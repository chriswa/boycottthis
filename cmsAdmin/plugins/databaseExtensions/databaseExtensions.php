<?php
/*
Plugin Name: Database Extensions
Description: Provides extra database functions, especially regarding relationships between records
Version: 0.4
Requires at least: 2.13
Required System Plugin: Yes
*/

/*
  DRILL:
    
    // drill into a List field called 'supplier' (a single value list field)
    $supplierRecord = drill($productRecord, 'supplier');
    
    // drill into a List field called 'categories' (multi value list field)
    $categoryRecords = drill($productRecord, 'categories');
    
    // drill into a Related Records field called 'products' (or a custom relationship - see below)
    $productRecords = drill($categoryRecord, 'products');
  
  INJECT:
    
    // inject category records into every record in a list of records (i.e. $productRecords[*]['category'])
    dbx_inject($productRecords, 'category');
    
    // inject category record into a single record
    dbx_inject($productRecord, 'category');
    
    // inject category records into a list of records, and createdBy accounts into those categories (i.e. $productRecords[*]['category']['createdBy'])
    dbx_inject($productRecords, 'category', 'createdBy');
    
  
  
  TODO:
    - re-style this as a "DB" plugin with drilling being a primary feature instead of it being drill-specific.
    - a "db_set($record, $fieldName, $object)" function?? (i.e. assign a relationship, much like create does)
    - db_inject function which drills a record (or a list of records) and puts the results into the original record array (or list of records)
    ? drill custom SQL list fields
    ? drill database list fields which are not keyed on 'num'
*/

//=============================================================================
// All customizations should go in customRelationships.php. See readme.txt for details.
//
// DON'T UPDATE ANYTHING IN THIS FILE
//=============================================================================

$GLOBALS['DBX_RELATIONSHIPS'] = array();

//=============================================================================
// Drill!
//=============================================================================

// e.g. $categoryRecord = drill($productRecord, 'category');
// e.g. $productRecords = drill($categoryRecord, 'products'); // for this to work, you'll need to create a Related Record field or use dbx_addRelationship() in customRelationships.php

// dbx_drill($record, $fieldName, $extraWhere = null, $returnType = "default");
function dbx_drill($record, $fieldName, $extraWhere = null, $returnType = 'default') {
  $tableName   = dbx_getTableNameFromRecord($record);
  $schema      = loadSchema($tableName);
  $fieldSchema = @$schema[$fieldName];
  //if (!$fieldSchema || !is_array($fieldSchema)) { die(__FUNCTION__.": field '$fieldName' not found in schema '$tableName'"); }
  $fieldValue = @$record[$fieldName];
  $fieldType  = @$fieldSchema['type'];
  
  // custom relationships added via rd_addRelationship can do a lookup
  $customRelationship = @$GLOBALS['DBX_RELATIONSHIPS'][$tableName][$fieldName];
  if ($customRelationship) {
    $foreignTableName   = coalesce( @$customRelationship['relatedTable'], @$record[ @$customRelationship['relatedTableFromField'] ] );
    $foreignTableSchema = loadSchema($foreignTableName);
    if (!$foreignTableSchema) { die(__FUNCTION__.": could not load related table '$foreignTableName'"); }
    
    $relatedWhere = array();
    foreach ($customRelationship['relatedCondition'] as $foreignField => $value) {
      list($foreignField, $suffixChar) = extractSuffixChar($foreignField, '=$');
      if ($suffixChar != '=') { $value = @$record[ $value ]; } // suffix= is a literal, otherwise the relationship is telling us what fieldName to use in the where clause
      $relatedWhere[$foreignField] = $value;
    }
    
    $foreignRecords = mysql_select($foreignTableName, dbx_whereAnd($relatedWhere, $extraWhere));
    $isSingularRelationship = @$customRelationship['singular'];
    if ($isSingularRelationship) { return @$foreignRecords[0]; }
    else                         { return $foreignRecords;     }
  }
  
  // 'createdBy' and 'updatedBy' are special fieldnames which do lookups in 'accounts' with the 'createdByUserNum' or 'updatedByUserNum' field, respectively
  if ($fieldName == 'createdBy' || $fieldName == 'updatedBy') {
    return mysql_get('accounts', @$record[$fieldName . 'UserNum']);
  }
  
  // 'upload' field types can do a special lookup in the 'uploads' table
  if ($fieldType == 'upload') {
    $relatedWhere = array(
      'tableName' => $tableName,
      'fieldName' => $fieldName,
      'recordNum' => $record['num'],
    );
    return mysql_select('uploads', dbx_whereAnd($relatedWhere, $extraWhere));
  }
  
  // 'list' field types that are optionsType=>table and optionsValueField=>num can do a lookup
  if ($fieldType == 'list' && @$fieldSchema['optionsType'] == 'table' && @$fieldSchema['optionsValueField'] == 'num') {
    $foreignTableName = @$fieldSchema['optionsTablename'];
    $isMultiList = (@$fieldSchema['listType'] == 'checkboxes' || @$fieldSchema['listType'] == 'pulldownMulti');
    if (!$isMultiList) {
      $foreignRecord = mysql_get($foreignTableName, $fieldValue);
      if ($returnType == 'label') { return @$foreignRecord[ @$fieldSchema['optionsLabelField'] ]; }
      else                        { return $foreignRecord; }
    }
    else {
      $foreignNums = mysql_getValuesAsCSV(explode("\t", trim($fieldValue)));
      $foreignRecords = mysql_select($foreignTableName, dbx_whereAnd("num IN ($foreignNums)", $extraWhere));
      if ($returnType == 'labels') { return array_pluck($foreignRecords, @$fieldSchema['optionsLabelField']); }
      else                         { return $foreignRecords; }
    }
  }
  
  // 'relatedRecords' field types can do a lookup
  if ($fieldType == 'relatedRecords') {
    $foreignTableName = @$fieldSchema['relatedTable'];
    if ($returnType == 'tableName') { return $foreignTableName; }

    // eval relatedWhere with rawData
    $oldGlobalRecord = @$GLOBALS['RECORD']; // don't overwrite someone else's global
    $GLOBALS['RECORD'] = $record; // provide access for getEvalOutput
    $relatedWhere = coalesce(getEvalOutput( @$fieldSchema['relatedWhere'] ), 'TRUE');
    $GLOBALS['RECORD'] = $oldGlobalRecord; // put the global back the way we found it

    return mysql_select($foreignTableName, dbx_whereAnd($relatedWhere, $extraWhere));
  }
  
  die(__FUNCTION__.": no such relationship '$fieldName' from schema '$tableName'");
}

//
function dbx_drillByNum($record, $fieldName, $getNum = null, $returnType = 'default') {
  if (!$getNum) {
    return null;
  }
  return dbx_first(dbx_drill($record, $fieldName, array('num' => $getNum), $returnType));
}

//=============================================================================
// Inject
//=============================================================================

// drill relationships and inject results into recordset
// example:
//  $categories = mysql_select('categories');
//  dbx_inject($categories, 'createdBy');
//  showme($categories[0]['createdBy']['username']);
// OR
//  dbx_inject($categories, 'createdBy', 'photos');
//  showme($categories[0]['createdBy']['photos'][0]['thumbUrlPath']);
function dbx_inject(&$records, $relationshipFieldName) {
  global $where, $keyFields, $multiKeyFields, $foreignRecordsByKeys, $isMultiRelationship, $relationshipFieldName, $alreadyInjected; // for callbacks
  
  // if we got an empty record set, nothing to do!
  if (!$records) { return; }
  
  // interpret function arguments
  $relationshipFieldNames = func_get_args();
  array_shift($relationshipFieldNames); // get rid of the first element
  
  // if we were called with a single record, call self with it in an array
  if (@$records['_tableName']) {
    call_user_func_array('dbx_inject', array_merge(array(array(&$records)), $relationshipFieldNames));
    return;
  }
  
  // keep track of how deep we are in the record array
  $currentFieldList = array(); // e.g. array($fieldName => $isMulti, ...)
  
  // determine which table we're starting from
  $tableName = dbx_getTableNameFromRecord(reset($records)); // reset() returns the first record
  
  // for each relationship to inject...
  foreach ($relationshipFieldNames as $relationshipFieldName) {
    
    // get information about this relationship
    $schema      = loadSchema($tableName);
    $fieldSchema = @$schema[$relationshipFieldName];
    @list($foreignTableName, $isMultiRelationship, $relationshipCondition) = _dbx_getRelationshipDetails($tableName, $relationshipFieldName, $schema, $fieldSchema);
    
    $where = array();
    
    // analyse $relationshipCondition to determine which fields to index for matching up related records with local records
    $keyFields = array();
    $multiKeyFields = array();
    foreach ($relationshipCondition as $matchFieldName => $matchValue) {
      list($matchFieldName, $suffixChar) = extractSuffixChar($matchFieldName, '=*');
      if ($suffixChar == '=') {
        $where[$matchFieldName] = $matchValue;
      }
      elseif ($suffixChar == '*') {
        $keyFields[$matchFieldName] = $matchValue;
        $multiKeyFields[$matchFieldName] = $matchValue;
      }
      else {
        $keyFields[$matchFieldName] = $matchValue;
      }
    }
    
    // traverse records to build WHERE clause for SELECTing related records (note that we also use this traversal to check if this injection has already occurred)
    $alreadyInjected = false;
    foreach ($keyFields as $keyForeignField => $keyLocalField) {
      $where[$keyForeignField . '[]'] = array();
    }
    traverseRecordSetRelationships($records, $currentFieldList, '_dbx_inject_buildWhere'); // uses globals $keyFields, $multiKeyFields, $where, $alreadyInjected, $relationshipFieldName
    
    // clean up duplicates
    foreach ($keyFields as $keyForeignField => $keyLocalField) {
      $where[$keyForeignField . '[]'] = array_unique($where[$keyForeignField . '[]']);
    }
    
    // if we haven't already injected this relationship into this record set...
    if (!$alreadyInjected) {
    
      // SELECT the foreign records
      //showme('MYSQL_SELECT ' . $foreignTableName); showme(dbx_mysql_where2($where));
      $foreignRecords = mysql_select($foreignTableName, dbx_mysql_where2($where));
      
      // index foreign records by $keyFields
      $foreignRecordsByKeys = array();
      foreach ($foreignRecords as $foreignRecord) {
        $key = '';
        foreach ($keyFields as $keyForeignField => $keyLocalField) {
          $key .= '<' . htmlspecialchars($foreignRecord[$keyForeignField]); // XXX: any encode function will do
        }
        if (!@$foreignRecordsByKeys[$key]) { $foreignRecordsByKeys[$key] = array(); }
        $foreignRecordsByKeys[$key][] = $foreignRecord;
      }
      
      // inject foreign records into local records
      traverseRecordSetRelationships($records, $currentFieldList, '_dbx_inject_doInject'); // uses globals $keyFields, $foreignRecordsByKeys, $isMultiRelationship, $relationshipFieldName
    }
    
    // end of loop, prepare for next iteration
    $tableName = $foreignTableName;
    $currentFieldList[$relationshipFieldName] = $isMultiRelationship;
  }
}

function _dbx_inject_buildWhere($record) {
  global $where, $keyFields, $multiKeyFields, $alreadyInjected, $relationshipFieldName;
  
  // check if we've already injected into this record
  if ($alreadyInjected || @is_array(@$record[$relationshipFieldName])) { $alreadyInjected = true; return; }
  
  // build where
  foreach ($keyFields as $keyForeignField => $keyLocalField) {
    if (@$multiKeyFields[$keyForeignField]) {
      $nums = _dbx_inject_getListValues($record[$keyLocalField]);
      $where[$keyForeignField . '[]'] = array_merge($where[$keyForeignField . '[]'], $nums);
    }
    else {
      $where[$keyForeignField . '[]'][] = $record[$keyLocalField];
    }
  }
}

function _dbx_inject_doInject($record) {
  global $keyFields, $multiKeyFields, $foreignRecordsByKeys, $isMultiRelationship, $relationshipFieldName;
  if ($multiKeyFields) {
    // XXX: assume only one multiKeyField!
    $multiKeyField = reset($multiKeyFields);
    $preKey  = '';
    $postKey = '';
    foreach ($keyFields as $keyForeignField => $keyLocalField) {
      if ($keyLocalField == $multiKeyField) { $preKey = $postKey; $postKey = ''; continue; }
      $postKey .= '<' . htmlspecialchars($record[$keyLocalField]); // XXX: any encode function will do
    }
    $nums = _dbx_inject_getListValues($record[$multiKeyField]);
    $record[$relationshipFieldName] = array();
    foreach ($nums as $num) {
      $key = $preKey . '<' . htmlspecialchars($num) . $postKey;
      $answer = array_value(coalesce(@$foreignRecordsByKeys[$key], array()), 0);
      $record[$relationshipFieldName][] = $answer;
    }
  }
  else {
    $key = '';
    foreach ($keyFields as $keyForeignField => $keyLocalField) {
      if (@$multiKeyFields[$keyForeignField]) { continue; }
      $key .= '<' . htmlspecialchars($record[$keyLocalField]); // XXX: any encode function will do
    }
    $answer = coalesce(@$foreignRecordsByKeys[$key], array());
    if (!$isMultiRelationship) { $answer = @reset($answer); }
    $record[$relationshipFieldName] = $answer;
  }
}

//=============================================================================
// Relationships
//=============================================================================

function dbx_addRelationship($options) {
  foreach (array('tableName', 'relationshipName', 'relatedCondition') as $requiredOptionKey) {
    if (!@$options[$requiredOptionKey]) { die(__FUNCTION__.": '$requiredOptionKey' is required!"); }
  }
  if (!@$options['relatedTable'] && !@$options['relatedTableFromField']) { die(__FUNCTION__.": either 'relatedTable' or 'relatedTableFromField' is required!"); }
  
  $GLOBALS['DBX_RELATIONSHIPS'][ $options['tableName'] ][ $options['relationshipName'] ] = $options;
}

function dbx_addRel($tableNameAndRelationshipName, $relatedTable, $relatedCondition, $singular = false) {
  list($tableName, $relationshipName) = explode('.', $tableNameAndRelationshipName);
  dbx_addRelationship(array(
    'tableName'        => $tableName,
    'relationshipName' => $relationshipName,
    'singular'         => !!$singular,
    'relatedTable'     => $relatedTable,
    'relatedCondition' => $relatedCondition,
  ));
}

//=============================================================================
// Internal
//=============================================================================

// 
function dbx_getTableNameFromRecord($record) {
  if (!is_array($record)) { showme(debug_backtrace()); die(__FUNCTION__ . ": not an array"); }
  $tableName = @$record['_tableName'];
  if (!$tableName) { showme(debug_backtrace()); die(__FUNCTION__ . ": record must have _tableName key defined"); }
  return $tableName;
}

// 
function dbx_whereAnd($cond1, $cond2) {
  if (!$cond2) { return $cond1; }
  if (is_array($cond1)) { $cond1 = mysql_where($cond1); }
  if (is_array($cond2)) { $cond2 = mysql_where($cond2); }
  return "($cond1) AND ($cond2)";
}

// given a single record or an array of records, return an array of records while preserving references
// NOTE: this function must be called with a preceeding &, like so:
// $recordSet = &dbx_recordOrRecordSetToRecordSet($recordOrRecordSet);
function &dbx_recordOrRecordSetToRecordSet(&$recordOrRecordSet) {
  if (@$recordOrRecordSet['_tableName']) {
    $array = array(&$recordOrRecordSet);
    return $array;
  }
  else {
    return $recordOrRecordSet;
  }
}

//
function dbx_blessRecord(&$record, $tableName) {
  $record['_tableName'] = $tableName;
  return $record;
}

//
function dbx_blessRecords(&$recordSet, $tableName) {
  foreach ($recordSet as $key => $value) {
    $recordSet[$key]['_tableName'] = $tableName;
  }
  return $recordSet;
}

// return an array of list values
function _dbx_inject_getListValues($fieldValue) {
  $array = explode("\t", $fieldValue);
  if (count($array) == 1) { return array(); } // not a properly-formed multi-select field

  $array = array_slice($array, 1, -1); // remove blanks from leading/trailing tabs
  return $array;
}

function traverseRecordSetRelationships(&$array, $fieldList, $callback) {
  foreach (array_keys($array) as $recordKey) {
    _traverseRecordSetRelationships($array[$recordKey], $fieldList, $callback);
  }
}
function _traverseRecordSetRelationships(&$array, $fieldList, $callback) {
  if (!$fieldList) {
    call_user_func_array($callback, array(&$array));
    return;
  }
  
  // shift first key-value pair off of $fieldList
  foreach ($fieldList as $nextField => $nextFieldIsMulti) { break; }
  array_shift($fieldList);
  
  if ($nextFieldIsMulti) {
    foreach (array_keys($array[$nextField]) as $recordKey) {
      _traverseRecordSetRelationships($array[$nextField][$recordKey], $fieldList, $callback);
    }
  }
  else {
    _traverseRecordSetRelationships($array[$nextField], $fieldList, $callback);
  }
}
  
// @list($foreignTableName, $isMultiRelationship, $relationshipCondition) = checkField($tableName, $fieldName, $schema, $fieldSchema);
function _dbx_getRelationshipDetails($tableName, $fieldName, $schema, $fieldSchema) {
  
  // custom relationships added via rd_addRelationship can do a lookup
  $customRelationship = @$GLOBALS['DBX_RELATIONSHIPS'][$tableName][$fieldName];
  if ($customRelationship) {
    $foreignTableName       = @$customRelationship['relatedTable'];
    $isSingularRelationship = @$customRelationship['singular'];
    if (@$customRelationship['relatedTableFromField']) {
      die(__FUNCTION__ . ': relatedTableFromField relationships not supported');
    }
    return array($foreignTableName, !$isSingularRelationship, $customRelationship['relatedCondition']);
  }
  
  // if there's no customRelationship, we can infer relationships with $fieldSchema
  $fieldType = @$fieldSchema['type'];
  
  // 'createdBy' and 'updatedBy' are special fieldnames which do lookups in 'accounts' with the 'createdByUserNum' or 'updatedByUserNum' field, respectively
  if ($fieldName == 'createdBy' || $fieldName == 'updatedBy') {
    return array('accounts', false, array('num' => $fieldName . 'UserNum'));
  }
  
  // 'upload' field types can do a special lookup in the 'uploads' table
  if ($fieldType == 'upload') {
    return array('uploads', true, array('tableName=' => $tableName, 'fieldName=' => $fieldName, 'recordNum' => 'num'));
  }
  
  // 'list' field types that are optionsType=>table and optionsValueField=>num can do a lookup
  if ($fieldType == 'list' && @$fieldSchema['optionsType'] == 'table' && @$fieldSchema['optionsValueField'] == 'num') {
    $foreignTableName = @$fieldSchema['optionsTablename'];
    $isMultiList = (@$fieldSchema['listType'] == 'checkboxes' || @$fieldSchema['listType'] == 'pulldownMulti');
    if ($isMultiList) {
      return array($foreignTableName, true, array('num*' => $fieldName));
    }
    else {
      return array($foreignTableName, false, array('num' => $fieldName));
    }
    
  }
  
  // 'relatedRecords' field types can do a lookup
  if ($fieldType == 'relatedRecords') {
    die(__FUNCTION__ . ': relatedRecords fields not supported (try a customRelationship instead)');
  }
  
  die(__FUNCTION__ . ": could not find relationship: $tableName.$fieldName");
}

// convenience function for turning an array into a WHERE clause
function dbx_mysql_where2($criteriaArray = null, $extraWhere = 'TRUE') {
  $where = '';
  if ($criteriaArray) {
    foreach ($criteriaArray as $fieldName => $value) {
      
      // IN (e.g. 'num[]' => array(1,2,3))
      if (preg_match('/^(\w+)\[\]$/', $fieldName, $matches)) {
        if (is_array($value) && $value) {
          list(, $fieldName) = $matches;
          $valuesCSV = mysql_getValuesAsCSV($value);
          $where .= "`$fieldName` IN ($valuesCSV) AND ";
        }
        else {
          $where .= "FALSE AND ";
        }
      }
      
      // EQUALS (e.g. 'num' => 1)
      elseif (preg_match('/^(\w+)$/', $fieldName)) {
        $where .= mysql_escapef("`$fieldName` = ? AND ", $value);
      }
      else {
        die(__FUNCTION__. ": Invalid column name '" .htmlspecialchars($fieldName). "'!"); // error checking: whitelist column chars to prevent sql injection
      }
    }
  }
  $where .= $extraWhere;
  return $where;
}

//=============================================================================
// Convenience
//=============================================================================

function dbx_first($recordSet) {
  if (!is_array($recordSet) || !$recordSet) { return null; }
  return reset($recordSet); // return first element
}

/*function dbx_num($num, $moreWhere = null) {
  return array_merge(coalesce($moreWhere, array()), array('num' => $num));
}*/

function dbx_owner($moreWhere = null) {
  global $CURRENT_USER;
  return array_merge(coalesce($moreWhere, array()), array('createdByUserNum' => @$CURRENT_USER['num']));
}

// add pseudo fields and uploads to a record or a list of records
// example:
//   $products = mysql_select('products');
//   dbx_decorate($products);
//   showme($products[0]['photos']);
// example:
//   $product = mysql_get('product', $productNum);
//   dbx_decorate($product);
//   showme($product['categories:labels']);
function dbx_decorate(&$recordOrRecordSet, $options = null) {
  
  $recordSet = &dbx_recordOrRecordSetToRecordSet($recordOrRecordSet);
  if (empty($recordSet)) { return $recordSet; } // empty recordSet?
  
  $tableName = dbx_getTableNameFromRecord(@$recordSet[0]);
  $schema    = loadSchema($tableName);
  
  $options = coalesce($options, array());
  $options['tableName'] = $tableName;
  
  require_once("lib/viewer_functions.php");
  
  // add '_link'
  foreach ($recordSet as $key => $record) {
    $filenameValue       = getFilenameFieldValue($record, @$schema['_filenameFields']);
    $record['_filename'] = rtrim($filenameValue, '-');
    if    (@!$schema['_detailPage']) { $recordSet[$key]['_link'] = "javascript:alert('Set Detail Page Url for this section in: Admin > Section Editors > Viewer Urls')"; }
    elseif(@$options['useSeoUrls'])  { $recordSet[$key]['_link'] = @$schema['_detailPage'] . '/' . $filenameValue . $record['num'] . "/"; }
    else                             { $recordSet[$key]['_link'] = @$schema['_detailPage'] . '?' . $filenameValue . $record['num']; }
  }
  
  // add :pseudo fields
  _getRecords_addPseudoFields($recordSet, $options, $schema);
  
  // add uploads
  addUploadsToRecords($recordSet);
  
  return $recordOrRecordSet;
}

// update or insert a record (obtained by mysql_get, mysql_select, or rd_create)
// dbx_save($record);
function dbx_save(&$record) {
  global $schema;
  
  // save state (we might be called from another schema's record_presave!)
  $oldSchema = $schema;
  
  $tableName = dbx_getTableNameFromRecord($record);
  $schema    = loadSchema($tableName);
  
  $oldRecord   = $record;
  $isNewRecord = !(@$record['num'] > 0);
  doAction('record_presave', $tableName, $isNewRecord, $oldRecord);
  //doAction('record_save_errorchecking', $tableName, !$isNewRecord, $oldRecord);
  //doAction('record_save_posterrorchecking', $tableName, !$isNewRecord, $oldRecord);
  
  if (@$schema['updatedDate']      && !array_key_exists('updatedDate',      $record)) { $record['updatedDate']      = mysql_datetime(); }
  if (@$schema['updatedByUserNum'] && !array_key_exists('updatedByUserNum', $record)) { $record['updatedByUserNum'] = coalesce(@$GLOBALS['CURRENT_USER']['num'], 0); }
  
  // 
  $columnsToValues = $record;
  unset($columnsToValues['_tableName']);
  foreach ($columnsToValues as $key => $value) {
    if (is_array($value) || is_object($value) || !is_array(@$schema[$key])) {
      unset($columnsToValues[$key]);
    }
  }
  
  if (@$record['num']) {
    unset($columnsToValues['createdDate']);
    unset($columnsToValues['createdByUserNum']);
    mysql_update($tableName, $record['num'], null, $columnsToValues);
  }
  else {
    if (@$schema['createdDate']      && !array_key_exists('createdDate',      $record)) { $record['createdDate']      = $columnsToValues['createdDate']      = mysql_datetime(); }
    if (@$schema['createdByUserNum'] && !array_key_exists('createdByUserNum', $record)) { $record['createdByUserNum'] = $columnsToValues['createdByUserNum'] = coalesce(@$GLOBALS['CURRENT_USER']['num'], 0); }
    $record['num'] = mysql_insert($tableName, $columnsToValues);
  }
  
  doAction('record_postsave', $tableName, $isNewRecord, $oldRecord, $record['num']);
  
  // restore state
  $schema = $oldSchema;
  
  return $record;
}

// delete a record (obtained by mysql_get, mysql_select, or rd_create)
// rd_delete($record);
function dbx_erase($record) {
  $tableName = dbx_getTableNameFromRecord($record);
  
  $recordNumsAsCSV = '0,' . intval($record['num']);
  
  doAction('record_preerase', $tableName, $recordNumsAsCSV);
  mysql_delete($tableName, $record['num']);
  doAction('record_posterase', $tableName, $recordNumsAsCSV);
}

// create a new record with default field values; it won't be inserted until you call dbx_save()
// $record = dbx_create('tableName');
// $record = dbx_create('tableName', array('fieldName' => 123));
function dbx_create($tableName, $record = null) {
  global $schema;
  
  // push global state
  $oldSchema = $schema;
  $oldRecord = @$GLOBALS['RECORD'];
  
  $schema = loadSchema($tableName);
  
  $GLOBALS['RECORD'] = array(); // for getEvalOutput code
  
  // supply defaults for this new record
  if (!$record) { $record = array(); }
  //$record = _addUndefinedDefaultsToNewRecord($suppliedFields, getMySqlColsAndType(mysql_escape(getTableNameWithPrefix($tableName)))); // XXX: this doesn't supply defaults for non-adminOnly fields!
  foreach ($schema as $fieldName => $fieldSchema) {
    if (!is_array($fieldSchema)) { continue; }
    
    // skip if already defined
    if (array_key_exists($fieldName, $record)) { continue; }

    // defaults for special fields
    if      ($fieldName == 'createdDate')      { $record[$fieldName] = mysql_datetime(getAdjustedLocalTime()); }
    else if ($fieldName == 'createdByUserNum') { $record[$fieldName] = @$GLOBALS['CURRENT_USER']['num']; }
    else if ($fieldName == 'updatedDate')      { $record[$fieldName] = mysql_datetime(getAdjustedLocalTime()); }
    else if ($fieldName == 'updatedByUserNum') { $record[$fieldName] = @$GLOBALS['CURRENT_USER']['num']; }
    else if ($fieldName == 'dragSortOrder')    { $record[$fieldName] = time(); }
    else if ($fieldName == 'siblingOrder')     { $record[$fieldName] = time(); }
    else {

      // defaults for field types
      $fieldType = @$fieldSchema['type'];
      if      ($fieldType == 'textfield') { $record[$fieldName] = getEvalOutput(@$fieldSchema['defaultValue']); }
      else if ($fieldType == 'list')      { $record[$fieldName] = getEvalOutput(@$fieldSchema['defaultValue']); }
      else if ($fieldType == 'textbox')   { $record[$fieldName] = getEvalOutput(@$fieldSchema['defaultContent']); }
      else if ($fieldType == 'wysiwyg')   { $record[$fieldName] = getEvalOutput(@$fieldSchema['defaultContent']); }
      else if ($fieldType == 'checkbox')  { $record[$fieldName] = (int) @$fieldSchema['checkedByDefault']; }
      else if ($fieldType == 'date') {
        if     (@$fieldSchema['defaultDate'] == 'none')   { $record[$fieldName] = '0000-00-00 00:00:00'; }
        elseif (@$fieldSchema['defaultDate'] == 'custom') { $record[$fieldName] = @mysql_datetime(strtotime($fieldSchema['defaultDateString'])); }
        else                                              { $record[$fieldName] = mysql_datetime(getAdjustedLocalTime()); }
      }
    }
  }
  
  // bless record
  dbx_blessRecord($record, $tableName);
  
  // pop global state
  $schema            = $oldSchema;
  $GLOBALS['RECORD'] = $oldRecord;
  
  return $record;
}

// shortcut for dbx_create() then dbx_save()
// dbx_createAndSave($tableName, array('fieldName' => 123));
function dbx_createAndSave($tableName, $fieldValues = null) {
  $record = dbx_create($tableName, $fieldValues);
  dbx_save($record);
  return $record;
}

// create a new record with default field values which is related to the reference record
// $record = dbx_createRelated($referenceRecord, $relatedRecordsFieldName, array('fieldName' => 123));
function dbx_createRelated($referenceRecord, $relatedRecordsFieldName, $suppliedFields = null) {
  $referenceTableName = dbx_getTableNameFromRecord($referenceRecord);
  
  $relatedTableName = null;
  $relatedValues    = array();
  
  // check for custom relationships added via rd_addRelationship
  $customRelationship = @$GLOBALS['DBX_RELATIONSHIPS'][$referenceTableName][$relatedRecordsFieldName];
  if ($customRelationship) {
    $relatedTableName = coalesce( @$customRelationship['relatedTable'], @$referenceRecord[ @$customRelationship['relatedTableFromField'] ] );
    foreach ($customRelationship['relatedCondition'] as $foreignField => $value) {
      list($foreignField, $suffixChar) = extractSuffixChar($foreignField, '=$');
      if (!$suffixChar == '=') { $value = @$referenceRecord[$value]; }
      $relatedValues[$foreignField] = $value;
    }
  }
  
  // otherwise, look for a relatedRecords field
  else {
    $referenceSchema      = loadSchema($referenceTableName);
    $referenceFieldSchema = @$referenceSchema[$relatedRecordsFieldName];
    if (@$referenceFieldSchema['type'] != 'relatedRecords') { die(__FUNCTION__ . ": '$relatedRecordsFieldName' is not a relatedRecords field"); }
    $relatedTableName = @$referenceFieldSchema['relatedTable'];
    
    // determine relatedRecord's rawData from field's relatedWhere
    $relatedConditions = preg_split('/\s+AND\s+/i', @$referenceFieldSchema['relatedWhere']); // note: naive where parsing!
    $oldGlobalRecord = @$GLOBALS['RECORD']; // push
    $GLOBALS['RECORD'] = $referenceRecord; // provide access for getEvalOutput
    foreach ($relatedConditions as $relatedCondition) {
      list($fieldName, $value) = preg_split('/\s*=\s*/', trim($relatedCondition));
      $value = trim($value, "'"); // naive mysql_unescape()
      $relatedValues[$fieldName] = getEvalOutput($value);
    }
    $GLOBALS['RECORD'] = $oldGlobalRecord; // pop
  }
  
  return dbx_create($relatedTableName, array_merge($relatedValues, $suppliedFields));
}

// shortcut for dbx_createRelated() then dbx_save()
// $record = dbx_createAndSaveRelated($referenceRecord, $fieldName, array('fieldName' => 123));
function dbx_createAndSaveRelated($referenceRecord, $fieldName, $suppliedFields = null) {
  $record = dbx_createRelated($referenceRecord, $fieldName, $suppliedFields);
  dbx_save($record);
  return $record;
}

// $record = dbx_getOrCreateAndSave($tableName, $whereAndRecord);
function dbx_getOrCreateAndSave($tableName, $whereAndAlsoRecord) {
  $record = mysql_get($tableName, null, $whereAndAlsoRecord);
  if ($record) { return $record; }
  return dbx_createAndSave($tableName, $whereAndAlsoRecord);
}

// $record = dbx_getOrCreateAndSaveRelated($referenceRecord, $fieldName, array('fieldName' => 123));
function dbx_getOrCreateAndSaveRelated($referenceRecord, $fieldName, $whereAndAlsoRecord) {
  $record = dbx_one(dbx_drill($user, $fieldName, $whereAndAlsoRecord));
  if ($record) { return $record; }
  return dbx_createAndSaveRelated($user, $fieldName, $whereAndAlsoRecord);
}


//=============================================================================
// Developer Tools
//=============================================================================

// show all possible relationships that can be drilled from a table
function dbx_showRelationships($recordOrTableName) {
  $tableName = $recordOrTableName;
  if (is_array($recordOrTableName)) {
    $tableName = dbx_getTableNameFromRecord($recordOrTableName);
  }
  $schema = loadSchema($tableName);
  
  $report = array();
  
  $isMultiToEnglish = array(
    true  => 'many',
    false => 'one',
  );
  
  // list customRelationships
  $customRelationships = coalesce(@$GLOBALS['DBX_RELATIONSHIPS'][$tableName], array());
  foreach ($customRelationships as $fieldName => $customRelationship) {
    $isSingularRelationship = @$customRelationship;
    $report[$fieldName] = array('table' => @$customRelationship['relatedTable'], 'isMulti' => $isMultiToEnglish[!@$customRelationship['singular']]);
  }
  
  // createdBy and updatedBy
  if (@$schema['createdByUserNum']) { $report['createdBy'] = array('table' => 'accounts', 'isMulti' => $isMultiToEnglish[false]); }
  if (@$schema['updatedByUserNum']) { $report['updatedBy'] = array('table' => 'accounts', 'isMulti' => $isMultiToEnglish[false]); }
  
  // list fields
  foreach ($schema as $fieldName => $fieldSchema) {
    if (!is_array($fieldSchema)) { continue; }
    if (@$fieldSchema['type'] == 'list' && @$fieldSchema['optionsType'] == 'table' && @$fieldSchema['optionsValueField'] == 'num') {
      $isMultiList = (@$fieldSchema['listType'] == 'checkboxes' || @$fieldSchema['listType'] == 'pulldownMulti');
      $report[$fieldName] = array('table' => @$fieldSchema['optionsTablename'], 'isMulti' => $isMultiToEnglish[$isMultiList]);
    }
  }
  
  // relatedRecords fields
  foreach ($schema as $fieldName => $fieldSchema) {
    if (!is_array($fieldSchema)) { continue; }
    if (@$fieldSchema['type'] == 'relatedRecords') {
      $report[$fieldName] = array('table' => @$fieldSchema['relatedTable'], 'isMulti' => $isMultiToEnglish[true]);
    }
  }
  
  // upload fields
  foreach ($schema as $fieldName => $fieldSchema) {
    if (!is_array($fieldSchema)) { continue; }
    if (@$fieldSchema['type'] == 'upload') {
      $report[$fieldName] = array('table' => 'uploads', 'isMulti' => $isMultiToEnglish[true]);
    }
  }
  
  //showme("Relationships for '$tableName'...");
  //showme($report);
  return $report;
}


//=============================================================================
// Load project-specific customizations
//=============================================================================

if (file_exists(dirname(__FILE__) . "/customRelationships.php")) {
  require_once('customRelationships.php');
}

?>
