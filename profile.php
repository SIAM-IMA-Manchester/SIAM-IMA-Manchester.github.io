<?php

require("common.php");

$positions = array("Undergraduate", "Graduate PhD.", "Graduate Masters", "Staff", "Postdoc", "Other");
$positionDefault = "Graduate PhD.";
$notOk = array();
$fields = array("email", "firstname", "lastname", "position", "positionText", "institution", "school", "visible");
$fieldsMandatory = array("email" => 0, "firstname" => 0, "lastname" => 0, "institution" => 0);
if (!($userLoggedIn && empty($_POST["new_pass"]))) {
  if ($userLoggedIn) {
    $fields[] = "cur_pass";
    $fieldsMandatory["cur_pass"] = 0;
  }
  $fields[] = "new_pass";
  $fields[] = "repeat_new_pass";
  $fieldsMandatory["new_pass"] = $fieldsMandatory["repeat_new_pass"] = 0;
}
$errors = array();

$db = db_connect();

function errors() {
  global $errors;
  if ($errors) {
    echo "<div class=\"remark\">\n";
    if (count($errors) > 1) {
      echo "<p>The following errors were encountered:</p><ul>\n";
      foreach ($errors as $err)
        echo "  <li>$err</li>\n";
      echo "</ul>\n\n";
    } else
      echo "<p>The following error was encountered: $errors[0]</p>\n";
    //echo '<pre>', print_r($_POST, true), '</pre>';
    echo "</div>\n\n";
    return true;
  }
  return false;
}

function valOrEmpty($array, $key) {
  return (isset($array[$key]) ? $array[$key] : "");
}

function notOk($f) {
  return (isset($GLOBALS["notOk"][$f]) && $GLOBALS["notOk"][$f]);
}

function mandatory($f) {
  return (isset($GLOBALS["fieldsMandatory"][$f]) ? "<span class=\"mandatory\" title=\"Mandatory field\">*</span>" : "");
}

if (hasPerm() && isset($_GET["del"])) {
  $query = mysql_query("DELETE FROM members WHERE pkm='".mysql_real_escape_string($_GET["del"])."'", $db);
  forwardTo("members.php");
}

while ($submitted = isset($_POST["submit"])) {
  $editedUser = array();
  $adminEdit = (!empty($_POST["id"]) && isset($userData["pkm"]) && hasPerm());
  if ($adminEdit && $_POST["id"] == $userData["pkm"]) $adminEdit = false;
  if ($adminEdit) $fields[] = "perms";
  foreach ($fields as $f) {
    if (empty($_POST[$f])) {
      $editedUser[$f] = "";
      if (isset($fieldsMandatory[$f])) $notOk[$f] = true;
    } else
      $editedUser[$f] = stripslashes($_POST[$f]);
  }
  if (!isUomAddress($_POST["email"])) {
    $errors[] = "Only University of Manchester email addresses are allowed.";
    break;
  }
    
  if ($editedUser["position"] === $positions[count($positions)-1])
    $editedUser["position"] = $editedUser["positionText"];
  //echo "<pre style=\"background: white; border: 1px solid black; position: absolute; z-index: 37;\">", print_r($editedUser, true), "</pre>";
  if (count($notOk)) $errors[] = "Some of the data is missing. Please fill all the required fields (marked by<span class=\"mandatory\">*</span>).";
  if (!empty($editedUser["new_pass"]) || !empty($editedUser["repeat_new_pass"])) {
    $settingPassword = true;
    if ($editedUser["new_pass"] !== $editedUser["repeat_new_pass"]) {
      $errors[] = "Passwords do not match.\n\n";
      break;
    }
  } else
    $settingPassword = false;
  if ($adminEdit && !hasPerm("Admin"))
    $errors[] = "Only administrators may edit other users' data.";
  if ($errors) break;
  $editedUser["visible"] = (isset($editedUser["visible"]) && $editedUser["visible"] ? 1 : 0);
  $fields = array("email", "firstname", "lastname", "position", "institution", "school", "visible");
  if ($adminEdit) $fields[] = "perms";
  $sql = array();
  foreach ($fields as $f)
    $sql[] = "$f='".mysql_real_escape_string($editedUser[$f])."'";
  $sql = implode(", ", $sql);
  if ($settingPassword)
    $sql .= ", password='".mysql_real_escape_string($crypt = ep2crypt($editedUser["email"], $editedUser["new_pass"]))."'";
  $editTitle = "Update profile";
  $updating = true;
  if ($adminEdit)
    $sql = "update members set $sql where pkm='".mysql_real_escape_string($_POST["id"])."'";
  elseif ($userLoggedIn) {
    $sql = "update members set $sql where pkm='".mysql_real_escape_string($userData["pkm"])."'";
    if ($settingPassword)
      $sql .= " and password='".mysql_real_escape_string($userData["password"])."'";
  } else {
    $sql = "insert into members set $sql";
    $editTitle = "Join";
    $updating = false;
  }
  $query = mysql_query($sql, $db);
  $nr = mysql_affected_rows($db);
  outHead($editTitle);
  // echo "<p>Data:</p><pre style=\"text-align: left; white-space: pre-wrap;\">$sql\n\nPOST = ", print_r($_POST, true), "editedUser = ", print_r($editedUser, true), "</pre>\n\n";
  if ($updating)
    if ($nr >= 0)
      if ($adminEdit)
        echo "<p>User's data was successfully updated.</p>\n\n";
      else
        echo "<p>Your data was successfully updated.</p>\n\n";
    else
      if ($adminEdit)
        echo "<p>User's data update <b>failed</b>!</p>\n\n";
      else
        echo "<p>Your data updated <b>failed</b>!</p><pre>$sql</pre>\n\n";
  else
    if ($nr > 0) {
      $encpass = ep2crypt($editedUser["email"], $crypt);
      if (mail($editedUser["email"], "Manchester SIAM Student Chapter Registration" , "Please follow this link to finish your registration process:\nhttp://www.maths.manchester.ac.uk/~siam/activate.php?email=$editedUser[email]&code=$encpass\n\nThank you.\n"))
        echo "<p>Your registration has been successfuly processed. An account activation code has been sent to your e-mail address. Please follow the link in the mailbox to finish your registration.</p>";
      else
        echo "<p>The account was successfully created, but sending of the activation e-mail has failed.</p>";
    } else
      echo "<p>The account was <b>not</b> successfully created. Maybe you have already registered with the same e-mail address?</p>";
  outFoot();
}

if ($userLoggedIn) {
  outHead("User profile");
  echo "<form action=\"profile.php\" method=\"post\">\n\n";
  if (!errors())
    if (isset($_GET["id"]) && hasPerm("Admin")) {
      $query = mysql_query("SELECT * FROM members WHERE pkm='".mysql_real_escape_string($_GET["id"])."' LIMIT 1", $db);
      $editedUser = mysql_fetch_assoc($query);
      echo "<input type=\"hidden\" name=\"id\" value=\"$editedUser[pkm]\" />\n\n";
    } else
      $editedUser = $userData;
} else {
  outHead("Join");
  if (!errors()) $editedUser = array();
  echo "<form action=\"profile.php\" method=\"post\">\n\n";
  echo "<input type=\"hidden\" name=\"join\" value=\"1\" />\n\n";
}

$visible = isset($editedUser["visible"]) && $editedUser["visible"];
echo "<div class=\"center\"><div id=\"profile\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"profile\">
  <tr".(notOk("firstname") ? " class=\"bad\"" : "").">
    <th>First name:".mandatory("firstname")."</th>
    <td><input class=\"form-control\" type=\"text\" name=\"firstname\" value=\"".valOrEmpty($editedUser, "firstname")."\" /></td>
  </tr>
  <tr".(notOk("lastname") ? " class=\"bad\"" : "").">
    <th>Surname:".mandatory("lastname")."</th>
    <td><input class=\"form-control\" type=\"text\" name=\"lastname\" value=\"".valOrEmpty($editedUser, "lastname")."\" /></td>
  </tr>\n";
if (!empty($editedUser["tcreated"]))
  echo "  <tr>
    <th>Account created:</th>
    <td>".valOrEmpty($editedUser, "tcreated")."</td>
  </tr>\n";
echo "  <tr".(notOk("email") ? " class=\"bad\"" : "").">
    <th>E-mail:".mandatory("email")."</th>
    <td><input class=\"form-control\" type=\"text\" name=\"email\" value=\"".valOrEmpty($editedUser, "email")."\" /></td>
  </tr>
  <tr".(notOk("position") ? " class=\"bad\"" : "").">
    <th>Position:".mandatory("position")."</th>
    <td>
      <select class=\"form-control\" name=\"position\" onChange=\"positionChange(this, 'positionText');\">\n";
$userPos = ($userLoggedIn || $submitted ? valOrEmpty($editedUser, "position") : $positionDefault);
$userPosIdx = -1;
$last = count($positions)-1;
foreach ($positions as $idx => $pos) {
  echo "        <option value=\"$pos\"";
  if ($pos == $userPos || ($userPosIdx == -1 && $idx == $last)) {
    $userPosIdx = $idx;
    echo " selected=\"1\"";
  }
  echo ">$pos</option>\n";
}
echo "      </select>\n";
if (($userLoggedIn || $submitted) && $userPosIdx == $last)
  echo "      <input type=\"text\" name=\"positionText\" id=\"positionText\" value=\"".(isset($editedUser["positionText"]) ? $editedUser["positionText"] : $userPos)."\" />\n";
else
  echo "      <input type=\"text\" name=\"positionText\" id=\"positionText\" style=\"visibility: hidden;\" />\n";
echo "    </td>
  </tr>
  <tr".(notOk("institution") ? " class=\"bad\"" : "").">
    <th>Institution:".mandatory("institution")."</th>
    <td><input class=\"form-control\" type=\"text\" name=\"institution\" value=\"".valOrEmpty($editedUser, "institution")."\" /></td>
  </tr>
  <tr".(notOk("school") ? " class=\"bad\"" : "").">
    <th>School:".mandatory("school")."</th>
    <td><input class=\"form-control\" type=\"text\" name=\"school\" value=\"".valOrEmpty($editedUser, "school")."\" /></td>
  </tr>\n";
if ($userLoggedIn) {
  echo "  <tr".(notOk("cur_pass") ? " class=\"bad\"" : "").">
    <th>Current password:".mandatory("cur_pass")."</th>
    <td><input class=\"form-control\" type=\"password\" name=\"cur_pass\" /></td>
  </tr>\n";
  $np1 = "New password";
  $np2 = "Repeat new password";
} else {
  $np1 = "Password";
  $np2 = "Repeat password";
}
echo "  <tr".(notOk("new_pass") ? " class=\"bad\"" : "").">
    <th>$np1:".mandatory("new_pass")."</th>
    <td><input class=\"form-control\" type=\"password\" name=\"new_pass\" /></td>
  </tr>
  <tr".(notOk("repeat_new_pass") ? " class=\"bad\"" : "").">
    <th>$np2:".mandatory("repeat_new_pass")."</th>
    <td><input class=\"form-control\" type=\"password\" name=\"repeat_new_pass\" /></td>
  </tr>";
// echo "<tr>
//     <th colspan=\"2\" class=\"section\">Preferences</th>
//   </tr>
//   <tr".(notOk("visible") ? " class=\"bad\"" : "").">
//     <th>Publicly visible:".mandatory("visible")."</th>
//     <td>
//       <input type=\"radio\" name=\"visible\" id=\"visibleYes\" value=\"1\" ".($visible ? " checked=\"1\"" : "")." /><label for=\"visibleYes\">Yes</label>
//       <input type=\"radio\" name=\"visible\" id=\"visibleNo\" value=\"0\" ".($visible ? "" : " checked=\"1\"")." /><label for=\"visibleNo\">No</label>
//     </td>
//   </tr>\n";
if ($userLoggedIn && hasPerm("Admin")) {
    echo "  <tr>
    <th colspan=\"2\" class=\"section\">Admin options</th>
  </tr>
  <tr>
    <th>User type:</th>
    <td>
      <select name=\"perms\" id=\"perms\">\n";
  foreach ($userPerms->userPerms as $desc => $perm)
    echo "        <option value=\"$perm\"".($perm == $editedUser["perms"] ? " selected=\"1\"" : "").">$desc</option>\n";
  echo "      </select>
    </td>
  </tr>";
  }
echo "  <tr>
    <td colspan=\"2\" class=\"buttons\"><input class=\"btn btn-success btn-lg pull-right\" type=\"submit\" name=\"submit\" value=\"".($userLoggedIn ? "Update" : "Join")."\" /></td>
  </tr>
</table></div></div></form>\n";

outFoot();

?>
