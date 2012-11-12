<?php
  require_once "_app_init.php";
  
  list($issues, $meta) = getRecords(array(
    'tableName' => 'issues',
    'where'     => whereRecordNumberInUrl(0),
  ));
  $issue = @$issues[0];
  if (!$issue) { dieWith404("Record not found!"); }
  
?>

<?php include "_header.php" ?>

<h1><?php echo htmlspecialchars($issue['title']) ?></h1>
[DATE AGO]<br/>
<?php echo $issue['content'] ?><br/>

TODO: suggest update form

<?php include "_footer.php" ?>
