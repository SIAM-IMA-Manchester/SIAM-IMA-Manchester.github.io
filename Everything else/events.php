<?php

require('common.php');
$linkedRSS[] = "events";
outHead("Events");

echo "\n<h2>Oncoming events</h2>\n\n";

$now = time();
$rssItems = rssItems("events");

$future = array();
$past = array();
foreach ($rssItems as $ts => $item) {
  if (isset($item["time"]) && $item["time"] > $now)
    $future[] = $item;
  else
    $past[] = $item;
}

if (count($future) == 0)
  echo "<p>No events are pending at the moment.</p>\n\n";
else
  foreach ($future as $item) outRSSitem($item);

if (count($past) > 0) {
  echo "\n<h2>Past events</h2>\n\n";
  foreach ($past as $item) outRSSitem($item);
}

outFoot();

?>
