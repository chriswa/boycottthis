<?php
  require_once "_app_init.php";
  
  $page = mysql_get('about_us', 1);
?>

<?php include "_header.php" ?>

<?php echo $page['content'] ?>

<?php include "_footer.php" ?>
