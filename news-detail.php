<?php
  require_once "_app_init.php";
  
  list($announcements, $meta) = getRecords(array(
    'tableName' => 'announcements',
    'where'     => whereRecordNumberInUrl(0),
  ));
  $announcement = @$announcements[0];
  if (!$announcement) { dieWith404("Record not found!"); }
  
?>

<?php include "_header.php" ?>

<h1><?php echo htmlspecialchars($announcement['title']) ?></h1>
[DATE AGO]<br/>
<?php echo $announcement['content'] ?>

<?php include "_footer.php" ?>
