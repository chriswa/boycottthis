<?php
  require_once "_app_init.php";
  
  list($issues, $meta) = getRecords(array(
    'tableName' => 'issues',
    'where'     => whereRecordNumberInUrl(0),
  ));
  $issue = @$issues[0];
  if (!$issue) { dieWith404("Record not found!"); }
  
  $updates = mysql_select('updates', mysql_escapef('issue = ? ORDER BY date ASC', $issue['num']));
  
?>

<?php include "_header.php" ?>

<div style="float: right;">
  <button class="boycott-button" data-issue="<?php echo $issue['num'] ?>">JOIN!!</button>
  <?php echo $issue['pledge_count'] ?> are boycotting
</div>

<h1><?php echo htmlspecialchars($issue['organization:label']) ?></h1>
<h2><?php echo htmlspecialchars($issue['title']) ?></h2>
Posted <?php echo date('F jS Y', strtotime($issue['date_posted'])) ?>
<?php if ($issue['resolved']): ?>
  &mdash; Resolved <?php echo date('F jS Y', strtotime($issue['date_resolved'])) ?>
<?php endif ?>
<br/>
<?php echo implode(', ', $issue['categories:labels']); ?><br/>

<?php echo $issue['content'] ?>

<?php $links = json_decode($issue['links'], true); ?>
<?php if ($links): ?>
  <p>
    <?php foreach ($links as $link): ?>
      <?php $title = coalesce($link['title'], $link['url']); ?>
      <a href="<?php echo htmlspecialchars($link['url']) ?>" target="_blank"><?php echo htmlspecialchars($title) ?></a><br/>
    <?php endforeach ?>
  </p>
<?php endif ?>

<?php foreach ($updates as $update): ?>
  <div style="border: 1px solid #000; padding: 5px; margin: 10px 0;">
    UPDATED <?php echo date('F jS Y', strtotime($update['date'])) ?><br/>
    <b><?php echo htmlspecialchars($update['title']) ?></b><br/>
    <?php echo $update['content'] ?>
    <?php $links = json_decode($update['links'], true); ?>
    <?php if ($links): ?>
      <p>
        <?php foreach ($links as $link): ?>
          <?php $title = coalesce($link['title'], $link['url']); ?>
          <a href="<?php echo htmlspecialchars($link['url']) ?>" target="_blank"><?php echo htmlspecialchars($title) ?></a><br/>
        <?php endforeach ?>
      </p>
    <?php endif ?>
  </div>
<?php endforeach ?>

<p>
  <button class="subscribe-button" data-issue="<?php echo $issue['num'] ?>">Subscribe to updates</button>
  <button class="unboycott-button" data-issue="<?php echo $issue['num'] ?>">Unboycott</button>
</p>

<p>
  TODO: suggest update form
</p>

<?php include "_footer.php" ?>
