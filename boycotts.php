<?php
  require_once "_app_init.php";
  
  $sortOption = coalesce(@$_REQUEST['sort'], 'biggest');
  $orderBy = array_value(array(
    'biggest' => 'pledge_count DESC',
    'newest'  => 'date_posted DESC',
  ), $sortOption);
  
  $where = 'TRUE';
  $query = @$_REQUEST['query'];
  if ($query) {
    $escapedQuery = mysql_escape($query);
    
    // find matching organizations
    $organizations = mysql_select('organizations', "`title` LIKE '%$escapedQuery%'");
    
    //
    $where = 'FALSE';
    $where .= " OR organization IN (" . mysql_escapeCSV(array_pluck($organizations, 'num')) . ")";
    foreach (array('title', 'keywords', 'summary', 'content') as $fieldName) {
      $where .= " OR `$fieldName` LIKE '%$escapedQuery%'";
    }
  }
  
  list($issues, $meta) = getRecords(array(
    'tableName' => 'issues',
    'perPage'   => 10,
    'orderBy'   => $orderBy,
    'where'     => "hidden = 0 AND ($where)",
  ));
  
?>

<?php include "_header.php" ?>

<div style="float: right;">
  <?php if ($sortOption === 'biggest'): ?><b>biggest</b><?php else: ?><a href="?sort=biggest">biggest</a><?php endif ?>
  |
  <?php if ($sortOption === 'newest'): ?><b>newest</b><?php else: ?><a href="?sort=newest">newest</a><?php endif ?>
</div>

<div style="margin: 50px;">
  <form action="boycotts.php" method="POST">
    Search: <input type="text" name="query" value="<?php echo htmlspecialchars(@$_REQUEST['query']) ?>" />
    <input type="submit" value="Go" />
  </form>
</div>

<?php if ($issues): ?>
  <?php foreach ($issues as $issue): ?>
    <div>
      <a href="<?php echo $issue['_link'] ?>">
        <?php echo $issue['pledge_count'] ?> people are boycotting <?php echo htmlspecialchars($issue['organization:label']) ?>
      </a><br/>
      <?php echo htmlspecialchars($issue['summary']) ?>
      <a href="<?php echo $issue['_link'] ?>">(read more)</a>
    </div>
    <br/>
  <?php endforeach ?>
<?php else: ?>
  <p>Sorry, no results were found. Please try another search.</p>
<?php endif ?>

<?php if ($meta['totalPages'] > 1): ?>
  <?php if ($meta['prevPage']): ?><a href="<?php echo $meta['prevPageLink'] ?>">&lt;&lt; prev</a><?php else: ?>&lt;&lt; prev<?php endif ?>
  - page <?php echo $meta['page'] ?> of <?php echo $meta['totalPages'] ?> -
  <?php if ($meta['nextPage']): ?><a href="<?php echo $meta['nextPageLink'] ?>">next &gt;&gt;</a><?php else: ?>next &gt;&gt;<?php endif ?>
<?php endif ?>

<?php include "_footer.php" ?>
