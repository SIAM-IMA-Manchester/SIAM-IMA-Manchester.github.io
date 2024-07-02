<?php

require("common.php");

outHead("Members");

if (hasPerm()) {
  $isAdmin = true;
  $where = "";
} else {
  $isAdmin = false;
  $where = " WHERE visible";
  echo "<p>This is the filtered list of the members of The Manchester SIAM Student Chapter that wanted their name publicly shown. Many members are hidden, as that is the default setting.</p>\n\n";
}

$db = db_connect();
$query = mysql_query("SELECT * FROM members$where ORDER BY lastname COLLATE utf8_unicode_ci", $db);

echo "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bordered members\">\n\n";
echo "  <tr>\n    <th>First name</th>\n    <th>Last name</th>\n    <th>Position</th>\n    <th>Institution</th>\n    <th>School</th>\n";
if ($isAdmin) {
  echo "    <th>Options</th>\n";
  $emails = array();
}
echo "  </tr>\n\n";
while ($user = mysql_fetch_assoc($query)) {
  echo "  <tr".($isAdmin && hasPerm("Admin", $user) ? " class=\"admin\"" : "").">\n    <td>$user[firstname]</td>\n    <td>$user[lastname]</td>\n    <td>$user[position]</td>\n    <td>$user[institution]</td>\n    <td>$user[school]</td>\n";
  if ($isAdmin) {
    echo "    <td class=\"opts\"><a href=\"profile.php?id=$user[pkm]\" title=\"Edit user's data\">E</a> <a href=\"profile.php?del=$user[pkm]\" title=\"Delete user\" onClick=\"return confirm('Are you sure you want to delete $user[firstname] $user[lastname]?');\">D</a> <span title=\"Show/hide user's e-mail from the list below\" class=\"emailOpt\" onClick=\"return emailOnOff(this, ".(empty($user["pkm"]) ? 0 : $user["pkm"]).");\">@</span></td>\n";
    $fn = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $user["firstname"]);
    $ln = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $user["lastname"]);
    $em = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $user["email"]);
    $emails["$ln, $fn, $em, $user[pkm]"] = array($user["pkm"], "$fn $ln &lt;$em&gt;");
  }
  echo "  </tr>\n\n";
}
echo "</table>\n\n";

if ($isAdmin) {
  ksort($emails);
  echo "<div id=\"emailsBlock\">\n\n<h2>E-mails</h2>\n\n<div id=\"emails\">";
  $first = true;
  foreach ($emails as $key => $val) {
    list($idx, $email) = $val;
    if ($first) $first = false; else echo "<span id=\"comma$idx\">, </span>";
    echo "<span id=\"email$idx\">$email</span>";
  }
  echo "</div>\n\n</div>\n\n";
}

outFoot();

?>
