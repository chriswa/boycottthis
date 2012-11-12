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
