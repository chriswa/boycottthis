<?php
  require_once "_app_init.php";
  
  $sortOption = coalesce(@$_REQUEST['sort'], 'biggest');
  $orderBy = array_value(array(
    'biggest' => 'pledge_count DESC',
    'newest'  => 'date_posted DESC',
  ), $sortOption);
  
  list($issues, $meta) = getRecords(array(
    'tableName' => 'issues',
    'orderBy'   => $orderBy,
  ));
  
?>

<?php include "_header.php" ?>

<div style="float: right;">
  <?php if ($sortOption === 'biggest'): ?><b>biggest</b><?php else: ?><a href="?sort=biggest">biggest</a><?php endif ?>
  |
  <?php if ($sortOption === 'newest'): ?><b>newest</b><?php else: ?><a href="?sort=newest">newest</a><?php endif ?>
</div>

<?php foreach ($issues as $issue): ?>
  <div>
    <a href="<?php echo $issue['_link'] ?>">
      <?php echo $issue['pledge_count'] ?> people are boycotting <?php echo htmlspecialchars($issue['organization']) ?>
    </a><br/>
    <?php echo htmlspecialchars($issue['summary']) ?>
    <a href="<?php echo $issue['_link'] ?>">(read more)</a>
  </div>
<?php endforeach ?>

<?php include "_footer.php" ?>
