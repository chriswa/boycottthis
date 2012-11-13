<?php
  require_once "_app_init.php";
  
  $sortOption = coalesce(@$_REQUEST['sort'], 'biggest');
  $orderBy = array_value(array(
    'biggest' => 'pledge_count DESC',
    'newest'  => 'date_posted DESC',
  ), $sortOption);
  
  list($issues, $meta) = getRecords(array(
    'tableName' => 'issues',
    'perPage'   => 1,
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
      <?php echo $issue['pledge_count'] ?> people are boycotting <?php echo htmlspecialchars($issue['organization:label']) ?>
    </a><br/>
    <?php echo htmlspecialchars($issue['summary']) ?>
    <a href="<?php echo $issue['_link'] ?>">(read more)</a>
  </div>
<?php endforeach ?>

<?php if ($meta['prevPage']): ?><a href="<?php echo $meta['prevPageLink'] ?>">&lt;&lt; prev</a><?php else: ?>&lt;&lt; prev<?php endif ?>
- page <?php echo $meta['page'] ?> of <?php echo $meta['totalPages'] ?> -
<?php if ($meta['nextPage']): ?><a href="<?php echo $meta['nextPageLink'] ?>">next &gt;&gt;</a><?php else: ?>next &gt;&gt;<?php endif ?>

<?php include "_footer.php" ?>
