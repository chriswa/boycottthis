<?php

require_once "../../../lib/viewer_functions.php";

global $schemas;
$schemas = getSortedSchemas();

$report = array();
foreach ($schemas as $tableName => $schema) {
  $report[$tableName] = array();
  foreach ($schema as $fieldName => $fieldSchema) {
    if (!is_array($fieldSchema)) { continue; }
    $result = checkField($tableName, $fieldName, $schema, $fieldSchema);
    if ($result) { $report[$tableName][$fieldName] = $result; }
  }
}

function checkField($tableName, $fieldName, $schema, $fieldSchema) {
  $fieldType = @$fieldSchema['type'];
  
  // custom relationships added via rd_addRelationship can do a lookup
  $customRelationship = @$GLOBALS['DBX_RELATIONSHIPS'][$tableName][$fieldName];
  if ($customRelationship) {
    $foreignTableName       = @$customRelationship['relatedTable'];
    $isSingularRelationship = @$customRelationship['singular'];
    if (@$customRelationship['relatedTableFromField']) {
      $foreignTableName = "dynamic (relatedTableFromField = {$customRelationship['relatedTableFromField']})";
    }
    return array($foreignTableName, !$isSingularRelationship);
  }
  
  // 'createdBy' and 'updatedBy' are special fieldnames which do lookups in 'accounts' with the 'createdByUserNum' or 'updatedByUserNum' field, respectively
  if ($fieldName == 'createdBy' || $fieldName == 'updatedBy') {
    return array('accounts', false);
  }
  
  // 'upload' field types can do a special lookup in the 'uploads' table
  if ($fieldType == 'upload') {
    return array('uploads', true);
  }
  
  // 'list' field types that are optionsType=>table and optionsValueField=>num can do a lookup
  if ($fieldType == 'list' && @$fieldSchema['optionsType'] == 'table' && @$fieldSchema['optionsValueField'] == 'num') {
    $foreignTableName = @$fieldSchema['optionsTablename'];
    $isMultiList = (@$fieldSchema['listType'] == 'checkboxes' || @$fieldSchema['listType'] == 'pulldownMulti');
    return array($foreignTableName, $isMultiList);
  }
  
  // 'relatedRecords' field types can do a lookup
  if ($fieldType == 'relatedRecords') {
    $foreignTableName = @$fieldSchema['relatedTable'];
    return array($foreignTableName, true);
  }
  
  return null;
}

/*
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
*/

?>

<style>
  table { border: 1px solid black; padding: 2px; margin-top: 5px; border-collapse: collapse; margin-bottom: 5px; }
  thead > tr { background-color: #ccc !important; }
  tbody > tr:nth-child(odd) { background-color: #fff; }
  tbody > tr:nth-child(even) { background-color: #eee; }
  td, th { padding: 5px; margin: 0; }
  th { text-align: left; }
  h2 { margin: 0px; padding: 5px; background: #eee; }
</style>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
$(function(){
  $("div").draggable();
});
</script>

<?php foreach ($report as $tableName => $fields): ?>
  <?php if ($fields): ?>
    <div style="float: left; margin: 10px;">
    <h2><a name="<?php echo $tableName ?>"><?php echo $tableName ?></a></h2>
    <table>
      <tr>
        <th>Field</th>
        <th>Foreign Table</th>
        <th>Plurality</th>
      </tr>
      <?php foreach ($fields as $fieldName => $info): ?>
        <tr>
          <td><?php echo $fieldName ?></td>
          <td><a href="#<?php echo $info[0] ?>"><?php echo $info[0] ?></a></td>
          <td><?php echo $info[1] ? 'many' : 'one' ?></td>
        </tr>
      <?php endforeach ?>
    </table>
    </div>
  <?php endif ?>
<?php endforeach ?>

<div style="height: 1024px; width: 1px; clear: both;"></div>