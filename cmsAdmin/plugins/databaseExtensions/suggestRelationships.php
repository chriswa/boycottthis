<?php require_once "../../lib/viewer_functions.php"; ?>

<style>
  table { border: 1px solid black; padding: 2px; margin-top: 5px; border-collapse: collapse; margin-bottom: 5px; }
  thead > tr { background-color: #ccc !important; }
  tbody > tr:nth-child(odd) { background-color: #fff; }
  tbody > tr:nth-child(even) { background-color: #eee; }
  td, th { padding: 5px; margin: 0; }
  th { text-align: left; }
</style>

<table>
  <thead>
    <tr>
      <th>Database List Field</th>
      <th>Corresponding Related Records Field</th>
    </tr>
  </thead>
  <tbody>
<?php
  
  global $schemas;
  $schemas = getSortedSchemas();
  
  if (@$_REQUEST['add']) {
    add();
  }

  function add() {
    global $schemas;
    $foreignSchema = @$schemas[$_REQUEST['foreignSchemaName']];
    if (@$foreignSchema[$_REQUEST['foreignFieldName']]) { echo "<p style='color: red;'>Error! Cannot overwrite existing field!</p>"; return; }
    $foreignSchema[$_REQUEST['foreignFieldName']] = array(
      'order'           => time(),
      'label'           => "",
      'type'            => "relatedRecords",
      'relatedTable'    => $_REQUEST['schemaName'],
      'relatedLimit'    => "",
      'relatedView'     => "",
      'relatedModify'   => 1,
      'relatedErase'    => "",
      'relatedWhere'    => $_REQUEST['fieldName'] . "='<"."?php echo mysql_escape(@\$RECORD['num']) ?".">'",
      'relatedMoreLink' => $_REQUEST['fieldName'] . "_match=<"."?php echo htmlspecialchars(@\$RECORD['num']) ?".">",
    );
    saveSchema($_REQUEST['foreignSchemaName'], $foreignSchema);
    redirectBrowserToURL('?');
  }
  
  // find all "lookup" list fields (optionsType=>table and optionsValueField=>num)
  foreach ($schemas as $schemaName => $schema) {
    foreach ($schema as $fieldName => $fieldSchema) {
      if (!is_array($fieldSchema)) { continue; } // not a field
      if (@$fieldSchema['type']              != 'list' ) { continue; }
      if (@$fieldSchema['optionsType']       != 'table') { continue; }
      if (@$fieldSchema['optionsValueField'] != 'num'  ) { continue; }
      
      $foreignSchemaName     = @$fieldSchema['optionsTablename'];
      $foreignFieldNameGuess = pluralize($schemaName);
      
      reportRow($schemaName, $fieldName, $foreignSchemaName, $foreignFieldNameGuess);
    }
  }

?>
  </tbody>
</table>

<table cellspacing="0" cellpadding="5">
  <thead>
    <tr>
      <th>Database UserNum Field</th>
      <th>Corresponding Related Records Field</th>
    </tr>
  </thead>
  <tbody>
<?php
  
  // find all "createdByUserNum" and "updatedByUserNum" fields
  foreach ($schemas as $schemaName => $schema) {
    $fieldName = 'createdByUserNum';
    $fieldSchema = @$schema[$fieldName];
    if (!$fieldSchema || !is_array($fieldSchema)) { continue; } // missing or not a field
    
    $foreignFieldNameGuess = pluralize($schemaName);
    
    reportRow($schemaName, $fieldName, 'accounts', $foreignFieldNameGuess);
  }


?>
  </tbody>
</table>

<?php
  
  
  function reportRow($schemaName, $fieldName, $foreignSchemaName, $foreignFieldNameGuess) {
    global $schemas;
    $foreignSchema = @$schemas[$foreignSchemaName];
    if (!$foreignSchema) { return; }
    
    $expectedWhere = $fieldName . "='<"."?php echo mysql_escape(@\$RECORD['num']) ?".">'";
    
    // look for a relatedRecords foreign field which matches up with the list field
    $potentialMatchLog = '';
    $matchingRecField = null;
    foreach ($foreignSchema as $foreignFieldName => $foreignFieldSchema) {
      if (!is_array($foreignFieldSchema)) { continue; } // not a field
      if (@$foreignFieldSchema['type']         != 'relatedRecords') { continue; }
      if (@$foreignFieldSchema['relatedTable'] != $schemaName     ) { continue; }
      
      if (@$foreignFieldSchema['relatedWhere'] == $expectedWhere) {
        $matchingRecField = $foreignFieldName . " (found in schema)";
      }
      else {
        $potentialMatchLog .= "<tr><td>Schema</td><td><code>$foreignFieldName</code></td><td><code>" . htmlspecialchars(@$foreignFieldSchema['relatedWhere']) . "</code></td><tr>\n";
      }
    }
    
    // look for a DBX_RELATIONSHIPS entry which matches up with the list field
    $customRelationships = coalesce(@$GLOBALS['DBX_RELATIONSHIPS'][$foreignSchemaName], array());
    foreach ($customRelationships as $foreignFieldName => $foreignFieldSchema) {
      if (@$foreignFieldSchema['relatedTable'] != $schemaName) { continue; }
      
      if (@$foreignFieldSchema['relatedWhere'] == $expectedWhere) {
        $matchingRecField = $foreignFieldName . " (found in DBX_RELATIONSHIPS)";
      }
      else {
        $potentialMatchLog .= "<tr><td>DBX_RELATIONSHIPS</td><td><code>$foreignFieldName</code></td><td><code>" . htmlspecialchars(@$foreignFieldSchema['relatedWhere']) . "</code></td><tr>\n";
      }
    }
    
    $isMultiRecord = (@$fieldSchema['listType'] == 'checkboxes' || @$fieldSchema['listType'] == 'pulldownMulti');
    $fieldNotes = $isMultiRecord ? ' (multi value)' : '';
    
    if ($matchingRecField) {
      echo "<tr style='color: green;'><td>$schemaName.$fieldName$fieldNotes</td><td>$foreignSchemaName.$matchingRecField</td></tr>";
    }
    else {
      
      if ($potentialMatchLog) {
        $potentialMatchLog = "<table><thead><th>Potential Match Source</th><th>fieldName</th><th>relatedWhere</th></tr></thead><tbody>$potentialMatchLog</tbody></table>";
      }
      
      $addForm = "<form action=\"?\" method=\"post\" style=\"display: inline;\">";
      $addForm .= "<input type=\"hidden\" name=\"schemaName\" value=\"$schemaName\" />";
      $addForm .= "<input type=\"hidden\" name=\"fieldName\" value=\"$fieldName\" />";
      $addForm .= "<input type=\"hidden\" name=\"foreignSchemaName\" value=\"$foreignSchemaName\" />";
      $addForm .= "$foreignSchemaName.<input type=\"text\" name=\"foreignFieldName\" value=\"$foreignFieldNameGuess\" />";
      $addForm .= " <input type=\"submit\" name=\"add\" value=\"Add relatedRecords Field\" onclick=\"return confirm('Are you sure?')\" />";
      $addForm .= "</form>";
      
      $sampleCustomCode =  "dbx_addRelationship(array(\n";
      $sampleCustomCode .= "  'tableName'        => '$foreignSchemaName',\n";
      $sampleCustomCode .= "  'relationshipName' => '$foreignFieldNameGuess', // TO"."DO: decide on this relationship name\n";
      $sampleCustomCode .= "  'relatedTable'     => '$schemaName',\n";
      $sampleCustomCode .= "  'relatedCondition' => array( '$fieldName' => 'num' ),\n";
      $sampleCustomCode .= "));\n";
      
      echo "<tr><td style='color: red;' valign='top'>$schemaName.$fieldName$fieldNotes</td><td>$addForm<xmp style='margin: 0; color: purple;'>$sampleCustomCode</xmp>$potentialMatchLog</td></tr>\n";
    }
  }
  
  // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  function pluralize( $string ) {
    preg_match('/^(.*?)([a-z]*)$/i', $string, $matches);
    list(, $prefix, $word) = $matches;
    return $prefix . ($word ? _pluralize($word) : '');
  }
  
  function _pluralize( $string ) {
    $plural = array(
      '/(quiz)$/i'                      => "$1zes",
      '/^(ox)$/i'                       => "$1en",
      '/([m|l])ouse$/i'                 => "$1ice",
      '/(matr|vert|ind)ix|ex$/i'        => "$1ices",
      '/(x|ch|ss|sh)$/i'                => "$1es",
      '/([^aeiouy]|qu)y$/i'             => "$1ies",
      '/(hive)$/i'                      => "$1s",
      '/(?:([^f])fe|([lr])f)$/i'        => "$1$2ves",
      '/(shea|lea|loa|thie)f$/i'        => "$1ves",
      '/sis$/i'                         => "ses",
      '/([ti])um$/i'                    => "$1a",
      '/(tomat|potat|ech|her|vet)o$/i'  => "$1oes",
      '/(bu)s$/i'                       => "$1ses",
      '/(alias)$/i'                     => "$1es",
      '/(octop)us$/i'                   => "$1i",
      '/(ax|test)is$/i'                 => "$1es",
      '/(us)$/i'                        => "$1es",
      '/s$/i'                           => "s",
      '/$/'                             => "s"
    );
    
    $irregular = array(
      'move'   => 'moves',
      'foot'   => 'feet',
      'goose'  => 'geese',
      'sex'    => 'sexes',
      'child'  => 'children',
      'man'    => 'men',
      'tooth'  => 'teeth',
      'person' => 'people'
    );
    
    $uncountable = array(
      'sheep',
      'fish',
      'deer',
      'series',
      'species',
      'money',
      'rice',
      'information',
      'equipment'
    );
    
    // save some time in the case that singular and plural are the same
    if ( in_array( strtolower( $string ), $uncountable ) ) {
      return $string;
    }
    
    // check for irregular singular forms
    foreach ( $irregular as $pattern => $result ) {
      $pattern = '/' . $pattern . '$/i';
    
      if ( preg_match( $pattern, $string ) ) {
        return preg_replace( $pattern, $result, $string);
      }
    }
    
    // check for matches using regular expressions
    foreach ( $plural as $pattern => $result ) {
      if ( preg_match( $pattern, $string ) ) {
        return preg_replace( $pattern, $result, $string );
      }
    }
    
    return $string;
  }

if (!function_exists('_sortMenusByOrder')) {
function _sortMenusByOrder($fieldA, $fieldB) {

  // sort field meta data below sorted by "order" value
  $orderA = array_key_exists('menuOrder', $fieldA) ? $fieldA['menuOrder'] : 1000000000;
  $orderB = array_key_exists('menuOrder', $fieldB) ? $fieldB['menuOrder'] : 1000000000;
  if ($orderA < $orderB) { return -1; }
  if ($orderA > $orderB) { return 1; }
  return 0;
}
}

?>
