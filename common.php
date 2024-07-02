<?php

date_default_timezone_set("Europe/London");
ini_set("default_charset", "utf-8");
setlocale(LC_CTYPE, "en_GB.UTF-8");

require_once("rss_data.php");
require_once("rsswriter.php");
require_once("simplePerms.php");

@session_start();

$linkedRSS = array();

$chief = "Puneet Matharu <puneet.matharu@manchester.ac.uk>";
$mainMenu = array(
/*
  array(
    "url" => "#",
    "title" => "About",
    "submenu" => array(
	array(
        "url" => "/~siam/purpose.php",
        "title" => "Purpose",
      ),
      array(
        "url" => "/~siam/contact.php",
        "title" => "Contact",
      ),
      array(
        "url" => "/~siam/committee.php",
        "title" => "Committee",
      ),
      array(
        "url" => "/~siam/events.php",
        "title" => "Events",
      ),
      array(
        "url" => "/~siam/minutes.php",
        "title" => "Minutes",
      ),
    ),
  ),
*/
  array(
	"url" => "/~siam/contact.php",
	"title" => "Contact",
    ),
 
  array(
	"url" => "/~siam/committee.php",
	"title" =>  "Committee",
    ),
 
  array(
    "url" => "https://manchestersiam.wordpress.com/",
    "title" => "Blog",
  ),
 
  array(
    "url" => "/~siam/links.php",
    "title" => "Links",
  ),
);
updateLoggedIn();

$userPerms = new SimplePerms(
  array("Member" => "0", "Admin" => "1", "Events organizer" => 2)
);

/*************************
 *** Utility functions ***
 *************************/

function hasExactPerm($perm = "Admin", $user = null) {
  global $userData;
  if (!isset($user) && isset($userData)) $user = $userData;
  return $GLOBALS["userPerms"]->hasExactPerm($user, $perm);
}

function hasPerm($perm = "Admin", $user = null) {
  global $userData;
  if (!isset($user) && isset($userData)) $user = $userData;
  return $GLOBALS["userPerms"]->hasPerm($user, $perm);
}

function ep2crypt($email, $pass) {
  return crypt(md5($pass), md5($email));
}

function mkhash($text) {
  return md5("mAIs".$text);
}

function updateLoggedIn() {
  global $userLoggedIn, $mainMenu, $userData;
  if (isset($_SESSION['SIAM_manchester_login']) && isset($_SESSION['SIAM_manchester_pass'])) {
    $userLoggedIn = login($_SESSION['SIAM_manchester_login'], $_SESSION['SIAM_manchester_pass']);
    if (!$userLoggedIn) logout();
  } else
    $userLoggedIn = false;
  if ($userLoggedIn) {

    $mainMenu[3]["url"] = "#";
    $mainMenu[3]["title"] = "<p class=\"navbar-text\"> Hello, ".$userData[firstname]."</p> Account";

    $mainMenu[3]["submenu"] = array(
      array(
        "url" => "/~siam/profile.php",
        "title" => "Profile",
      ),
      array(
        "url" => "/~siam/members.php",
        "title" => "Members",
      ),
      array(
        "url" => "/~siam/logout.php?url=".currentUrl(),
        "title" => "Logout",
      ),
    );
  } else {
    $mainMenu[3]["url"] = "#";
    $mainMenu[3]["title"] = "Account";
    $mainMenu[3]["submenu"] = array(
      array(
        "url" => "/~siam/login.php?url=".currentUrl(),
        "title" => "Login",
      ),
      array(
        "url" => "/~siam/profile.php",
        "title" => "Join",
      ),
/*      array(
        "url" => "/~siam/members.php",
        "title" => "Members",
      ),*/
    );
  }
}

function logout() {
  unset($_SESSION["SIAM_manchester_login"]);
  unset($_SESSION["SIAM_manchester_pass"]);
  forwardToGetField();
  updateLoggedIn();
}

function currentUrl($encode = true) {
  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if ($encode) $url = urlencode($url);
  return $url;
}

function forwardTo($url) {
  if (substr($url, -15) == "login_check.php") return;
  if (isset($GLOBALS["headOut"]) && $GLOBALS["headOut"]) ob_end_clean();
  header("Location: $url");
  exit;
}

function forwardToSelf($sufix = "") {
  forwardTo(basename($_SERVER["PHP_SELF"]).$sufix);
}

function forwardToGetField($field = "url") {
  //echo $_GET[$field];
  if (isset($_GET[$field]) && !empty($_GET[$field])) forwardTo($_GET[$field]);
}

function forwardToPostField($field = "url") {
  //echo $_POST[$field];
  if (isset($_POST[$field]) && !empty($_POST[$field])) forwardTo($_POST[$field]);
}

function get2hidden($fields = "url") {
  $res = "";
  foreach ((array)$fields as $f)
    if (isset($_GET[$f]) && !empty($_GET[$f]))
      $res .= "<input type=\"hidden\" name=\"$f\" value=\"".htmlspecialchars($_GET[$f])."\">\n";
  return $res;
}

/************************************************************
 *** Old DB functions (compatibility with the older code) ***
 ************************************************************/

function db_connect() {
  $sql = mysql_connect("localhost:3306", "siam", "SiAmMySqL");

  if (!$sql) return 0;
  if (!mysql_select_db("siam_chapter", $sql)) {
    mysql_close($sql);
    return 0;
  }
  return $sql;
}

function login($email, $pass) {
  global $userData;
  $db = db_connect();
  $query = mysql_query("SELECT * FROM members WHERE email='".mysql_real_escape_string($email)."' and password='".mysql_real_escape_string($pass)."' LIMIT 1", $db);
  $userData = mysql_fetch_assoc($query);
  if ($userData) {
    $userData["loggedIn"] = true;
    if (!isset($userData["perms"])) $userData["perms"] = 0;
    return true;
  }
  $userData = array(
    "loggedIn" => false,
    "perms" => 0,
  );
  return false;
}

/************************************
 *** New DB functions (PDO-based) ***
 ************************************/

function dbhConnect() {
  global $dbh;
  if (isset($dbh)) return $dbh;
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=siam_chapter', "siam", "SiAmMySqL", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    return $dbh;
  } catch (PDOException $e) {
    print "<p>Error connecting to the database: " . $e->getMessage() . "</p>";
    die();
  }
}

function dbQuery($query, $values = null, $breakOnBindErrors = false) {
  $dbh = dbhConnect();
  try {
    $sth = $dbh->prepare($query);
  } catch (PDOException $e) {
    $sth = false;
  }
  if (!$sth) return false;
  if (is_array($values))
    foreach ($values as $key => &$val)
      if (!$sth->bindParam(':'.$key, $val) && $breakOnBindErrors) {
        print "<p>Error in the database request.</p>";
        die();
      }
  $sth->execute();
  return $sth;
}

function dbTableExists($table) {
  $sth = dbQuery("select 17 from $table limit 0,1");
  return ($sth ? $sth->rowCount() > 0 : false);
}

function dbErrorInfo($info, $code, $subcode = null) {
  if (gettype($info) === "object") $info = $info->errorInfo();
  return ($info[0] === $code && (!isset($subcode) || $info[1] === (int)$subcode));
}

function dbErrorMessage($info) {
  if (gettype($info) === "object") $info = $info->errorInfo();
  return $info[2];
}

/*******************************
 *** Main document functions ***
 *******************************/

function outHead($title = "", $shortTitle = null, $extraHead = "") {

  if (!isset($shortTitle)) $shortTitle = $title;
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600" rel="stylesheet" type="text/css">

  <title>Manchester Student SIAM Chapter<?php if (!empty($shortTitle)) echo ": $shortTitle"; ?></title>
    <!-- bootstrap CSS -->
    <link rel="stylesheet" href="/~siam/css/bootstrap.css">

  <link rel="stylesheet" href="/~siam/css/style.css" >
  <script type="text/javascript" src="/~siam/script.js?reload"></script>

<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="/~siam/css/bootstrap-image-gallery.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<?php
  if (!empty($extraHead)) echo "\n$extraHead\n";
?>
</head>

<body link="purple" vlink="purple">


  <?php

menu($GLOBALS["mainMenu"]);

?>

<div class="container">
<div id="content">
<?php
  if (!empty($title))
    echo "<h1>$title</h1>\n";
}

function outFoot() {
  global $rssData;

?>
</div>
</div>

    
<div id="footer" class="container-fluid">

<!--
/*<div class="container"><div style="float:left;"><b>RSS:</b><ol>
 <li><a href="/~siam/rss.php"><b>All news</b></a></li>

   <?php

foreach ($rssData as $cat => $data)
  echo "  <li><a href=\"/~siam/rss.php?cat=$cat\">".$data["title"]."</a></li>\n";
  ?>
  </ol> </div>*/
 -->

<div style="float:right;"> <a href="http://www.siam.org/">SIAM</a> and <a href="http://www.maths.manchester.ac.uk/">The School of Mathematics</a> at <a href="http://www.manchester.ac.uk/">The University of Manchester</a></div>
</div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- bootstrap JS-->
    <script src="/~siam/js/bootstrap.js"></script>

    <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script src="/~siam/js/bootstrap-image-gallery.min.js"></script>

</body>
</html>
<?php
  exit(0);
}

function outHeadSNSCC12($title = "") {
  outHead("SIAM National Student Chapter Conference 2012<br />(SNSCC12)", "SNSCC12".(empty($title) ? "" : " ".$title));
  if (!empty($title)) echo "<h2 style=\"text-align: center;\">".ucwords($title)."</h2>\n\n";
  echo "<p style=\"text-align: center;\">18<sup>th</sup> May 2012.<br />\nAlan Turing Building<br />\nUniversity of Manchester</p>\n\n";
}

function outFootSNSCC12() {
  echo "<p><a href=\"snscc12.php\">&#x226a; Back to the conference main page</a></p>\n\n";
  outFoot();
}

function mkremark($text, $title = "", $class = "") {
  $res = "<div class=\"remark".(empty($class) ? "" : " ".$class)."\">";
  if (!empty($title)) $res .= "<div class=\"title\">$title</div>";
  $res .= "<div class=\"content\">$text</div></div>\n\n";
  return $res;
}

function remark($text, $title = "", $class = "") {
  echo mkremark($text, $title, $class);
}

function mkerror($text) {
  return mkremark($text, "Error", "error");
}

function error($text) {
  echo mkerror($text);
}

function maskEmail($email, $desc = "email") {
  if (preg_match('#^([^@]+)@([^@]+)$#', $email, $reOut))
    return "&nbsp;<span onClick=\"return liame(this, '$reOut[2]', '$reOut[1]');\" class=\"email\" title=\"Click to expand\">$desc</span>";
  else
    return "$desc: <tt>$email</tt>";
}

function email($email, $desc = "") {
  echo maskEmail($email, $desc);
}

function emailFromText($text) {
  //echo "<pre>".htmlspecialchars($text)."</pre>";
  if (preg_match('#^([^<]+)<([^>]+)>\s*$#', $text, $reOut))
    echo maskEmail(trim($reOut[2]), trim($reOut[1]));
  else
    echo maskEmail($text);
}

/*function menu($menu, $class = "menu", $level = 0) {
  echo "<div class=\"$class\">";
  $first = true;
  foreach ($menu as $item) {
    if (!$level)
      if ($first) $first = false; else echo " | ";
    echo "<div".(isset($item["url"]) ? " onClick=\"return menuClick(event, '$item[url]');\"" : " class=\"nourl\"").">$item[title]";
    if (isset($item["submenu"])) {
      menu($item["submenu"], "submenu", $level+1);
    }
    echo "</div>";
  }
  echo "</div>";
}*/

function menu($menu){
  ?>
  <nav class="navbar navbar-default">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="/~siam/"><img src="/~siam/images/logo17big.png" style="height:80px"></a>
</div>



  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
  <?php

  foreach ($menu as $item) {
    if (isset($item["submenu"])) {

      print "<li class=\"dropdown\"><a href=\"";
      print $item["url"];
      print "\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">";
      print $item[title];
      print "<span class=\"caret\"></span></a>";


      print "<ul class=\"dropdown-menu\">";

      foreach($item["submenu"] as $subitem){
        print "<li><a href=\"";
        print $subitem["url"];
        print "\">";
        print $subitem["title"];
        print "</a></li>";
      }


      print "</ul></li>";

    }else{ //It has no submenu, just a plain old link

      print "<li><a href=\"";
      print $item["url"];
      print "\">";
      print $item["title"];
      print "</a></li>";

    }

  }
  ?>

  <p class="navbar-text"></p>

  </ul>
    </div></div></nav>

  <?php
}


/**************************
 *** Auxilary functions ***
 **************************/

function rssItem($cat, $ts) {
  global $rssData;
  if (isset($rssData[$cat]))
    foreach ($rssData[$cat]["items"] as $item)
      if (isset($item["timestamp"]) && $item["timestamp"] == $ts)
        return $item;
  return null;
}

function rssItems($categories = null, $ts = null) {
  global $rssData;
  if (!isset($categories)) $categories = array_keys($rssData);
  if (!is_array($categories)) $categories = array($categories);
  $timestamp = 0;
  $rssItems = array();
  $idx = 0;
  foreach ($categories as $cat)
    $idx += count($rssData[$cat]["items"]);
  $fmt = ($idx ? "%0".(floor(log($idx, 10))+1)."d" : "%d");
  foreach ($categories as $cat)
    foreach ($rssData[$cat]["items"] as $item) {
      if (isset($ts) && (!isset($item["timestamp"]) || $ts != $item["timestamp"])) continue;
      if (isset($item["alias"])) {
        list($icat, $its) = $item["alias"];
        $aitem = rssItem($icat, $its);
        foreach ($aitem as $key => $val)
        if (!isset($item[$key])) $item[$key] = $val;
        if (!isset($item)) continue;
      }
      $its = (isset($item["timestamp"]) ? $item["timestamp"] : 0);
      if (!isset($item["permalink"])) $item["permalink"] = "cat=$cat&ts=$its";
      $rssItems[$its.".".sprintf($fmt, --$idx)] = $item;
    }
  krsort($rssItems);
  return $rssItems;
}

function outRSS($rssItems = null, $title = null) {
  $ts = 0;
  foreach ($rssItems as $key => $item)
    if (isset($item["timestamp"]) && $ts < $item["timestamp"])
      $ts = $item["timestamp"];
  $rss = new RSSWriter(
    'http://www.maths.manchester.ac.uk/~siam/',
    'Manchester Student SIAM Chapter'.(empty($title) ? "" : ": $title"),
    'Manchester Student SIAM Chapter RSS feed',
    array(
      'dc:publisher' => 'Manchester Student SIAM Chapter',
      'dc:date' => date('Y-m-d\TH:i:s+01:00', $ts ? $ts : time())
    )
  );
  if ($ts)
    foreach ($rssItems as $ts => $item)
      if (isset($item["timestamp"]) && $item["timestamp"])
        $rss->addItem(
          'http://www.maths.manchester.ac.uk/~siam/rss.php?'.$item['permalink'],
          $item['title'],
          array(
            'description' => $item['text'],
            'dc:date' => date('Y-m-d\TH:i:s+01:00', $item['timestamp'])
          )
        );
  $rss->serialize();
}

/*function outRSSitem($item, $class = "") {
  $class = "item".(empty($class) ? "" : " $class");
?>
<table border="0" cellspacing="0" cellpadding="2" class="<?php echo $class; ?> box">
  <tr valign="middle">
    <td align="left" class="<?php echo $class; ?> title">
      <div class="<?php echo $class; ?> time"><?php echo (isset($item["timestamp"]) && $item["timestamp"] ? date("H:i, d.m.Y.", $item["timestamp"]) : ""); ?></div>
      <?php echo $item["title"]; ?>
    </td>
  </tr>
  <tr valign="top">
    <td align="justify" class="<?php echo $class; ?> body" colspan="2" height="76">
<?php

  echo preg_replace('/^/', "      ", $item["text"]);

?>
    </td>
  </tr>
</table>
<?php
}*/

function outRSSitem($item) {
?>
<div class="panel panel-warning">
<div class="panel-heading">


      <h3 class="panel-title"><?php echo $item["title"]; ?></h3>
      <p class="panel-timestamp" title="<?php echo (isset($item["timestamp"]) && $item["timestamp"] ? date("H:i, d.m.Y.", $item["timestamp"]) : ""); ?>">

      <?php echo (isset($item["timestamp"]) && $item["timestamp"] ?  humanTiming($item["timestamp"])." ago" : ""); ?>

      </p>
    </div>
  <div class="panel-body">
<?php

  echo preg_replace('/^/', "      ", $item["text"]);

?>
</div></div>
<?php

}

/* Credit: arnorhs, taken from stackoverflow.com*/
function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}


/*function outRSSitemsAsHTML($rssItems = null, $class = "") {
  foreach ($rssItems as $ts => $item) outRSSitem($item, $class);
  echo "\n";
}

function outRSSasHTML($categories = null, $class = "") {
  $rssItems = rssItems($categories);
  outRSSitemsAsHTML($rssItems, $class);
}

function outRSSasHTMLnewOnly($categories = null, $class = "") {
  $rssItems = rssItems($categories);
  $now = time();
  foreach ($rssItems as $ts => $item)
    if (!isset($item["time"]) || $item["time"] > $now) outRSSitem($item, $class);
  echo "\n";
}
*/

function size2str($size) {
  $sizes = array("B", "KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB");
  $idx = 0;
  $last = count($sizes) - 1;
  while ($size >= 1024 && $idx < $last) {
    $size /= 1024;
    $idx++;
  }
  return sprintf("%.2f%s", $size, $sizes[$idx]);
}

function dwldLink($fname, $desc, $type = "") {
  $url = $fname;
  $fname = preg_replace("/\?.*$/", "", $url);
  if (empty($type)) {
    if (preg_match("/\.([^.]+)$/", $fname, $reOut))
      $type = strtoupper($reOut[1]).", ";
    else
      $type = "";
  } else
    $type .= ", ";
  return sprintf("<a href=\"%s\">%s</a> (%s%s; last change: %s)", $url, $desc, $type, size2str(filesize($fname)), date("j.n.Y., H:i",filemtime($fname)));
}

function chief() {
  global $chief;
  $chief = preg_replace_callback(
    '#<([^>]+@[^>]+)>#',
    create_function(
      '$matches',
      'return (count($matches) > 1 ? maskEmail($matches[1]) : "");'
    ),
    $chief);
  return $chief;
}

function isUomAddress($email) {
  $domains = array("manchester.ac.uk", "man.ac.uk");
  foreach ($domains as $domain) {
    if (strlen($email) < strlen($domain))
      continue;

    $pos = strpos($email, $domain, strlen($email) - strlen($domain));

    if ($pos === false)
      continue;

    if ($pos == 0 || $email[(int) $pos - 1] == "@" || $email[(int) $pos - 1] == ".")
      return true;
  }

  return false;
}

?>
