<?php

require_once("common.php");
require_once("vsTPL.php");

$defaultConferenceData = array(
  "code" => "",
  "url" => "http://www.maths.manchester.ac.uk/~siam/???.php",
  "mailFrom" => "Samuel Relton <samuel.relton@postgrad.manchester.ac.uk>",
  "registrationDeadline" => mktime(00, 00, 00, 7, 1, 1899), // hour, minute, second, MONTH, DAY, year
  "header" => "",
  "title" => "",
  "shortTitle" => "",
  "needConfirmation" => true,
  "email" => "",
  "mailSubjectPrefix" => "[SNSCC]",
  "regFields" => array(
    // Fields used in the code: title, firstname, lastname, email
    // description => array(type, name, [htmlParams, [sqlParams, [mandatory, [display in list]]]])
    // or
    // description => name   # as
    // description => array('text', name, array(), 'varchar(127) default ""', type == "text", type in array("text", "file*"))
    "Title" => array("selectTitle", "title", array(), null, true),
    "Name" => "firstname",
    "Last name" => "lastname",
    "E-mail" => "email",
    "Department/Institution" => "institution",
    "Request travel funding" => array("checkbox", "needFund"),
    "Remarks" => array("textarea", "remarks"),
    //"Accomodation" => array("select", "accomodation", array("other" => "I'll deal with it myself", "hotel" => "Hotel", "tent" => "Tent", "trailer" => "Trailer")),
    "Abstract" => array("files", "abstract"),
  ),
  "sqlCreateTableExtras" => "constraint unique index ui_email (email)",
  "uniqueFields" => "e-mail", // description, written to users who fail the uniqueness test
  "confirmationEmail" => "Dear {title} {lastname},\n\nwe have received your registration for the {conf}{title}. Please confirm it by clicking on the following link:\n{conf}{url}?confirm={code}\n\nWe are looking forward to seeing you!",
  "noconfirmationEmail" => "Dear {title} {lastname},\n\nwe have received your registration for the {conf}{title}.\n\nWe are looking forward to seeing you!",
);
$type2sql = array(
  'checkbox' => 'boolean default false',
  'const' => '', // used for constants, i.e., if you want an empty title
  'file' => 'varchar(255) default null',
  'files' => 'varchar(4352) default null', // 17 names of 255 chars, separated by '/' (saved as file-id-index)
  'text' => 'varchar(127) default ""',
  'textarea' => 'varchar(1719) default ""',
);
$type2html = array(
  // html
  // or
  // array(html, parameterDefaults)
  'checkbox' => '<input name="%1$s" id="fld_%1$s" type="checkbox"%2$s />',
  'const' => '%1$s',
  'file' => '<input name="%1$s" id="fld_%1$s" type="file"%2$s />',
  'files' => '<div id="fld_%1$s"><div id="fld_first_%1$s" class="file"><input name="%1$s[]" type="file"%2$s /> <img src="images/fileRemove.png" align="top" style="visibility: hidden;" onClick="return filesRemove(this);" title="Remove file" /> <img src="images/fileAdd.png" align="top" onClick="return filesAdd(\'%1$s\', this);" title="Add file" /></div></div><script type="text/javascript">makeClone("%1$s", "fld_first_%1$s"); // --></script><noscript>[Error: JavaScript is disabled]</noscript>', // srediti
  'select' => '<select name="%1$s" id="fld_%1$s">%2$s</select>',
  'selectTitle' => array('<select name="%1$s" id="fld_%1$s">%2$s</select>', array('' => 'Select', 'Dr' => 'Dr', 'Professor' => 'Professor', 'Mr' => 'Mr', 'Miss' => 'Miss', 'Mrs' => 'Mrs', 'Ms' => 'Ms')),
  'text' => array('<input name="%1$s" id="fld_%1$s" type="text"%2$s />', array('size' => 37)),
  'textarea' => array('<textarea name="%1$s" id="fld_%1$s"%2$s></textarea>', array('rows' => 5, 'cols' => '41')),
);

if (!isset($conferenceData))
  $conferenceData = $defaultConferenceData;
else
  foreach ($defaultConferenceData as $key => $value)
    if (!isset($conferenceData[$key]))
      $conferenceData[$key] = $value;
$processRegistrationErrors = array();
$inRegistration = false;
$registrationDone = false;
$filesToUpload = array();
$uploadsDir = "files/uploads/".$conferenceData["code"];
if (!is_dir($uploadsDir))
  if (!mkdir($uploadsDir, 0755, true))
    die("Cannot create uploads directory: $uploadsDir");

processRegistration();
handleConfirm();

function outConfPages() {
  if (isset($_GET["list"])) {
    outHeadConf("Attendants");
    displayReglist();
    outFootConf();
  }
  if (isset($_GET["id"])) {
    if (isset($_GET["att"]))
      downloadAttachment($_GET["id"], $_GET["att"]);
    outHeadConf("Attendant info");
    displayReg($_GET["id"]);
    outFootConf();
  }
  if (isset($_GET["zip"]))
    if (empty($_GET["zip"]))
      downloadAllAttachments();
    else
      downloadAttachments($_GET["zip"]);
}

function outHeadConf($title = "") {
  global $conferenceData, $processRegistrationErrors;
  $st = $conferenceData["shortTitle"];
  if (!empty($title)) $st = (empty($st) ? "" : " ").$title;
  outHead($conferenceData["title"], $st);
  if (!empty($title)) echo "<h2 style=\"text-align: center;\">".ucwords($title)."</h2>\n\n";
  if (!empty($conferenceData["header"])) echo "<p style=\"text-align: center;\">", $conferenceData["header"], "</p>\n\n";
  if ($GLOBALS["registrationDone"])
    remark("Registration data was successfully saved.<br /><b>Please confirm your registration</b> by clicking the link that was sent to you in a confirmation e-mail.", "Success", "success");
  elseif ($processRegistrationErrors) {
    error("The following errors have occured:<ul>\n  <li>".implode("</li>\n  <li>", $processRegistrationErrors)."</li>\n</ul>".($GLOBALS["inRegistration"] ? "Please, go <a href=\"#\" onClick=\"history.back(); return false;\">back</a> and edit your data." : ""));
    $GLOBALS["registrationDone"] = true;
  }
}

function outFootConf() {
  outFoot();
}

function dbConnectConf() {
  $dbh = dbhConnect();
  return $dbh;
}

function confTable($type) {
  global $conferenceData;
  return "conf_$conferenceData[code]_$type";
}

function dbCreateConfTable($type) {
  global $conferenceData;
  $sql =
    "create table `".confTable($type)."` (".
    "  `id` integer auto_increment primary key,\n".
    "  `siam_id` integer default null";
  foreach ($conferenceData["regFields"] as $key => $field) {
    $sqlType = fieldSQLtype($field);
    if (!empty($sqlType))
      $sql .= ",\n  `".fieldName($field)."` $sqlType";
  }
  $sql .=
    ",\n  `confirmed` boolean default ".($conferenceData["needConfirmation"] ? "false" : "true").
    ",\n  `code` integer not null".
    ",\n  `lastchange` timestamp";
  if (!empty($conferenceData["sqlCreateTableExtras"]))
    $sql .= ",\n  ".$conferenceData["sqlCreateTableExtras"];
  $sql .=
    "\n) default charset=utf8 collate=utf8_bin";
  $sth = dbQuery($sql);
  //echo "<pre>SQL:\n$sql\n\nError [", $sth->errorCode(), "]: ", print_r($sth->errorInfo(), true), "</pre>\n"; exit;
  return $sth;
}

function field($field) {
  if (is_array($field)) return $field;
  return array("input", $field);
}

function fieldValue($field, $idx, $default = null) {
  if (is_array($field))
    return (count($field) > $idx ? $field[$idx] : $default);
  if ($idx == 1) return $field;
  return $default;
}

function fieldName($field) {
  return fieldValue($field, 1);
}

function fieldType($field) {
  return fieldValue($field, 0, "text");
}

function fieldBasicType($field) {
  $type = fieldType($field);
  if (substr($type, 0, 5) == "const") return "const";
  if (substr($type, 0, 4) == "file") return "file";
  if (substr($type, 0, 6) == "select") return "select";
  return $type;
}

function fieldMandatory($field) {
  return fieldValue($field, 4, fieldType($field) == "text");
}

function fieldDisplayInList($field) {
  $ft = fieldType($field);
  return fieldValue($field, 5, $ft == "text" || substr($ft, 0, 4) == "file");
}

function fieldSQLtype($field) {
  global $type2sql;
  if (fieldBasicType($field) === "const") return "";
  $res = fieldValue($field, 3);
  if (empty($res)) {
    $type = fieldType($field);
    $res = (isset($type2sql[$type]) ? $type2sql[$type] : "");
    if (empty($res)) {
      /* Construct type for a select-type field */
      if (substr($type, 0, 6) === "select") {
        $def = $GLOBALS["type2html"][$type];
        if (is_array($def) && count($def) > 1) {
          $notAssoc = true;
          foreach ($def[1] as $key => $val) {
            $len = strlen($key);
            if ($notAssoc) {
              if (strspn($key, "0123456789") < $len) {
                $notAssoc = false;
                $maxLen = $len;
              }
            } else
              if ($maxLen < $len) $maxLen = $len;
          }
          $nullDef = (fieldMandatory($field) ? "not null" : "default null");
          $res = $type2sql[$type] = ($notAssoc ?
            "integer $nullDef" :
            "varchar($maxLen) $nullDef"
          );
        }
      } else
      /* Construct type for a files-type field */
      if (substr($type, 0, 5) === "files")
          $res = $type2sql[$type] = $type2sql["files"];
      /* Construct type for a file-type field */
      if (substr($type, 0, 4) === "file")
          $res = $type2sql[$type] = $type2sql["file"];
    }
  }
  return $res;
}

function htmlTemplate($field) {
  if (!is_array($field)) $field = array("text", $field);
  $name = fieldName($field);
  $type = fieldType($field);
  $params = fieldValue($field, 2, array());
  $tpl = $GLOBALS["type2html"][$type];
  if (!is_array($params)) $params = array();
  if (is_array($tpl) && count($tpl) > 1) {
    foreach ($tpl[1] as $key => $val)
      if (!isset($params[$key])) $params[$key] = $val;
    $tpl = $tpl[0];
  }
  $args = "";
  if (substr($type, 0, 5) === "const")
    return (is_array($params) ? implode("", $params) : $params);
  if (substr($type, 0, 6) === "select")
    foreach ($params as $key => $val)
      $args .= "<option value=\"".htmlspecialchars($key)."\">".htmlspecialchars($val)."</option>";
  else
    foreach ($params as $key => $val)
      $args .= " $key=\"".htmlspecialchars($val)."\"";
  return sprintf($tpl, $name, $args);
}

function row2data($row) {
  global $conferenceData;
  $res = array();
  foreach ($conferenceData["regFields"] as $key => $field) {
    $name = fieldName($field);
    $res[$name] = (isset($row[$name]) ? $row[$name] : "");
  }
  return $row;
}

function processRegistrationError($error) {
  $GLOBALS["processRegistrationErrors"][] = $error;
  return false;
}

function registrationForm() {
  global $conferenceData, $userLoggedIn, $userData;
  if ($GLOBALS["registrationDone"]) return;
  $mandatoryField = "<span class=\"mandatory\" title=\"Mandatory field\">&raquo;</span>";
  $x = rand(3,11);
  $y = rand(3,11);
  switch (rand(1,3)) {
    case 1: $t = "$x + $y"; $r = $x + $y; break;
    case 2: if ($x < $y) list($x,$y) = array($y,$x); $t = "$x &ndash; $y"; $r = $x - $y; break;
    case 3: $t = "$x &middot; $y"; $r = $x * $y; break;
  }
  echo "<div class=\"registration\">\n\n";
  echo "<div class=\"title\">Registration</div>\n\n";
  //echo "<pre>", print_r($_POST, true), "</pre>\n\n";
  if (isset($conferenceData["registrationDeadline"]) && $conferenceData["registrationDeadline"] < time())
    remark("Registration deadline has passed.");
  else {
    echo "<div class=\"legend\">Fields marked by &ldquo;<span class=\"mandatory\" style=\"padding: 0;\" title=\"Mandatory field\">&raquo;</span>&rdquo; are mandatory.</div>\n\n";
    echo "<form method=\"post\" enctype=\"multipart/form-data\">\n\n";
    echo "<input type=\"hidden\" name=\"captchaCheck\" value=\"", mkhash($r), "\" />\n";
    if ($userLoggedIn) {
      echo "<input type=\"hidden\" name=\"siam_id\" value=\"$userData[pkm]\" />\n\n";
      remark("If this is not your data, please <a href=\"/~siam/logout.php\">log out</a> before registering!");
      // echo "<pre>", print_r($userData, true), "</pre>\n";
    } else
      remark("If you are a member of the Manchester Student SIAM Chapter, please <a href=\"/~siam/login.php\">log in</a> before registering!");
    echo "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" class=\"registration\">\n\n";
    foreach ($conferenceData["regFields"] as $key => $field) {
      if (fieldSQLtype($field) == "") continue;
      $name = fieldName($field);
      echo "  <tr valign=\"top\">\n    <th>";
      if (fieldMandatory($field)) echo $mandatoryField;
      echo "<label for=\"fld_$name\">$key</label>:</th>\n    <td>";
      if ($userLoggedIn && isset($userData[$name]))
        echo $userData[$name];
      else
        echo htmlTemplate($field);
      echo "</td>\n  </tr>\n\n";
    }
    echo "  <tr>\n    <th>${mandatoryField}Bot-unfriendly test:</th>\n    <td>$t = <input type=\"text\" name=\"captcha\" size=\"3\" maxlength=\"3\" /></td>\n  </tr>\n\n";
    echo "  <tr>\n    <td colspan=\"2\" align=\"right\"><input type=\"submit\" name=\"confReg\" value=\"     Submit     \" /> <input type=\"reset\" value=\"     Reset all fields     \" /></td>\n  </tr>\n\n";
    echo "</table>\n\n";
    echo "</form>\n\n";
  }
  echo "</div>\n\n";
}

function fixField($field, $data) {
  if (substr(fieldType($field), 0, 4) !== "file") return $data;
  if (is_array($data)) return implode('/', $data);
  return $data;
}

function handleUpload($file = null) {
  global $conferenceData;
  if ($file["size"] <= 0) return "";
  if ($file["error"] == UPLOAD_ERR_INI_SIZE) {
    processRegistrationError("File too big: '".htmlspecialchars($file["name"])."'");
    return "";
  } elseif ($file["error"] == UPLOAD_ERR_PARTIAL) {
    processRegistrationError("File uploaded only partially: '".htmlspecialchars($file["name"])."'");
    return "";
  } elseif ($file["error"] != UPLOAD_ERR_OK) {
    processRegistrationError("Unknown error uploading file '".htmlspecialchars($file["name"])."'");
    return "";
  }
  if (is_uploaded_file($file['tmp_name'])) {
    $GLOBALS["filesToUpload"][] = $file['tmp_name'];
    return $file['name'];
  }
  return "";
}

function handleUploads($fn) {
  if (!isset($_FILES[$fn])) return false;
  handleUpload();
  if (is_array($_FILES[$fn]["name"])) {
    $files = array();
    $cnt = count($_FILES[$fn]["name"]);
    for ($i = 0; $i < $cnt; $i++) {
      $file = array();
      foreach ($_FILES[$fn] as $key => $data)
        $file[$key] = $data[$i];
      $fname = handleUpload($file);
      if (!empty($fname)) $files[] = $fname;
    }
    $files = implode('/', $files);
  } else
    $files = handleUpload($file);
  return $files;
}

function finalizeUploads($id) {
  global $uploadsDir;
  $idx = 0;
  foreach ($GLOBALS["filesToUpload"] as $idx => $tmpname)
    move_uploaded_file($tmpname, "$uploadsDir/${id}_$idx");
}

function processRegistration() {
  global $userLoggedIn, $userData, $conferenceData;
  if (!isset($_POST["confReg"])) return false;
  if (isset($conferenceData["registrationDeadline"]) && $conferenceData["registrationDeadline"] < time()) return false;
  $GLOBALS["inRegistration"] = false;
  //echo "<pre>POST: ", print_r($_POST, true), "FILES: ", print_r($_FILES, true), "</pre>"; exit;
  if (empty($_POST["captchaCheck"]) || empty($_POST["captcha"]) || $_POST["captchaCheck"] != mkhash($_POST["captcha"]))
    processRegistrationError("Anti-bot code is wrong or missing. Please try again.");
  $regData = array();
  $reqFields = array();
  if ($userLoggedIn && isset($_POST["siam_id"]) && $_POST["siam_id"] == $userData["pkm"]) {
    $sql = "siam_id = :siam_id";
    $regData["siam_id"] = $userData["pkm"];
    foreach ($conferenceData["regFields"] as $desc => $field) {
      if (fieldSQLtype($field) == "") continue;
      $fn = fieldName($field);
      if (!empty($userData[$fn])) {
        $sql .= ", $fn = :$fn";
        $regData[$fn] = fixField($field, $userData[$fn]);
      } elseif (isset($_POST[$fn]) && $_POST[$fn]) {
        $sql .= ", $fn = :$fn";
        $regData[$fn] = fixField($field, $_POST[$fn]);
      } elseif (substr(fieldType($field), 0, 4) === "file") {
        $sql .= ", $fn = :$fn";
        $regData[$fn] = handleUploads($fn);
      } elseif (fieldMandatory($field))
        $reqFields[] = $desc;
    }
  } else {
    $sql = "";
    foreach ($conferenceData["regFields"] as $desc => $field) {
      if (fieldSQLtype($field) == "") continue;
      $fn = fieldName($field);
      if (isset($_POST[$fn]) && $_POST[$fn]) {
        $sql .= (empty($sql) ? "" : ", ")."$fn = :$fn";
        $regData[$fn] = fixField($field, $_POST[$fn]);
      } elseif (substr(fieldType($field), 0, 4) === "file") {
        $sql .= ", $fn = :$fn";
        $regData[$fn] = handleUploads($fn);

      } elseif (fieldMandatory($field))
        $reqFields[] = $desc;
    }
  }

  //echo "<pre>SQL:\n$sql\nregData: ", print_r($regData, true), ",reqFields: ", print_r($reqFields, true), "</pre>\n\n";

  if ($reqFields)
    processRegistrationError("Missing mandatory fields:<ul><li>".implode("<li></li>", $reqFields)."</li></ul>");
  if ($GLOBALS["processRegistrationErrors"]) return false;
  if (empty($sql))
    return processRegistrationError("No registration data");
  $sql .= ", code = :code";
  $regData["code"] = rand();
  $sql = "insert into ".confTable("attend")." set $sql";
  //echo "<pre>$sql\n", print_r($regData, true), "</pre>"; exit;

  SaveData:
  $sth = dbQuery($sql, $regData);
  $err = $sth->errorCode();
  if ($err != '00000') {
    //echo "<pre>SQL:\n$sql\n\nError [", $sth->errorCode(), "]: ", print_r($sth->errorInfo(), true), "</pre>\n"; exit;
    if (dbErrorInfo($sth, '42S02', '1146')) {
      $sth = dbCreateConfTable("attend");
      if ($sth->errorCode() !== '00000')
        return processRegistrationError("Cannot create attendants' table. Reported error:<br /><i>".dbErrorMessage($sth)."</i>");
      goto SaveData;
    }
    elseif (dbErrorInfo($sth, '23000', '1062'))
      return processRegistrationError("The application with the same ".$conferenceData["uniqueFields"]." was already processed.");
  }
  $id = $GLOBALS["dbh"]->lastInsertId("id");
  finalizeUploads($id);
  /*** Dodaj slanje maila! ***/
  $regData["id"] = $id;
  $regData["conf"] =& $conferenceData;
  $tpl = new vsTPL();
  if (!@mail(
        $conferenceData["email"],
        (empty($conferenceData["mailSubjectPrefix"]) ? "" : $conferenceData["mailSubjectPrefix"]." ")."Registration confirmation",
        $tpl->parseTPLexec($conferenceData[($conferenceData["needConfirmation"] ? "" : "no")."confirmationEmail"], $regData),
        "From: $conferenceData[mailFrom]\n"
      )) {
    return processRegistrationError("Error sending a confirmation mail.");
  }
  $GLOBALS["registrationDone"] = true;
}

function displayReglist() {
  global $conferenceData;
  if (!hasPerm("Events organizer")) return error("You do not have permission to view the list of attendants.");
  $sth = dbQuery("select * from ".confTable("attend")." order by lastname, firstname, id");
  echo "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bordered hover\" id=\"attendants\">\n\n";
  $display = array();
  $types = array();
  $attachCnt = 0;
  $imgConfirmed = "<img src=\"images/ok.png\" alt=\"[Confirmed]\" width=\"16\" height=\"13\" border=\"1\" />";
  $imgUnconfirmed = "<img src=\"images/bad.png\" alt=\"[Not confirmed]\" width=\"15\" height=\"15\" border=\"1\" />";
  echo "  <thead>\n\n";
  echo "  <tr>\n";
  echo "    <th class=\"filterMenu\">Filter<div class=\"filterMenu\" id=\"filterMenuConfirmed\"><div title=\"Show all\" onClick=\"filterTableRows('attendants'); return false;\">A</div><div title=\"Show confirmed\" onClick=\"filterTableRows('attendants', 'confirmed'); return false;\">$imgConfirmed</div><div title=\"Show unconfirmed\" onClick=\"filterTableRows('attendants', 'unconfirmed'); return false;\">$imgUnconfirmed</div></div></th>\n";
  foreach ($conferenceData["regFields"] as $key => $field) {
    if ($display[$key] = fieldDisplayInList($field))
      echo "    <th>".htmlspecialchars($key)."</th>\n";
    $types[$key] = fieldBasicType($field);
  }
  echo "  </tr>\n\n";
  echo "  </thead>\n\n";
  echo "  <tbody>\n\n";
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    echo "  <tr class=\"".($row["confirmed"] ? "confirmed" : "unconfirmed")."\" onClick=\"location='?id=$row[id]'; return false;\">\n";
    echo "    <td style=\"text-align: center;\" title=\"".($row["confirmed"] ? "Confirmed" : "Not confirmed")."\">".($row["confirmed"] ? $imgConfirmed : $imgUnconfirmed)."</td>\n";
    foreach ($conferenceData["regFields"] as $key => $field) {
      $fn = fieldName($field);
      if ($display[$key])
        if ($types[$key] == "file") {
          $cnt = (empty($row[$fn]) ? 0 : substr_count($row[$fn], "/") + 1);
          $attachCnt += $cnt;
          echo "    <td style=\"text-align: center;\">".($cnt > 0 ? "<img src=\"images/attach.png\" width=\"16\" height=\"16\" alt=\"[Attachments: $cnt]\" title=\"Attachments: $cnt\"/>" : "&ndash;")."</td>\n";
        } else
          echo "    <td>".htmlspecialchars($row[$fn])."</td>\n";
    }
    echo "  </tr>\n\n";
  }
  echo "  </tbody>\n\n";
  echo "</table>\n\n";
  if ($attachCnt > 0) echo "<p>Total number of attached files: $attachCnt. [ <a href=\"?zip\">Download all as a ZIP archive</a> ]</p>\n\n";
}

function getAttendant($id) {
  $sth = dbQuery("select * from ".confTable("attend")." where id=:id order by lastname, firstname, id", array("id" => $id));
  $row = $sth->fetch(PDO::FETCH_ASSOC);
  if (!$row) return false;
  return $row;
}

function displayReg($id) {
  global $uploadsDir;
  if (!hasPerm("Events organizer")) return error("You do not have permission to view attendants' info.");
  if (!$id) return false;
  $id = (int)$id;
  $row = getAttendant($id);
  if (!$row) return false;
  echo "<div style=\"text-align: center;\"><div class=\"registration\">\n\n";
  echo "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" class=\"registration\">\n\n";
  echo "  <tr valign=\"top\">\n    <th>Confirmed:</th>\n    <td>".($row["confirmed"] ? "<span class=\"ok\">Yes</span>" : "<span class=\"bad\">No</span> [ <a href=\"?confirm=$id\">Confirm?</a> ]")."</td>\n  </tr>\n\n";
  $attachCnt = 0;
  foreach ($GLOBALS["conferenceData"]["regFields"] as $key => $field) {
    echo "  <tr valign=\"top\">\n    <th>$key:</th>\n    <td>";
    $type = fieldBasicType($field);
    $value = $row[fieldName($field)];
    switch ($type) {
      case "checkbox":
        echo ($value ? "Yes" : "No");
        break;
      case "file":
        if (empty($value))
          echo "&mdash;";
        else {
          $files = explode("/", $value);
          $attachCnt += count($files);
          echo "<ul>\n";
          foreach ($files as $idx => $file)
            echo "      <li><a href=\"?id=$id&amp;att=$idx\">".htmlspecialchars($file)."</a> (".size2str(filesize("$uploadsDir/${id}_$idx")).")</li>\n";
          echo "    </ul>";
        }
        break;
      default:
        $ov = $value;
        $value = preg_replace('#\n{2,}|\r{2,}|(\r\n){2,}#', "</p><p>", htmlspecialchars($value));
        $value = str_replace(array("\r\n", "\n", "\r"), "<br />", $value);
        if ($ov != $value) $value = "<p>$value</p>";
        echo $value;
    }
    echo "</td>\n  </tr>\n\n";
  }
  echo "  <tr valign=\"top\">\n    <th>Last data change:</th>\n    <td>$row[lastchange]</td>\n  </tr>\n\n";
  if ($attachCnt) echo "  <tr>\n    <th>Attachments:</th>\n    <td><a href=\"?zip=$id\">Download all $attachCnt as a ZIP archive</a></td>\n  </tr>\n\n";
  //echo "  <tr>\n    <th>:</th>\n    <td></td>\n  </tr>\n\n";
  echo "</table>\n\n";
  echo "</div></div>\n\n";
  echo "<p><a href=\"$_SERVER[PHP_SELF]\">&#8810; Back to the conference page</a></p>\n\n";
}

function downloadAttachment($id, $idx) {
  global $uploadsDir;
  if (!hasPerm("Events organizer")) return error("You do not have permission to download attached files.");
  if (!$id) return false;
  $id = (int)$id;
  $idx = (int)$idx;
  $sth = dbQuery("select * from ".confTable("attend")." where id=:id order by lastname, firstname, id", array("id" => $id));
  $row = $sth->fetch(PDO::FETCH_ASSOC);
  if (!$row) return processRegistrationError("No registrations are matching the given id.");
  $skipped = 0;
  foreach ($GLOBALS["conferenceData"]["regFields"] as $key => $field)
    if (fieldBasicType($field) == "file") {
      $value = $row[fieldName($field)];
      if (!empty($value)) {
        $files = explode("/", $value);
        $cnt = count($files);
        $newSkipped = $skipped + $cnt;
        if ($idx < $newSkipped) {
          $fname = $files[$idx - $skipped];
          break;
        }
        $skipped = $newSkipped;
      }
    }
  if (!isset($fname)) return processRegistrationError("Error obtaining file.");
  $localName = "$uploadsDir/${id}_$idx";
  $finfo = new finfo(FILEINFO_MIME);
  $mime = $finfo->file($localName);
  header("Content-type: $mime");
  header("Content-Disposition: attachment; filename=\"$fname\"");
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Length: ".filesize($localName));
  readfile($localName);
  exit(0);
}

function downloadAttachments($id) {
  global $uploadsDir;
  if (!hasPerm("Events organizer")) return error("You do not have permission to download attached files.");
  if (!$id) return false;
  $id = (int)$id;
  $sth = dbQuery("select * from ".confTable("attend")." where id=:id order by lastname, firstname, id", array("id" => $id));
  $row = $sth->fetch(PDO::FETCH_ASSOC);
  if (!$row) return processRegistrationError("No registrations are matching the given id.");
  $files = array();
  foreach ($GLOBALS["conferenceData"]["regFields"] as $key => $field)
    if (fieldBasicType($field) == "file") {
      $value = $row[fieldName($field)];
      if (!empty($value)) $files = array_merge($files, explode("/", $value));
    }
  if (!$files) return processRegistrationError("Error obtaining files.");
  $zip = new ZipArchive();
  $zname = tempnam("files/uploads", "attach_");
  if($zip->open($zname, ZIPARCHIVE::OVERWRITE) !== true)
    return processRegistrationError("Error creating a zip archive.");
  foreach ($files as $idx => $fname)
    $zip->addFile("$uploadsDir/${id}_$idx", iconv("UTF-8", "CP852", $fname));
  $zip->close();
  header("Content-type: application/zip");
  header("Content-Disposition: attachment; filename=\"$row[lastname] $row[firstname].zip\"");
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Length: ".filesize($zname));
  readfile($zname);
  unlink($zname);
  exit(0);
}


function downloadAllAttachments() {
  global $conferenceData, $uploadsDir;
  if (!hasPerm("Events organizer")) return error("You do not have permission to download attached files.");
  $sth = dbQuery("select * from ".confTable("attend")." order by lastname, firstname, id");
  $allFiles = array();
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $files = array();
    foreach ($GLOBALS["conferenceData"]["regFields"] as $key => $field)
      if (fieldBasicType($field) == "file") {
        $value = $row[fieldName($field)];
        if (!empty($value)) $files = array_merge($files, explode("/", $value));
      }
    if ($files)
      $allFiles["$row[lastname] $row[firstname]"] = array($row["id"], $files);
  }
  $zip = new ZipArchive();
  $zname = tempnam("files/uploads", "attach_");
  if($zip->open($zname, ZIPARCHIVE::OVERWRITE) !== true)
    return processRegistrationError("Error creating a zip archive.");
  foreach ($allFiles as $dir => $files) {
    $dir = str_replace("/", "-", $dir);
    $zip->addEmptyDir(iconv("UTF-8", "CP852", $dir));
    foreach ($files[1] as $idx => $fname)
      $zip->addFile("$uploadsDir/$files[0]_$idx", iconv("UTF-8", "CP852", "$dir/$fname"));
  }
  $zip->close();
  header("Content-type: application/zip");
  header("Content-Disposition: attachment; filename=\"$conferenceData[code].zip\"");
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Length: ".filesize($zname));
  readfile($zname);
  unlink($zname);
  exit(0);
}

function checkConfirm($id) {
  $row = getAttendant($id);
  if (!$row)
    return processRegistrationError("No such attendant exists.");
  if ($row["confirmed"])
    return processRegistrationError("This attendant's registration was already confirmed.");
  return $row;
}

function handleConfirm() {
  if (!isset($_GET["confirm"])) return false;
  $id = (int)$_GET["confirm"];
  if (isset($_GET["code"])) {
    $row = checkConfirm($id);
    if (!$row) return false;
    if ($row["code"] != $_GET["code"])
      return processRegistrationError("No such attendant exists.");
    $sth = dbQuery("update ".confTable("attend")." set confirmed=true where id=:id and code=:code", array("id" => $id, "code" => $_GET["code"]));
    if ($sth->errorCode() != '00000')
      return processRegistrationError("An error has occured while confirming this attendant's registration.");
    if ($sth->rowCount() == 0)
      return processRegistrationError("This attendant's registration was already confirmed or the wrong code or id was supplied.");
  } elseif (hasPerm("Events organizer")) {
    $row = checkConfirm($id);
    if (!$row) return false;
    $sth = dbQuery("update ".confTable("attend")." set confirmed=true where id=:id", array("id" => $id));
    if ($sth->errorCode() != '00000')
      return processRegistrationError("An error has occured while confirming this attendant's registration.");
  } else
    return processRegistrationError("You are not allowed to confirm this attendant's registration.");
  outHeadConf("Attendant confirmation");
  remark("The following registration was successfully confirmed:<br /><b>".htmlspecialchars($row["title"])." ".htmlspecialchars($row["firstname"])." ".htmlspecialchars($row["lastname"])."</b> (".htmlspecialchars($row["email"]).") from <b>".htmlspecialchars($row["institution"])."</b>.", "Confirmed", "success");
  outFootConf();
}

?>
