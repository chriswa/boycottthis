<?php
  require_once "_app_init.php";
  
  $newestIssues = mysql_select('issues', "hidden = 0 ORDER BY createdDate DESC LIMIT 2");
  dbx_decorate($newestIssues);
  
  $biggestIssues = mysql_select('issues', "hidden = 0 ORDER BY pledge_count DESC LIMIT 1");
  dbx_decorate($biggestIssues);
  
  $announcements = mysql_select('announcements', "hidden = 0 ORDER BY date DESC LIMIT 1");
  dbx_decorate($announcements);
  
?>

<?php include "_header.php" ?>

<div style="margin: 50px;">
  <form action="boycotts.php" method="POST">
    Search: <input type="text" name="query" value="<?php echo htmlspecialchars(@$_REQUEST['query']) ?>" />
    <input type="submit" value="Go" />
  </form>
</div>

<style>
  .column-third { width: 30%; margin: 10px; float: left; border: 1px solid #000; padding: 5px; }
</style>

<div class="column-third">
  New Boycotts<br/>
  <?php foreach ($newestIssues as $issue): ?>
    <div>
      <?php echo pretty_relative_time($issue['date_posted']) ?><br/>
      <a href="<?php echo $issue['_link'] ?>"><?php echo htmlspecialchars($issue['title']) ?></a>
    </div>
  <?php endforeach ?>
  <a href="boycotts.php?sort=newest">see all boycotts</a>
</div>

<div class="column-third">
  <?php foreach ($newestIssues as $issue): ?>
    <div>
      <a href="<?php echo $issue['_link'] ?>">
        <?php echo $issue['pledge_count'] ?> people are boycotting <?php echo htmlspecialchars($issue['organization:label']) ?>
      </a><br/>
      <?php echo htmlspecialchars($issue['summary']) ?>
      <a href="<?php echo $issue['_link'] ?>">(read more)</a>
    </div>
  <?php endforeach ?>
  <a href="boycotts.php?sort=biggest">see biggest boycotts</a>
</div>

<div class="column-third">
  News<br/>
  <?php foreach ($announcements as $announcement): ?>
    <div>
      <a href="<?php echo $announcement['_link'] ?>"><?php echo htmlspecialchars($announcement['title']) ?></a><br/>
      <?php echo pretty_relative_time($announcement['date']) ?>
      <?php echo htmlspecialchars($announcement['summary']) ?>
      <a href="<?php echo $announcement['_link'] ?>">(read more)</a>
    </div>
  <?php endforeach ?>
  <a href="news.php">see all news</a>
</div>

<?php include "_footer.php" ?>
