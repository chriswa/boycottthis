<?php
  function outputNavLink($title, $url) {
    $isActive = (strpos($_SERVER['SCRIPT_NAME'], $url) !== false);
    if ($isActive) {
      echo "<b>$title</b>";
    }
    else {
      echo "<a href=\"$url\">$title</a>";
    }
  }
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="site.js"></script>

<?php include "_overlays.php"; ?>

<style>
  body { margin: 0; }
  #content { margin: 10px; }
  #navbar { background-color: #000; color: #fff; padding: 3px 8px; }
  #navbar a { color: #ccf; font-weight: bold; text-decoration: none; }
</style>
<div id="navbar">
    <?php outputNavLink("Home/search", "index.php"); ?>
  | <?php outputNavLink("Boycotts",    "boycotts.php"); ?>
  | <?php outputNavLink("About Us",    "about.php"); ?>
  | <?php outputNavLink("News",        "news.php"); ?>
  | <?php outputNavLink("Contact",     "contact.php"); ?>
</div>
<div id="content">
