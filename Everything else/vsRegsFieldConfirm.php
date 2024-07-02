<?php

/*************************************
 *** Confirmation field for vsRegs ***
 *************************************/

require_once("vsTPL.php");

class vsRegsFieldConfirm extends vsRegsField {

  public $tplSubject = "Registration confirmation";
  public $tplBody = "Dear {title} {lastname},\n\nwe have received your registration for the {conf}{title}. Please confirm it by clicking on the following link:\n{conf}{url}?confirm={code}\n\nWe are looking forward to seeing you!";
  public $extraHeaders = ""; // "From: $conferenceMail\n"
  public $fieldEmail = "email";
  public $plugin = null;
  public $htmlA = "A";
  public $htmlConfirmed = "+";
  public $htmlUnconfirmed = "&ndash;";

  public function __construct($name, $title = "", $mandatory = false, $htmlParams = null) {
    parent::__construct($name, $title, $mandatory, $htmlParams);
    $this->value = rand();
    $this->tpl = new vsTPL();
  }

  public function setParent($parent) {
    parent::setPArent($parent);
    if (empty($parent->params["url"]))
      $parent->params["url"] =
        "http://".$_SERVER["SERVER_NAME"].($_SERVER["SERVER_PORT"] == "80" ? "" : ":".$_SERVER["SERVER_PORT"]).
        $_SERVER["SCRIPT_NAME"];
    $this->plugin = $parent->addPlugin(new vsRegsPluginConfirm());
    $this->plugin->field = $this;
  }

  public function setDefaultTexts() {
    $this->addTexts(array(
      'confirmConfirmed' => 'Confirmed',
      'confirmErrorPreparingMail' => 'Error preparing a confirmation mail.',
      'confirmErrorSendingMail' => "Error sending a confirmation mail. Please, contact us via e-mail.",
      'confirmErrorSendingMailDebug' => "Error sending a confirmation mail:<pre><b>To:</b> <a href=\"mailto:{to}\">{to}</a>\n{headers}<b>Subject:</b> {subject}\n\n{body}</pre>",
      'confirmInfoMail' => 'Confirmation link was sent to your e-mail. Please follow the instructions there to complete your registration.',
      'confirmShowConfirmed' => 'Show confirmed',
      'confirmShowUnconfirmed' => 'Show unconfirmed',
      'confirmUnconfirmed' => 'Unconfirmed',
    ));
  }

  public function isVisible($scope = vsRegs::SCOPE_INPUT) {
    return ($scope == vsRegs::SCOPE_OUTPUT || $scope == vsRegs::SCOPE_TABLE);
  }

  public function getRowParams() {
    return array(
      "class" => ($this->isConfirmed() ? "confirmed" : "unconfirmed"),
    );
  }

  public function getColumnTitleParams() {
    return array(
      "class" => "filterMenu",
    );
  }

  public function getCellParams() {
    return array(
      "style" => "text-align: center;",
      "title" => ($this->isConfirmed() ? $this->text('confirmConfirmed') : $this->text('confirmUnconfirmed')),
    );
  }

  public function getColumnTitle() {
    return "Conf.<div class=\"filterMenu\" id=\"filterMenuConfirmed\"><div title=\"Show all\" onClick=\"filterTableRows('attendants'); return false;\">".$this->htmlA."</div><div title=\"".$this->text('confirmShowConfirmed')."\" onClick=\"filterTableRows('attendants', 'confirmed'); return false;\">".$this->htmlConfirmed."</div><div title=\"".$this->text('confirmShowUnconfirmed')."\" onClick=\"filterTableRows('attendants', 'unconfirmed'); return false;\">".$this->htmlUnconfirmed."</div></div>";  
  }

  public function jsCode() {
    return array(
      "Array.prototype.indexOf" =>
        "// Taken from https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/indexOf\n".
        "// due to lack of support in IE prior to version 9\n".
        "if (!Array.prototype.indexOf) {\n".
        "  Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {\n".
        "    \"use strict\";\n".
        "    if (this == null) {\n".
        "      throw new TypeError();\n".
        "    }\n".
        "    var t = Object(this);\n".
        "    var len = t.length >>> 0;\n".
        "    if (len === 0) {\n".
        "      return -1;\n".
        "    }\n".
        "    var n = 0;\n".
        "    if (arguments.length > 1) {\n".
        "      n = Number(arguments[1]);\n".
        "      if (n != n) { // shortcut for verifying if it's NaN\n".
        "        n = 0;\n".
        "      } else if (n != 0 && n != Infinity && n != -Infinity) {\n".
        "        n = (n > 0 || -1) * Math.floor(Math.abs(n));\n".
        "      }\n".
        "    }\n".
        "    if (n >= len) {\n".
        "      return -1;\n".
        "    }\n".
        "    var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);\n".
        "    for (; k < len; k++) {\n".
        "      if (k in t && t[k] === searchElement) {\n".
        "        return k;\n".
        "      }\n".
        "    }\n".
        "    return -1;\n".
        "  }\n".
        "}\n",
      "filterTableRows" =>
        "function filterTableRows(el, classes = null) {\n".
        "  if (typeof el !== 'object')\n".
        "    el = document.getElementById(el);\n".
        "  if (classes == null)\n".
        "    for (var i = 0; i < el.rows.length; i++)\n".
        "      el.rows[i].style.display = \"table-row\";\n".
        "  else {\n".
        "    if (typeof classes == 'string')\n".
        "      classes = [ classes ];\n".
        "    for (var i = 1; i < el.rows.length; i++) {\n".
        "      var show = false;\n".
        "      var cList = el.rows[i].className.split(\" \"); // used instead of a buggy classList\n".
        "      for (var j = 0; j < classes.length; j++)\n".
        "        if (cList.indexOf(classes[j]) >= 0) {\n".
        "          show = true;\n".
        "          break;\n".
        "        }\n".
        "      el.rows[i].style.display = (show ? \"table-row\" : \"none\");\n".
        "    }\n".
        "  }\n".
        "}\n",
      );
  }

  public function assign($tplSubject, $tplBody, $extraHeaders = "", $fieldEmail = "email") {
    $this->tplSubject = $tplSubject;
    $this->tplBody = $tplBody;
    $this->extraHeaders = $extraHeaders;
    $this->fieldEmail = $fieldEmail;
    return $this;
  }

  public function isConfirmed() {
    $value = $this->getValue();
    return isset($value) && isset($value["confirmed"]) && $value["confirmed"];
  }

  // No code output to the user; only info on the confirmation
  protected function htmlInput($params) { return ""; }
  protected function htmlEdit($params) { return ""; }
  protected function htmlOutput($params) {
    $res = $this->htmlCell($params);
    $currentId = $this->parent->getCurrentId();
    if ($this->isConfirmed()) return $res;
    if (!empty($currentId) && $this->parent->hasRole(vsRegs::ROLE_EDIT))
      $res .= " [<a href=\"?confirm=".((int)$currentId)."\">Confirm</a>]";
    return $res;
  }

  protected function htmlCell($params) {
    return ($this->isConfirmed() ? $this->htmlConfirmed : $this->htmlUnconfirmed);
  }

  public function sqlDefinition() {
    return
      "`".$this->getName()."` boolean default false,".
      "`".$this->getName()."Code` integer ".$this->sqlDefinitionNull();
  }

  public function clearValue() {
    return true;
  }

  public function exportValueToDBrow() {
    $value = $this->getValue();
    if (isset($value)) return array("attend" => array($this->getName()."Code" => $value)); else return null;
  }

  public function importValueFromDBrow($row) {
    if (!is_array($row)) return true;
    return $this->setValue(array(
      "code" => (!isset($row[$this->getName()."Code"]) || empty($row[$this->getName()."Code"]) ? -1 : (int)$row[$this->getName()."Code"]),
      "confirmed" => (!isset($row[$this->getName()]) || empty($row[$this->getName()]) ? false : (bool)$row[$this->getName()]),
    ));
  }

  public function afterInsert() {
    $parent =& $this->parent;

    $data = $parent->getCurrentData();
    if (!is_array($data)) return $parent->addError('confirmErrorPreparingMail');

    $fldName = $this->getName();
    if (isset($data[$fldName]) && $data[$fldName]) return true;

    $cnt = $parent->getFieldsCount();
    for ($i = 0; $i < $cnt; $i++) {
      $field =& $parent->getField($i);
      $name = $field->getName();
      if ($name != $this->fieldEmail)
        $data[$name] = $field->getHtmlOutput();
    }
    $data["conf"] =& $parent->params;

    $subject = $this->tpl->parseTPLexec($this->tplSubject, $data);
    $body = preg_replace('#<\s*br\s*/?\s*>#', ' ', $this->tpl->parseTPLexec($this->tplBody, $data));
    $body = preg_replace('#<[^>]+>#', '', $body);
    if (!mail(
        $data[$this->fieldEmail],
        $subject,
        $body,
        $this->extraHeaders
    )) return $parent->addError('confirmErrorSendingMail', array(
      'to' => htmlspecialchars($data[$this->fieldEmail]),
      'subject' => htmlspecialchars($subject),
      'body' => htmlspecialchars($body),
      'headers' => htmlspecialchars($this->extraHeaders),
    ));
    return $parent->addInfo('confirmInfoMail');
  }

}

/*******************************************
 *** Plugin to handle confirmation links ***
 *******************************************/

class vsRegsPluginConfirm extends vsRegsPlugin {

  public $field = null;
  public $membersId = "attendants";

  public function setParent($parent) {
    parent::setParent($parent);
    $this->parent->addBulkAction("confirm", $this->text('confirmBulkConfirm'));
    $this->parent->addBulkAction("unconfirm", $this->text('confirmBulkUnconfirm'));
  }

  public function setDefaultTexts() {
    $this->addTexts(array(
      'confirmBulkConfirm' => 'Confirm registration',
      'confirmBulkUnconfirm' => 'Unconfirm registration',
      'confirmErrorConfig' => 'Error in configuration.',
      'confirmErrorConfigDebug' => 'Error in configuration.<pre>{data}</pre>',
      'confirmErrorConfirmed' => 'This registration was already confirmed.',
      'confirmErrorInvalidCode' => 'Invalid code.',
      'confirmErrorPluginConfig' => 'Confirmation plugin was not configured properly.',
      'confirmErrorSavingConfirmation' => 'Error saving confirmation to the database.',
      'confirmErrorSavingConfirmationDebug' => 'Error saving confirmation to the database:{lastPDOerror}',
      'confirmErrorSavingData' => 'Error saving data.',      
      'confirmErrorWrongCode' => 'Incorrect confirmation code. Please check that you have used the correct link.',
      'confirmSuccessOther' => 'The registration was successfully confirmed. Thank you.',
      'confirmSuccessSave' => 'The new data was successfully saved.',
      'confirmSuccessYour' => 'Your registration was successfully confirmed. Thank you.',
    ));
  }

  public function unique() {
    return true;
  }

  // No direct output; we shall signal that we handled what we had and leave a note in the infos/errors list
  protected function endWithErrorPlain($msg) {
    $this->parent->autoHandled = true;
    $this->parent->addErrorPlain($msg);
    return false;
  }
  protected function endWithError($msg, $params = null) {
    $this->parent->autoHandled = true;
    $this->parent->addError($msg, $params);
    return false;
  }
  protected function endWithInfoPlain($msg) {
    $this->parent->autoHandled = true;
    $this->parent->addInfoPlain($msg);
    return true;
  }
  protected function endWithInfo($msg, $params = null) {
    $this->parent->autoHandled = true;
    $this->parent->addInfo($msg, $params);
    return true;
  }

  public function confirm($id, $code = null) {
    $id = (int)$id;
    $fieldId = $this->parent->params["fieldId"];
    $fieldConfirm = $this->field->getName();
    $fieldCode = $fieldConfirm."Code";
    if (empty($fieldCode)) return $this->endWithError('confirmErrorPluginConfig');
    $data = $this->parent->membersInfo(true, $id);
    if (!$data) return $this->endWithError('confirmErrorWrongCode');
    $data = $data[0];
    if (!isset($data[$fieldId]) || !isset($data[$fieldCode])) return $this->endWithError('confirmErrorConfig', array('data' => print_r($data, true)));
    $successText = $this->text('confirmSuccessYour');
    if ($allowedToConfirm = ((int)$data[$fieldId] === $id))
      if (isset($code))
        $allowedToConfirm = ((int)$data[$fieldCode] === $code);
      else {
        $allowedToConfirm = $this->parent->hasRole(vsRegs::ROLE_EDIT);
        $successText = $this->text('confirmSuccessOther');
      }
    if ($allowedToConfirm) {
      if ($data[$fieldConfirm])
        return $this->endWithError('confirmErrorConfirmed');
      // Success; save the confirmation and return the info
      $sth = $this->query("update ".$this->tableName("attend")." set $fieldConfirm=true where $fieldId=:id", array("id" => (int)$data[$fieldId]));
      if (!$sth || $sth->errorCode() != '00000')
        return $this->endWithError('confirmErrorSavingConfirmation');
      return $this->endWithInfoPlain($successText);
    }
    return $this->endWithError('confirmErrorWrongCode');
  }

  public function beforeMembersTable() {
    $params =& $this->parent->params;
    if (isset($params["membersId"]) && !empty($params["membersId"]))
      $this->membersId = $params["membersId"];
    elseif (isset($this->membersId) && !empty($this->membersId))
      $params["membersId"] = $this->membersId;
    else
      $this->membersId = $params["membersId"] = "attendants";
    return "";
  }

  public function beforeHandleRequests() {
    $name = $this->field->getName()."Code";
    if (isset($_POST["submit"]) && !empty($_POST["id"]) && !empty($_POST[$name])) {
      if ($this->parent->deadlinePassed("Registrations")) return "";
      $this->autoHandled = true;
      if ($this->parent->readValuesFromPost(vsRegs::SCOPE_EDIT)) {
        $this->field->clearValue();
        //print_r($this->field->getValue()); exit;
        $id = (int)$_POST["id"];
        $data = $this->parent->getCurrentData();
        if (!isset($data) || !$data) return "";
	$this->parent->autoHandled = true;
        if ((int)$_POST[$name] != (int)$data[$name]) {
          //echo "name = '$name'<br>POST[name] = '$_POST[$name]'<br>data[name] = '$data[$name]'<br>"; print_r($data);exit;
          $this->parent->addError('confirmErrorInvalidCode');
          return "";
        }
	$this->parent->grantRole(vsRegs::ROLE_EDIT);
        $this->field->setValue(null);
        if ($this->parent->saveData()) {
          $this->parent->addInfo('confirmSuccessSave');
	  $this->parent->unsetRole();
          return "";
        }
        $this->parent->addError('confirmErrorSavingData');
	$this->parent->unsetRole();
        return "";
      }
    } elseif (!(empty($_GET["id"]) || empty($_GET["code"])))
      $this->confirm((int)$_GET["id"], (int)$_GET["code"]);
    elseif (!empty($_GET["confirm"]))
      $this->confirm((int)$_GET["confirm"]);
    elseif (!empty($_GET["edit"]) && !empty($_GET["code"])) {
      $id = (int)$_GET["edit"];
      $code = (int)$_GET["code"];
      $data = $this->parent->loadMemberData($id);
      if (isset($data[$name]) && (int)$data[$name] === $code) {
        $this->autoResults["handled"] = true;
        return $this->parent->generateDataBox(vsRegs::SCOPE_EDIT, array(
          "head" => "<input type=\"hidden\" name=\"$name\" value=\"$code\" />",
        ));
      }
      $this->parent->clearValues();
    }

    return "";
  }

  public function bulkAction($params) {
    list ($action, $ids) = $params;
    $sth = false;
    if ($action === "confirm") {
      $fieldId = $this->parent->params["fieldId"];
      $fieldConfirm = $this->field->getName();
      $sth = $this->query("update ".$this->tableName("attend")." set $fieldConfirm=true where $fieldId in (".implode(",", $ids).")");
    } elseif ($action === "unconfirm") {
      $fieldId = $this->parent->params["fieldId"];
      $fieldConfirm = $this->field->getName();
      $sth = $this->query("update ".$this->tableName("attend")." set $fieldConfirm=false where $fieldId in (".implode(",", $ids).")");
    }
    if ($sth && $sth->errorCode() == '00000') {
      header("Location: ?list");
      exit(0);
    }
    return "";
  }

}
