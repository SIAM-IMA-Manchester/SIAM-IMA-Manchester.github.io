<?php

/******************************
 *** ZIP plug-in for vsRegs ***
 ******************************/

require_once("vsTPL.php");

class vsRegsPluginZip extends vsRegsPlugin {

  public $createSubdirs = true;
  public $filenameEncoding = "ascii//translit//ignore"; // Suggested: setlocale(LC_CTYPE, "en_US.UTF-8");
  protected $files = null;
  protected $memberList = null;
  protected $id2name = null;

  public function __construct() {
    $this->init();
  }

  public function setParent($parent) {
    parent::setParent($parent);
    $this->parent->addBulkAction("download", $this->text('zipBulkDownload'));
  }

  public function setDefaultTexts() {
    $this->addTexts(array(
      'zipBulkDownload' => 'Download attachments',
      'zipDownloadAll' => 'Download all attachments ({cnt})',
      'zipErrorAddingFile' => 'Error adding file "{fname}" in the ZIP archive.',
      'zipErrorCreating' => 'Error creating a zip archive.',
      'zipErrorCreatingDir' => 'Error creating directory "{dir}" in the ZIP archive.',
      'zipErrorDirUndef' => 'Undefined uploads directory.',
    ));
  }

  public function unique() {
    return true;
  }

  public function addError($msg) {
    $this->parent->addError($msg);
    return "";
  }

  public function init() {
    $this->files = array();
    $this->memberList = null;
    $this->id2name = array();
    return "";
  }

  public function processImportedData($field) {
    if ($field instanceof vsRegsFieldFiles) {
      $values = $field->getValue();
      $pid = $this->parent->getCurrentId();
      if (isset($values["files"]))
        foreach ($values["files"] as $file) {
          $file["pid"] = $pid;
          $this->files[] = $file;
        }
    }
    return true;
  }

  public function registerBoxFoot($scope) {
    if ($scope != vsRegs::SCOPE_OUTPUT) return "";
    if (!$this->parent->hasRole(vsRegs::ROLE_DOWNLOAD)) return "";
    $cnt = count($this->files);
    return ($cnt ? "<div class=\"attachBox\"><a href=\"?attach=".$this->parent->getCurrentId()."\">".$this->text('zipDownloadAll', array('cnt' => $cnt))."</a></div>\n\n" : "");
  }

  public function afterMembersTable($memberList) {
    if (!$this->parent->hasRole(vsRegs::ROLE_DOWNLOAD)) return "";
    $this->memberList = $memberList;
    $cnt = count($this->files);
    return ($cnt ? "<div class=\"attachBox\"><a href=\"?attach\">".$this->text('zipDownloadAll', array('cnt' => $cnt))."</a></div>\n\n" : "");
  }

  public function beforeHandleRequests() {
    if (!isset($_GET["attach"])) return "";
    $id = $_GET["attach"];
    $downloader = $this->parent->hasRole(vsRegs::ROLE_DOWNLOAD);
    if (!empty($id) && ($downloader || $id == $this->parent->getCurrentId())) {
      $data = $this->parent->loadMemberData((int)$id);
      if (!$data) return "";
      return $this->zipFiles($this->data2name($data));
    } elseif ($downloader) {
      $memberList = $this->parent->membersInfo(true);
      if (!$memberList) return "";
      foreach ($memberList as $data) {
        $this->parent->clearValues();
	$this->parent->setCurrentData($data);
        $this->parent->importValuesFromDBrow($data);
      }
      $code = (isset($this->parent->params["code"]) ? $this->parent->params["code"] : "");
      if (empty($code)) $code = "attachments";
      return $this->zipFiles($code);
    }
    return "";
  }

  // Unless the fields are defined as here, new plugin has to inherit this one, redefining
  // at least this function
  protected function data2name($data) {
    if (isset($data["firstname"]) && isset($data["lastname"])) return "$data[lastname], $data[firstname]";
    if (isset($data["firstname"]) && isset($data["surname"])) return "$data[surname], $data[firstname]";

    if (isset($data["name"]) && isset($data["lastname"])) return "$data[lastname], $data[name]";
    if (isset($data["name"]) && isset($data["surname"])) return "$data[surname], $data[name]";
    if (isset($data["name"])) return $data["name"];
    return "";
  }

  public function id2name($id) {
    if (empty($id)) return "";
    $id = (int)$id;
    if (isset($this->id2name[$id])) return $this->id2name[$id];
    $fieldId = $this->parent->params["fieldId"];
    if (!isset($this->memberList))
      $this->memberList = $this->parent->membersInfo(false);
    if (is_array($this->memberList))
      foreach ($this->memberList as $data)
        if ((int)$data[$fieldId] === $id)
          return $this->id2name[$id] = $this->data2name($data);
    return "";
  }

  public function encode($str) {
    return (empty($this->filenameEncoding) ? $str : iconv("utf-8", $this->filenameEncoding, $str));
  }

  public function zipFiles($fname) {
    if (!$this->files) return "";
    $zip = new ZipArchive();
    $zname = tempnam("files/uploads", "attach_");
    if($zip->open($zname, ZIPARCHIVE::OVERWRITE) !== true)
      return $this->addError($this->text('zipErrorCreating'));
    $files =& $this->files;
    $createdSubdirs = array("" => true);
    $createSubdirs = $this->createSubdirs;
    $dir = "";
    if (!isset($this->parent->params["uploadsDir"]))
      return $this->addError($this->text('zipErrorDirUndef'));
    $uploadsDir = $this->parent->params["uploadsDir"];
    if (!empty($uploadsDir)) $uploadsDir .= "/";
    $addedFiles = array();
    foreach ($files as $file) {
      if ($this->createSubdirs) {
        $dir = $this->id2name($file["pid"]);
        if (!empty($dir)) {
          if (!isset($createdSubdirs[$dir])) {
            if (!$zip->addEmptyDir($this->encode($dir)))
              return $this->addError($this->text('zipErrorCreatingDir', array('dir' => htmlspecialchars($dir))));
            $createdSubdirs[$dir] = true;
          }
          $dir .= "/";
        }
      }
      $fullName = "$dir$file[filename]";
      if (isset($addedFiles[$fullName])) {
        $i = 1;
        if (preg_match('#(.*)(\.[^.]*)#', $fullName, $reOut)) {
          $tName = $reOut[1]."-";
          $tExt = $reOut[2];
        } else {
          $tName = $fullName."-";
          $tExt = "";
        }
        while (isset($addedFiles[$fullName = "$tName$i$tExt"])) $i++;
      }
      $addedFiles[$fullName] = true;
      if (!$zip->addFile("$uploadsDir$file[aid]", $this->encode($fullName)))
        return $this->addError($this->text('zipErrorCreatingDir', array('fname' => htmlspecialchars($fullName))));
    }
    if (strtolower(substr($fname, -4)) != ".zip")
      $fname .= ".zip";
    $zip->close();
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=\"$fname\"");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: ".filesize($zname));
    readfile($zname);
    unlink($zname);
    exit(0);
  }

  public function bulkAction($params) {
    list ($action, $ids) = $params;
    $sth = false;
    if ($action === "download") {
      $memberList = $this->parent->membersInfo(true, $ids);
      if (!$memberList) return "";
      foreach ($memberList as $data) {
        $this->parent->clearValues();
	$this->parent->setCurrentData($data);
        $this->parent->importValuesFromDBrow($data);
      }
      $code = (isset($this->parent->params["code"]) ? $this->parent->params["code"] : "");
      if (empty($code)) $code = "attachments";
      return $this->zipFiles($code);
    }
    return "";
  }

}

?>
