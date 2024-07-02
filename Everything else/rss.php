<?php

require_once("common.php");

if (isset($_GET["cat"])) {
  $cat = $_GET["cat"];
  if (isset($_GET["ts"])) {
    $ts = $_GET["ts"];
    outHead();
    $rssItems = (isset($rssData[$cat]) ? rssItems($categories, $ts) : array());
    if (count($rssItems)) {
      //echo "<div style=\"height: 47px;\"></div>\n\n";
      echo "<h2>".$rssData[$cat]["title"]."</h2>\n\n";
      outRSSitemsAsHTML($rssItems);
    } else
      echo "<h2>Item not found</h2>\n\n<p>No item with the given category and timestamp was found in the database.</p>\n\n";
    outFoot();
  }
  if (!is_array($cat)) $cat = array($cat);
  outRSS(rssItems($cat), $rssData[$cat[0]]["title"]);
} else
  outRSS(rssItems($cat));

?>
