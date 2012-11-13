<?php
  require_once "_app_init.php";
  
  list($announcements, $meta) = getRecords(array(
    'tableName' => 'announcements',
    'perPage'   => 10,
  ));
  
?>

<?php include "_header.php" ?>

<?php foreach ($announcements as $announcement): ?>
  <div>
    <a href="<?php echo $announcement['_link'] ?>"><?php echo htmlspecialchars($announcement['title']) ?></a><br/>
    <?php echo pretty_relative_time($announcement['date']) ?><br/>
    <?php echo htmlspecialchars($announcement['summary']) ?>
    <a href="<?php echo $announcement['_link'] ?>">(read more)</a>
  </div>
<?php endforeach ?>

<?php if ($meta['prevPage']): ?><a href="<?php echo $meta['prevPageLink'] ?>">&lt;&lt; prev</a><?php else: ?>&lt;&lt; prev<?php endif ?>
- page <?php echo $meta['page'] ?> of <?php echo $meta['totalPages'] ?> -
<?php if ($meta['nextPage']): ?><a href="<?php echo $meta['nextPageLink'] ?>">next &gt;&gt;</a><?php else: ?>next &gt;&gt;<?php endif ?>

<?php include "_footer.php" ?>
