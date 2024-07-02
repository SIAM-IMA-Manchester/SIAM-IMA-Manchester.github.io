<?php

require("common.php");
outHead("Welcome to Manchester SIAM-IMA Student Chapter!", "Welcome");

?>


<p>
The University of Manchester SIAM-IMA Student Chapter encourages the promotion of applied mathematics and computational science to students, especially, but not limited to, graduate students. The Chapter was set up in December 2009, 
and is run by a committee of PhD and Masters students at 
<a href="http://www.manchester.ac.uk/">The University of Manchester</a>, 
with help from our Faculty Advisors <a href="http://www.maths.manchester.ac.uk/~higham/">Nicholas J. Higham</a> and <a href="http://www.maths.manchester.ac.uk/djs/">David Silvester</a>.
</p>

<p>If you are a member of SIAM and an IMA e-Student, you can join the Chapter now by filling the <a href="http://www.maths.manchester.ac.uk/~siam/profile.php"><b>registration form</b></a>. SIAM membership is free of charge for all the students of the University of Manchester, if you are not a SIAM member yet you can apply <a target="_blank" href="https://my.siam.org/RegisterCustomer.aspx">here</a>. To become an IMA e-Student follow the instructions on the <a target="_blank" href="http://www.ima.org.uk/student/e-student.cfm.html">IMA site</a>.</p>
<p>You can also follow us on <a href="https://twitter.com/siam_manchester">Twitter</a> or join our <a href="https://www.facebook.com/groups/manchester.siam">Facebook group</a>.</p>

<h1 style="margin-bottom:20px;">
Manchester SIAM-IMA Student Chapter Conference 2023
</h1>

<p>
We are proud to have organized the Manchester SIAM-IMA Student Chapter Conference 2023 on 27 April 2023. 
See the <a href="/~siam/msiscc23/"> conference website </a> for information about the event. 
</p>

<?php

$now = time();
$oneYearAgo = $now - 60*60*24*365;
$rssItems = rssItems();

$recent = array();
$past = array();
foreach ($rssItems as $ts => $item) {
  if (isset($item["timestamp"]) && $item["timestamp"] > $oneYearAgo)
    $recent[] = $item;
  else
    $past[] = $item;
}

echo "\n<h2>News</h2>\n\n";
if (count($recent) == 0)
  echo "<p>There is no recent news.</p>\n\n";
else
  foreach ($recent as $item) outRSSitem($item);


if(count($past) > 0){
  if (isset($_GET["allNews"])) {
    echo "\n<h2 id=\"pastNews\">Past news</h2>\n\n";
    foreach ($past as $item) outRSSitem($item);
  } else {
    echo " <a type=\"button\" href=\"?allNews#pastNews\" class=\"btn btn-lg btn-default btn-block\">Load past news</a>\n\n";
  }
}

outFoot();

?>
