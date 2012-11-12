<?php
  require_once "_app_init.php";
  
  list($announcements, $meta) = getRecords(array(
    'tableName' => 'announcements',
  ));
  
?>

<?php include "_header.php" ?>

<?php foreach ($announcements as $announcement): ?>
  <div>
    <a href="<?php echo $announcement['_link'] ?>"><?php echo htmlspecialchars($announcement['title']) ?></a><br/>
    [DATE AGO] <?php echo htmlspecialchars($announcement['summary']) ?>
    <a href="<?php echo $announcement['_link'] ?>">(read more)</a>
  </div>
<?php endforeach ?>

<?php include "_footer.php" ?>
