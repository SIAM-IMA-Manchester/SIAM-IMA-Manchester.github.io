<?php

require_once("common.php");
require_once("vsRegs.php");
require_once("vsRegsFieldConfirm.php");
require_once("vsRegsPluginZip.php");

$galleryBaseDir = "files/galleries";

/* When the config of the conference is changed, it is best to copy this file and use a copy,
   so that the original is kept by the old conferences. */

class vsRegsSIAM extends vsRegs {

  function __construct($params, $role = vsRegs::ROLE_USER) {
    if (!isset($params["fields"])) {
      $fieldConf = new vsRegsFieldConfirm("confirm", "Account confirmed");
      $fieldConf->extraHeaders = "From: $params[email]\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
      $fieldConf->htmlConfirmed = "<img src=\"images/ok.png\" alt=\"[Confirmed]\" width=\"16\" height=\"13\" border=\"1\" />";
      $fieldConf->htmlUnconfirmed = "<img src=\"images/bad.png\" alt=\"[Not confirmed]\" width=\"15\" height=\"15\" border=\"1\" />";
      $fieldConf->tplBody = "Dear {title} {lastname},\n\nwe have received your registration for the {conf}{title} with the following info:\n\nName: {title} {firstname} {lastname}\nE-mail: {email}\nInstitution: {institution}\nInformal outing: {outing}\n\nRemarks:\n{iff}{{remarks}}{{remarks}}{-}\n\nPlease confirm your registration by clicking on the following link:\n{conf}{url}?id={id}&code={confirmCode}\n\nIf you wish to edit your information, you can do so here:\n{conf}{url}?edit={id}&code={confirmCode}\n\nWe are looking forward to seeing you!\n\nManchester SIAM Student Chapter";

      $fieldAttach = new vsRegsFieldFile("file", "Attachment");
      $fieldAttach->subtitle = "An abstract or a poster in PDF format.";
      $fieldAttach->htmlAdd = "<img src=\"images/fileAdd.png\" align=\"top\" />";
      $fieldAttach->htmlRemove = "<img src=\"images/fileRemove.png\" align=\"top\" />";
      $fieldAttach->htmlCell = "<img src=\"images/attach.png\" title=\"Number of attachments: %d\" />";
      $fieldAttach->deadlineDesc = "Abstract and poster submission";

      $fieldTitle = new vsRegsFieldSelect("title", "Title", true);
      $fieldTitle->assign(array('', 'Dr', 'Professor', 'Mr', 'Miss', 'Mrs', 'Ms'), "");

      $fieldOuting = new vsRegsFieldRadio("outing", "Informal outing");
      $fieldOuting->assign(array('Yes', 'No'), "");

      $params["fields"] = array(
        new vsRegsFieldHidden("siam_id", "SIAM id"),
        $fieldConf,
        $fieldTitle,
        new vsRegsFieldInput("firstname", "First name", true),
        new vsRegsFieldInput("lastname", "Last name", true),
        new vsRegsFieldInputUnique("email", "E-mail", true),
        new vsRegsFieldInput("institution", "Department", true),
        //$fieldOuting,
        new vsRegsFieldText("remarks", "Remarks"),
        $fieldAttach,
        //new vsRegsFieldFiles("files", "Attachments"),
        //new vsRegsFieldAvatar("avatar", "Avatar"),
        new vsRegsFieldCaptcha("abc", "Bot-unfriendly test"), // "abc" = "anti-bot check/captcha"
      );
      $params["fields"][5]->maxCellLength = 11;
      $params["fields"][7]->subtitle = "This is not covered by the organisers.";
      $params["fields"][7]->default = 1;
      //$params["fields"][8]->default = 0;// Added by Sophia. 
      //$params["fields"][8]->subtitle = "If you do not wish to join the SIAM Chapter, <br />please click 'No'.";// Added by Sophia. 
      $params["fields"][8]->subtitle = "Please specify any dietary needs, equipment<br />for talk, etc here.";
      if ($params["role"] != vsRegs::ROLE_ADMIN) {
        $params["fields"][5]->visibleInMembersTable = false;
        $params["fields"][7]->visibleInMembersTable = false;
        $params["fields"][9]->visibleInMembersTable = false;
      }
    }
    //var_dump($params); exit(0);
    if (!isset($params["subtitleCSS"])) $params["subtitleCSS"] = "hint";
    
    $params["registrationHead"] = mkremark("After you submit your data, you will receive an e-mail with links for confirming and editing your registration information or uploading an attachment any time before the deadline.");
    parent::__construct($params, $role);

    if (empty($this->params["membersTableSelectExtra"]))
      $this->params["membersTableSelectExtra"] = "order by lastname, firstname, ".$this->params["fieldId"];
    $this->getFieldByName("remarks")->visibleInMembersTable = false;

    $userDataPlugin = new vsRegsPluginAutoRegInfo();
    $userDataPlugin->assign(
      $GLOBALS["userData"],
      mkremark("If this is not your data, please <a href=\"/~siam/logout.php?url=".currentUrl()."\">log out</a> before registering!"),
      mkremark("If you are a member of the Manchester Student SIAM Chapter, please <a href=\"/~siam/login.php?url=".currentUrl()."\">log in</a> before registering!")
    )->assignMap("siam_id", "pkm");
    $this->addPlugin($userDataPlugin);

    $this->addPlugin(new vsRegsPluginZip());

  }

}

function outHeadConf($title = "") {
  global $conferenceData, $conf, $galleryBaseDir;
  static $extensions = array("jpg", "jpeg", "png", "gif");

  $extraHead = "";
  $gallery = (isset($_GET["gallery"]) ?
    $galleryBaseDir."/".$conferenceData["code"] :
    ""
  );
  $hasGallery = (!empty($gallery) && is_dir($gallery));

  if ($hasGallery) {
    $extraHead .= '  <script src="prettyPhoto/js/jquery.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" href="prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
  <script src="jquery.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $("a[rel^=\'photo\']").prettyPhoto();
    });
  </script>';
  } else {
    $conf = new vsRegsSIAM($conferenceData);
    $output = $conf->handleRequests();
    $errors = $conf->errors();
    $infos = $conf->infos();
  }

  if (hasPerm("Events organizer")) {
    $GLOBALS["mainMenu"][1]["submenu"][] = array(
      "url" => $_SERVER["PHP_SELF"]."?list",
      "title" => "List of registrants",
    );
  }

  $st = $conferenceData["shortTitle"];
  if (!empty($title)) $st = (empty($st) ? "" : " ").$title;
  outHead($conferenceData["title"], $st, $extraHead);

  if ($hasGallery) {
    $files = array();
    $dh = opendir($gallery);
    while ($fn = readdir($dh)) {
      if (preg_match('#-thumb\.\w+$#', $fn) || is_dir("$gallery/$fn")) continue;
      $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
      if (!in_array($ext, $extensions)) continue;
      $files[] = $fn;
    }
    closedir($dh);
    if ($files) {
      global $imgTitles, $imgCaptions;
      sort($files);
      $defaultCaption = (isset($GLOBALS["defaultCaption"]) ? $GLOBALS["defaultCaption"] : "");
      $defaultTitle = (isset($GLOBALS["defaultTitle"]) ? $GLOBALS["defaultTitle"] : "");
      echo "<h1>Gallery</h1>\n\n";
      echo "<div class=\"gallery content\" id=\"gallery\"> <ul class=\"gallery light_rounded\">\n";
      foreach ($files as $fn) {
        $f = "$gallery/$fn";
        $thumb = preg_replace('#\.\w+$#', '-thumb\0', $f);
        if (file_exists($thumb)) {
          $sizes = getImageSize($thumb);
          $dim = $sizes[3];
        } else {
          $thumb = $f;
          $dim = " height=\"60\" width=\"60\"";
        }
        echo "<li><a href=\"$f\" rel=\"photo[]\" title=\"".htmlspecialchars(isset($imgCaptions[$fn]) ? "$imgCaptions[$fn]" : $defaultCaption)."\"><img src=\"$thumb\" class=\"thumb\" $dim alt=\"".htmlspecialchars(isset($imgTitles[$fn]) ? $imgTitles[$fn] : $defaultTitle)."\" /></a></li>\n";
      }
      echo "</ul></div>\n\n";
      $zipFile = $gallery."/".$conferenceData["code"]."-photos.zip";
      if (file_exists($zipFile))
        echo "<p>".dwldLink($zipFile, "All photos")."</p>\n\n";
      outFootConf();
    }
  }

  if (!empty($title)) echo "<h2 style=\"text-align: center;\">".ucwords($title)."</h2>\n\n";
  if (!empty($conferenceData["header"])) echo "<p style=\"text-align: center;\">", $conferenceData["header"], "</p>\n\n";
  if ($errors) {
    if (count($errors) > 1) {
      $errors = "The following errors have occured:<ul>\n  <li>".implode("</li>\n  <li>", $errors)."</li>\n</ul>";
      $br = "";
    } else {
      $errors = $errors[0];
      $br = "<br />";
    }
    error($errors);
  }
  if ($infos)
    remark(implode("<br />\n", $infos), "Success", "success");
    //remark("Registration data was successfully saved.<br /><b>Please confirm your registration</b> by clicking the link that was sent to you in a confirmation e-mail.", "Success", "success");
  if (!empty($output)) echo $output;
  if ($conf->autoHandled) outFootConf();
}

function outFootConf() {
  outFoot();
}

function outDeadlines() {
  echo "<h3>Deadlines</h3>\n\n<div class=\"deadlines\">\n".$GLOBALS["conf"]->generateDeadlinesBox()."</div>\n\n";
}

?>
