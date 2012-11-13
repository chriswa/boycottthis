<?php
  // 
  require_once "../../../lib/viewer_functions.php";
  
  //
  $tableName = @$_REQUEST['tableName'];
  $record    = mysql_get($tableName, @$_REQUEST['num']);
  if (!$record) { dieWith404(); }
  
  //
  $relationships = dbx_showRelationships($record);
  
  function showFirstTextField($record) {
    $schema = loadSchema(dbx_getTableNameFromRecord($record));
    foreach ($schema as $fieldname => $fieldSchema) {
      if (!is_array($fieldSchema)) { continue; }  // skip table metadata - fields are arrays
      if (@$fieldSchema['type'] == 'textfield') {
        return $record[$fieldname];
      }
    }
    return '';
  }
?>
<h1><?php echo htmlspecialchars($tableName) ?>: <?php echo htmlspecialchars(showFirstTextField($record)) ?></h1>

<ul>
  <?php foreach ($relationships as $fieldName => $relationship): ?>
    <li>
      <strong><?php echo $fieldName ?></strong> -> <?php echo $relationship['table'] ?> (<?php echo $relationship['isMulti'] ?>)
      <ul>
        <?php
          $related = dbx_drill($record, $fieldName);
          if ($relationship['isMulti'] == 'one' && $related) { $related = array($related); }
        ?>
        <?php if ($related): ?>
          <?php foreach ($related as $foreignRecord): ?>
            <li>
              <a href="?tableName=<?php echo dbx_getTableNameFromRecord($foreignRecord) ?>&num=<?php echo $foreignRecord['num'] ?>">
                <?php echo htmlspecialchars(showFirstTextField($foreignRecord)) ?> (<?php echo $foreignRecord['num'] ?>)
              </a>
            </li>
          <?php endforeach ?>
        <?php else: ?>
          <li>(none)</li>
        <?php endif ?>
      </ul>
    </li>
  <?php endforeach ?>
</ul>

<?php showme($record) ?>
