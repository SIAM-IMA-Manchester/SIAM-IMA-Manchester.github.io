<?php

/*
{variable}
{function}
{function}{arg0}...{argn}
Prefix notation:
{+}{x1}{x2}...{xn}
*/

class vsTPL {

  public $metaStrings = array(
    'escape' => '\\', // escape for blockStart
    'blockStart' => '{',
    'blockEnd' => '}',
    'predefinedVarsPrefix' => '__', // used in loops and likes
    'langFilenameSeparator' => '::',
    'stopParsing' => '|||', // {a}{b}{c}|||{d} parses as commands {a}{b}{c} and {d} with nothing in between
    'trimBefore' => array('#\s*?( ?)$#s', '\1'), // anchor $ is important!
    'trimAfter' => array('#^( ?)\s*#s', '\1'), // anchor ^ is important!
  );
  public $tplExtension = "tpl";
  public $extensionsDir = "extensions";
  public $autoloadExtensions = true;
  public $tplDir = "";
  public $langDir = "";
  public $langFiles = array();
  public $lang = "";
  public $defaultLang = "en";
  public $code2text = array("lang_missing_translation" => "Error in translation");
  protected $lastError = "ok";
  protected $lastErrorArgs = array();
  public $extensions = array(); # array of vsTPLextensionBase descendants
  public $templates = array(); # cached templates
  public $parsingDepth = 0;
  public $variables;
  public $operators;
  public $localVars;
  public $varsObj;
  public $loadedLangFiles = array();
  public $extensionNameBase = "vsTPLextension";
  public $mainLangFile = "main"; # autoloads, is called without prefix, should have langName defined
  protected $variablesLoaded = false;
  public $passthruFiles = array("jquery" => "application/javascript; jquery-1.4.4.min.js");
  public $parsethruFiles = array();

  public function __construct($params = null) {
    if (!isset($params)) $params = array();
    $this->startupParams = $params;
    if (isset($params["tplDir"])) $this->tplDir = $params["tplDir"];
    $this->appDir = (isset($params["appDir"]) ? $params["appDir"] : dirname(__FILE__));
    if (isset($params["extensionNameBase"])) $this->extensionNameBase = $params["extensionNameBase"];
    if (isset($params["tplExtension"])) $this->tplExtension = $params["tplExtension"];
    if (isset($params["extensionsDir"])) $this->extensionsDir = $params["extensionsDir"];
    if (isset($params["autoloadExtensions"])) $this->autoloadExtensions = $params["autoloadExtensions"];
    if (isset($params["langDir"])) $this->langDir = $params["langDir"];
    if (isset($params["lang"])) $this->lang = $params["lang"];
    if (isset($params["code2text"])) $this->code2text = array_merge($this->code2text, $params["code2text"]);
    if (isset($params["langFiles"])) {
      $langFiles = (is_array($params["langFiles"]) ? $params["langFiles"] : array($params["langFiles"]));
      foreach ($langFiles as $f) $this->loadLangFile($f);
    }
    if (isset($params["autoload"]))
      $autoload = (is_array($params["autoload"]) ? $params["autoload"] : array($params["autoload"]));
    else
      $autoload = array("Branches", "Loops", "TPL");
    $varsClass = (isset($params["varsClass"]) ? $params["varsClass"] : "Variables");
    $this->extensions = array();
    foreach ($autoload as $al)
      $this->loadExtension($al);
    $this->varsObj =& $this->loadExtension($varsClass);
    $this->variablesLoaded = true;
    if (isset($params["variables"]) && is_array($params["variables"]))
      $this->varsObj->variables = array_merge($this->varsObj->variables, $params["variables"]);
    $this->variables =& $this->varsObj->variables;
    $this->operators =& $this->varsObj->operators;
    $this->localVars =& $this->varsObj->localVars;
    if (isset($params["autoGET"]) && $params["autoGET"]) $this->autoGET();
  }

  protected function autoGET() {
    if (is_array($this->parsethruFiles))
      foreach ($this->passthruFiles as $code => $info)
        if (isset($_GET[$code])) {
          if (count($f = explode(";", $info, 2)) < 2) $f = array("application/octet-stream", $info);
          if (file_exists($this->tplDir.'/'.trim($f[1])))
            $f[1] = $this->tplDir.'/'.trim($f[1]);
          elseif (file_exists($this->appDir.'/'.trim($f[1])))
            $f[1] = $this->appDir.'/'.trim($f[1]);
          else continue;
          header("Content-type: ".trim($f[0]));
          $this->parsethru($f[1]);
          exit(0);
        }
    if (is_array($this->passthruFiles))
      foreach ($this->passthruFiles as $code => $info)
        if (isset($_GET[$code])) {
          if (count($f = explode(";", $info, 2)) < 2) $f = array("application/octet-stream", $info);
          if (file_exists($this->tplDir.'/'.trim($f[1])))
            $f[1] = $this->tplDir.'/'.trim($f[1]);
          elseif (file_exists($this->appDir.'/'.trim($f[1])))
            $f[1] = $this->appDir.'/'.trim($f[1]);
          else continue;
          header("Content-type: ".trim($f[0]));
          readfile($f[1]);
          exit(0);
        }
  }

  public function __get($varName) {
    return $this->$varName;
  }

  public function &loadExtension($name) {
    $name = $this->extensionNameBase.($oname = $name);
    if (class_exists($name)) {
      $obj = new $name($this);
    } elseif (!empty($this->extensionsDir)) {
      @include_once($this->extensionsDir."/$name.php");
      if (class_exists($name))
        $obj = new $name($this);
    }
    if (!isset($obj))
      return $this->returnWithError("nonexistent_extension", array("ext" => $oname));
    if (!($obj instanceof vsTPLextensionBase))
      return $this->returnWithError("invlaid_extension_class", array("ext" => $oname));;
    if (count($this->extensions) && $this->variablesLoaded)
      // array_splice($this->extensions, count($this->extensions) - 1, 0, array(&$obj));
      // Add to the begining to ensure override of the existing extensions if collision exists
      array_splice($this->extensions, 0, 0, array(&$obj));
    else
      $this->extensions[] =& $obj;
    $obj->init();
    return $obj;
  }

  public function getLangFile($f) {
    if (empty($this->langDir)) return "";
    $f2 = $this->langDir."/".$this->lang."/$f.php";
    if (file_exists($f2)) return $f2;
    $f2 = $this->langDir."/".$this->defaultLang."/$f.php";
    if (file_exists($f2)) return $f2;
    return "";
  }

  public function loadLangFile($f) {
    $f2 = $this->getLangFile($f);
    if (file_exists($f2)) {
      include_once($f2);
      $this->code2text = array_merge($this->code2text, $code2text);
      $this->loadedLangFiles[$f] = true;
      return true;
    }
    return false;
  }

  public function langCode2Text($code) {
    if (strpos($code, $this->metaStrings["langFilenameSeparator"]) === false) {
      return (isset($this->code2text[$code]) ?
        $this->code2text[$code] :
        ($code === "lang_missing_translation" ? "Error in language configuration" : langCode2Text("lang_missing_translation"))
      );
    } else {
      list($file, $code) = explode($this->metaStrings["langFilenameSeparator"], $code, 2);
      if ($file !== "" && $code === "") {
        $fname = $this->getLangFile($file);
        if (!empty($fname) && file_exists($fname))
          return file_get_contents($fname);
        else
          return $this->langCode2Text("lang_missing_translation");
      } else {
        if ($file !== "" && !(isset($this->loadedLangFiles[$file]) && $this->loadedLangFiles[$file]))
          $this->loadLangFile($file);
        if (isset($this->code2text[$code]))
          return $this->code2text[$code];
        else
          return $this->langCode2Text("lang_missing_translation");
      }
    }
  }

  public function returnWithError($error, $args = null) {
    $this->lastError = $error;
    $this->lastErrorArgs = (isset($args) ? $args : array());
    //echo "<pre>$error: ", print_r($args, true), "</pre>\n";
    return false;
  }

  public function getVarValue($name) {
    return $this->varsObj->getVarValue($name);
  }

  public function setVarValue($name, $val) {
    return $this->varsObj->setVarValue($name, $val);
  }

  public function checkCommandNames() {
    if (empty($this->metaStrings["blockStart"])) return "empty_block_start";
    if (empty($this->metaStrings["blockEnd"])) return "empty_block_end";
    if ($this->metaStrings["blockStart"] === $this->metaStrings["blockEnd"]) return "same_block_start_and_end";
    $this->lastErrorArgs = array();
    return "ok";
  }

  public function merge($val1, $val2) {
    if (is_array($val1) ? (count($val1) === 0) : $val1 === "") return $val2;
    if (is_array($val2) ? (count($val2) === 0) : $val2 === "") return $val1;
    # '@' added to avoid "Notice: Array to string conversion" with nested arrays
    if (is_array($val1))
      if (is_array($val2))
        return array_merge($val1, $val2);
      else
        return @implode(", ", $val1).$val2;
    else
      if (is_array($val2)) {
        return $val1.@implode(", ", $val2);
      } else
        return $val1.$val2;
  }

  public function errorTemplateNotFound($tpl) {
    # default error handler for $this->load()
  }

  public function setTPL($tpl, $code) {
    # set template from a given code
    $this->templates[$tpl] = array();
    $this->templates[$tpl]["code"] = $code;
    $this->templates[$tpl]["processed"] = false;
  }

  public function load($tpl, $reloadIfExists = true, $errorIfNotFound = true) {
    # load template
    if (isset($this->templates[$tpl]) && !$reloadIfExists) return true;
    $fname = $this->tplDir."/$tpl.".$this->tplExtension;
    if (file_exists($fname)) {
      # load
      $this->setTPL($tpl, file_get_contents($fname));
      return true;
    } elseif ($errorIfNotFound) {
      # not found
      $this->errorTemplateNotFound($tpl);
      return false;
    }
    return false;
  }

  public function parsethru($tpl, $variables = null) {
    echo $this->parse($tpl, $variables);
  }

  public function parse($tpl, $variables = null, $errorIfNotFound = true) {
    if (!is_array($tpl)) $tpl = array($tpl);
    $out = "";
    foreach ($tpl as $t) {
      if (!isset($this->templates[$t])) $this->load($t);
      if (!isset($this->templates[$t])) continue;
      # get from cache
      $code = $this->templates[$t]["code"];
      $processed = $this->templates[$t]["processed"];
      $out = $this->merge($out, $processed ? $code : $this->parseTPLexec($code, $variables));
    }
    return $out;
  }

  protected function errorParams($text, $pos, $extra = null) {
    $op = -1;
    $row = 1;
    while(1) {
      $np = strpos($text, "\n", $op + 1);
      if ($np === false || $np > $pos) break;
      ++$row;
      $op = $np;
    }
    $res = array(
      "row" => $row,
      "col" => $pos - $op + 1,
      "pos" => $pos,
    );
    if (isset($extra) && is_array($extra)) $res = array_merge($res, $extra);
    return $res;
  }

  # prepare for parsing the given text
  public function parseTPL($text) {
    $this->parsingDepth = 0;
    $this->localVars = array();
    $res = $this->parseTPLexec($text); 
    if ($this->lastError !== "ok") {
      echo "<pre>".$this->lastError;
      print_r($this->lastErrorArgs); 
      echo "</pre>\n\n";
    }
    return $res;
  }

  # parse the given text
  public function parseTPLexec($text, $variables = null, $newDepth = 0) {
    //echo "<pre style=\"background-color: #ffffc0; padding: 7px; border: 1px solid maroon;\">$newDepth, variables = "; print_r($variables); echo "</pre>";
    if (is_array($text)) return $text;
    $this->lastError = $this->checkCommandNames();
    if ($this->lastError != "ok") return false;
    $pos = 0;
    $textLen = strlen($text);
    $out = "";
    $oldDepth = $this->parsingDepth;
    $this->parsingDepth = $newDepth;
    $this->localVars[] = (isset($variables) && is_array($variables) ? $variables : array());
    //if (isset($variables) && $variables) echo "<pre>vars: ", print_r($variables, true), "</pre>\n";
    $sp = $this->metaStrings['stopParsing'];
    $spl = strlen($sp);
    while ($pos < $textLen) {
      if ($pos && substr($text, $pos, $spl) == $sp) $pos += $spl;
      $res = $this->getNext($text, $pos);
      if (!$res) {
        $this->parsingDepth = $oldDepth;
        array_pop($this->localVars);
        return false;
      }
      $out = $this->merge($out, $this->strip($res[0]));
      if (empty($res[1])) continue;
      $res = $this->command($text, $pos, $res[1]);
//      echo "<pre style=\"background-color: #c0ffff; padding: 7px; border: 1px solid maroon;\">$res</pre>";
      if ($res === false && $this->lastError !== "ok") {
        $this->parsingDepth = $oldDepth;
        array_pop($this->localVars);
        return false;
      }
      $out = $this->merge($out, $res);
    }
    $this->parsingDepth = $oldDepth;
    array_pop($this->localVars);
    return $out;
  }

  public function strip($text) {
    $esc = $this->metaStrings["escape"];
    $escLen = strlen($esc);
    $esc2 = "$esc$esc";
    $escLen2 = 2 * $escLen;
    $res = "";
    $pos = 0;
    while (1) {
      $newPos = strpos($text, $esc, $pos);
      if ($newPos === false) return $res.substr($text, $pos);
      if (substr($text, $newPos, $escLen2) == $esc2) {
        $res .= substr($text, $pos, $newPos - $pos + $escLen);
        $pos = $newPos + $escLen2;
      } else {
        $isCmd = false;
        foreach ($this->metaStrings as $ms => $code)
          if (substr($text, $newPos, $escLen + strlen($ms)) == "$esc$ms") {
            $isCmd = true;
            break;
          }
        $res .= substr($text, $pos, $newPos - $pos + ($isCmd ? $escLen : 0));
        $pos = $newPos + $escLen;
      }
    }
  }
  
  # Helper for getNext
  private function escCnt($activePos, $esc, $escLen, &$text) {
    $escCnt = 1;
    while ($activePos >= $escCnt * $escLen && substr($text, $activePos - $escCnt * $escLen, $escLen) === $esc) ++$escCnt;
    return $escCnt - 1;
  }

  # find the next block, update $pos to the point after the block and return the array of the text before the block and of the content of the block itself
  # return false and update $lastError if the next block was not found
  # connected blocks: {block1}{block2}
  # unconnected blocks: {block1} {block2}, {block1}|||{block2}
  public function getNext($text, &$pos, $connected = false) {
    $esc = $this->metaStrings["escape"];
    $escLen = strlen($esc);
    $blockStart = $this->metaStrings["blockStart"];
    $blockStartLen = strlen($blockStart);
    $blockEnd = $this->metaStrings["blockEnd"];
    $blockEndLen = strlen($blockEnd);
    $blockMetaStop = $this->metaStrings["stopParsing"];
    $blockMetaStopLen = strlen($blockMetaStop);
    $predefinedVarsPrefix = $this->metaStrings["predefinedVarsPrefix"];
    $originalPos = $activePos = $pos;
    # find next start (return text and false if there is none)
    if ($connected) {
      if (substr($text, $activePos, $blockMetaStopLen) == $blockMetaStop) {
        $pos = strlen($text);
        return array(substr($text, $originalPos + $blockMetaStopLen), false);
      }
      if (!(
            # found (unescaped) at position $activePos
            (substr($text, $activePos, $blockStartLen) === $blockStart) &&
            ($this->escCnt($activePos, $esc, $escLen, $text) % 2 == 0)
         )) { $pos = strlen($text); return array(substr($text, $originalPos), false); }
    } else {
      --$activePos;
      do {
        ++$activePos;
        if (($activePos = strpos($text, $blockStart, $activePos)) === false)
          { $pos = strlen($text); return array(substr($text, $originalPos), false); }
      } while ($this->escCnt($activePos, $esc, $escLen, $text) % 2);
    }
    # find next end
    $start = $activePos; // $start must not be increased beacuse of error messages
    $activePos += $blockStartLen;
    $spos = $epos = 0; // start, end
    $depth = 1;
    do {
      if ($spos !== false && $spos < $activePos) {
        $spos = $activePos - 1;
        do {
          $spos = strpos($text, $blockStart, $spos + 1);
        } while (($spos !== false) && ($this->escCnt($spos, $esc, $escLen, $text) % 2));
      }
      if ($epos < $activePos) {
        $epos = $activePos - 1;
        do {
          $epos = strpos($text, $blockEnd, $epos + 1);
        } while (($epos !== false) && ($this->escCnt($epos, $esc, $escLen, $text) % 2));
      }
      if ($epos === false) return $this->returnWithError("missing_block_end", $this->errorParams($text, $start)); // error message
      if ($spos === false || $spos > $epos) {
        $activePos = $epos + $blockEndLen;
        if (!--$depth) break;
      } else {
        $activePos = $spos + $blockStartLen;
        ++$depth;
      }
    } while(1);
    $pos = $activePos;
    $textBeforeBlock = substr($text, $originalPos, $start - $originalPos);
    $textInBlock = substr($text, $start + $blockStartLen, $pos - ($start + $blockStartLen + $blockEndLen));
/*    if (isset($this->varsObj)) {
      $trimBefore = $trimAfter = !$this->varsObj->getVarValue($predefinedVarsPrefix."noTrim");
      $trimBefore &= !$this->varsObj->getVarValue($predefinedVarsPrefix."noTrimBefore");
      $trimAfter &= !$this->varsObj->getVarValue($predefinedVarsPrefix."noTrimAfter");
    } else
      $trimBefore = $trimAfter = true;
    //echo "<pre>vars: ", print_r($this->variables, true), "</pre>\n";
    if ($trimBefore && isset($this->metaStrings["trimBefore"])) {
      $tb = $this->metaStrings["trimBefore"];
      if (count($tb) == 2)
        $textBeforeBlock = preg_replace($tb[0], $tb[1], $textBeforeBlock);
    }
    if ($trimAfter && isset($this->metaStrings["trimAfter"])) {
      $ta = $this->metaStrings["trimAfter"];
      if (strlen($textInBlock) && count($ta) == 2) {
        $oldLen = strlen($text);
        $newLen = strlen(preg_replace($ta[0], $ta[1], substr($text, $pos)));
        $pos = $oldLen - $newLen;
      }
    }*/
    return array(
      $textBeforeBlock,
      $textInBlock,
    );
  }

  # Parse arguments for command $name in $text, starting from position $pos
  # Get as many as possible, while avoid failing
  # For example, if even number of arguments is needed,
  #   {$name}{arg1}{arg2}{arg3}
  # will parse out only
  #   {$name}{arg1}{arg2}
  # leaving {arg3} to be processed separately
  public function command($text, &$pos, $name) {
    ++$this->parsingDepth;
    //echo "<pre>parsingDepth = '", $this->parsingDepth, "', name = '$name'</pre>\n";
    foreach ($this->extensions as $cmd) {
      if ($cmd->enabled === false) continue;
      //echo "<pre>$name, cmd = "; print_r($cmd); echo "</pre>\n";
      $textLen = strlen($text);
      $newPos = $pos;
      //echo "<pre>$name => ", get_class($cmd), "</pre>\n";
      if ($cmd->start($name)) {
        $args = array();
        while ($newPos < $textLen) {
          $more = $cmd->more($name, $args);
          if ($more == 0) break;
          $res = $this->getNext($text, $newPos, true);
//          echo "<pre style=\"border: 1px solid #00a000; background-color: #c0ffc0; padding: 7px;\">[$pos] $name, res[1] = '".($res === false ? "FALSE" : $res[1])."'\nargs = "; print_r($args); echo "</pre>\n";
          if ($res === false || count($res) < 2 || $res[1] === false)
            if ($more == 2) {
              if (isset($lastOk) && is_array($lastOk) && count($lastOk) == 2) {
                $pos = $lastOk[0];
                array_splice($args, $lastOk[1]);
                break;
              }
              --$this->parsingDepth;
              return $this->returnWithError("not_enough_parameters", $this->errorParams($text, $pos, array("cmd" => $name)));
            } else
              break;
          if ($more == 1) $lastOk = array($pos, count($args));
          //$res[1] = $this->strip($res[1]);
          $args[] = ($cmd->autoprocessArg($name, $args, $res[1]) ? $this->parseTPLexec($res[1], null, $this->parsingDepth + 1) : $res[1]);
          $pos = $newPos;
        }
        //echo "<pre>[$pos] $name, args = "; print_r($args); echo "</pre>\n";
        $res = $cmd->process($name, $args);
        ##############################################
        ############ dodaj error handling ############
        ##############################################
        --$this->parsingDepth;
        return $res;
      }
    }
    --$this->parsingDepth;
    return $this->returnWithError("unknown_command", $this->errorParams($text, $pos, array("cmd" => $name)));
  }

}

abstract class vsTPLextensionBase {

  public $tpl;
  public $enabled = true;

  function __construct(&$parent, $params = null) {
    if (!isset($params)) $params = array();
    if (isset($parent)) $this->tpl =& $parent; else die("No parent given calling TPLcmd class '".get_class($this)."'");
    if (is_a($parent, 'vsTPL')) $this->tpl = $parent; else die("Parent of '".get_class($this)."' is not a subclass of 'vsTPL'");
  }

  public function init() {
  }

  public function processArg($arg) {
    return $this->tpl->parseTPLexec($arg, null, $this->tpl->parsingDepth + 1);
  }

  public function processArgs($args) {
    if (is_array($args)) {
      $res = array();
      foreach ($args as $a)
        $res[] = $this->processArg($a);
      return $res;
    }
    return array($this->processArg($args));
  }

  # return true if you want parser to send parsed arguments to more() and process(); otherwise, return false
  public function autoprocessArg($cmd, $args, $newArg) {
    return true;
  }

  # Initialize processing of the command $cmd and return true if $cmd can be handled; otherwise, return false
  abstract public function start($cmd);

  # Return 0 if no more arguments can be used, 1 if more arguments can be handled but are not needed or 2 if more arguments are mandatory
  # $args is the list of already collected arguments
  abstract public function more($cmd, $args);

  # Process collected parts
  abstract public function process($cmd, $args);

}


#######################################################################
# ZA ISPRAVITI:                                                       #
# Gdje god pise $obj->getVarValue(...) treba vidjeti je l' ide to ili #
# $obj->tpl->parseTPLexec(...). Prvo ide ako treba varijabla, a drugo #
# ako se trazi vrijednost (pa, osim varijable, moze ici i izraz).     #
#######################################################################

class vsTPLextensionVariables extends vsTPLextensionBase {

  public $variables = array(); // associative array of variables
  public $localVars = array(); // array of associative arrays of local variables
  public $blocks = array(); // stack of array variables for {var}...{/var} blocks
  public $default = ""; // value returned when a non-existing variable is read
  const null = null;

  # scope is how many levels outside of the active block is some variable defined
  # use {dump}{<}{a}{b} to writeout the value of a<b, BUT
  # use {dump}{group}{{<}{a}{b}{c}} to writeout the value of a<b<c
  #   {dump} takes only minimal number of arguments to make
  #   {dump}{...}{something unrelated to dump}
  #   possible

  ##########################################################################################
  # Q: Should {+}{a}{b} and likes be parsed and treated as {+}{{a}}{{b}}?                  #
  # A: NO! Would make {<}{{arr}{i}}{something} impossible (for literal value "something")! #
  ##########################################################################################

  public $operators = array( // operators (properties: alias OR minargs, maxargs, code, func)

    # To add debugging info:
    # s/'\([^']\+\)\(.*'code' => '\)/'\1\2echo "<pre>RT op \\"\1\\"<\/pre>\\n"; /

    # make variables local
    'my' => array('minargs' => 1, 'code' => 'foreach ($args as $a) { $var = $a; if (!isset($obj->localVars[count($obj->localVars)-1][$var])) $obj->localVars[count($obj->localVars)-1][$var] = ""; } return "";'),
    'local' => array('alias' => 'my'),

    # {group}{...} to prevent unwanted output and to separate {a}{b}{c} to {group}{{a}{b}}{c}
    'group' => array('minargs' => 1, 'maxargs' => 1, 'returnDepth' => 2, 'code' => 'return $args[0];'),
    # {noTrim}{...} process without trimming
    'noTrim' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = $obj->tpl->parseTPLexec($a, array($obj->tpl->metaStrings["predefinedVarsPrefix"]."noTrim" => true)); return (count($res) == 1 ? $res[0] : $res);'),
    # {noTrimBefore}{...} process without trimming
    'noTrimBefore' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = $obj->tpl->parseTPLexec($a, array($obj->tpl->metaStrings["predefinedVarsPrefix"]."noTrimBefore" => true)); return (count($res) == 1 ? $res[0] : $res);'),
    # {noTrimAfter}{...} process without trimming
    'noTrimAfter' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = $obj->tpl->parseTPLexec($a, array($obj->tpl->metaStrings["predefinedVarsPrefix"]."noTrimAfter" => true)); return (count($res) == 1 ? $res[0] : $res);'),

    # return (y1, y2,..., y[n-1]), where yi equals xi if xi is defined; otherwise yi is xn
    'default' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array(); $val = array_pop($args); foreach ($args as $a) $res[] = ($obj->varDefined($a) ? $obj->getVarValue($a) : $val); return (count($res) == 1 ? $res[0] : $res);'),
    # return (y1, y2,..., y[n-1]), where yi equals xn if xi is empty; otherwise yi is xi
    'empty' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array(); $val = array_pop($args); foreach ($args as $a) { $varValue = $obj->getVarValue($a); $res[] = (empty($varValue) ? $val : $obj->getVarValue($a)); } return (count($res) == 1 ? $res[0] : $res);'),
    # isset(x1) && isset(x2) && ...
    'defined' => array('minargs' => 1, 'returnDepth' => 0, 'autoProcess' => 'return false;', 'code' => 'foreach ($args as $a) if (!$obj->varDefined($a)) return false; return true;'),

    # return x1 if x1 is defined and is in scope x2; otherwise return x3; default x2=0 (strictly local variable)
    'iflocal' => array('minargs' => 2, 'maxargs' => 3, 'returnDepth' => 0, 'code' => '$name = $args[0]; $scope = (count($args) < 3 ? 0 : $args[1]); $val = $args[count($args) - 1]; return ($obj->varLocal($name, $scope) ? $obj->getVarValue($name) : $val);'),
    'debug' => array('minargs' => 1, 'autoProcess' => 'return true;', 'returnDepth' => 0, 'code' => '$res = "<pre>"; foreach ($args as $i => $a) { $res .= "debug($i) = ".print_r($a, true)."\n"; } $res .= "</pre>\n"; return $res;'),

    # x1=x2=...=xn
    '=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$val = $obj->processArg(array_pop($args)); foreach ($args as $a) $obj->setVarValue($a, $val); return $val;'),
    'assign' => array('alias' => '='),
    # (int)x1, (int)x2,..., (int)xn
    'int' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = intval($obj->getVarValue($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # x1=(int)x1, x2=(int)x2,..., xn=(int)xn
    'toint' => array('minargs' => 1, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); foreach ($args as $a) $res[] = $obj->setVarValue($a, intval($obj->getVarValue($a))); return (count($res) == 1 ? $res[0] : $res);'),

    # array(x1, x2,..., xn)
    'array' => array('returnDepth' => 0, 'code' => 'return $args;'),
    # array(x1, x1+x3,..., x1+n*x3), x1+n*x3<=x2, if x3 > 0 (empty if x1 > x2)
    # array(x1, x1+x3,..., x1+n*x3), x1+n*x3>=x2, if x3 < 0 (empty if x1 < x2)
    # array(x1, x2), if x3 = 0
    # x3 is 1 if only two args are given
    'range' => array('minargs' => 2, 'maxargs' => 3, 'returnDepth' => 0, 'code' => 'if (count($args) < 3) $args[] = 1; list($f, $t, $inc) = $args; if ($inc == 0) return array($f, $t); $res = array(); $i = $f; $sign = ($inc > 0 ? 1 : -1); while ($i*$sign <= $t*$sign) { $res[] = $i; $i += $inc;} return $res;'),
    # array(count(x1), count(x2),..., count(xn)), if n > 1
    # count(x1), if n = 1
    # count is 1 for each xi that is not an array (treated as array(xi))
    'count' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = (is_array($a) ? count($a) : 1); return (count($res) == 1 ? $res[0] : $res);'),
    # array_merge(explode(x1, x2), explode(x1, x3),..., explode(x1, xn))
    'explode' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$sep = array_shift($args); $res = array(); foreach ($args as $a) $res[] = explode($sep, $a); return $obj->array_flatten($res);'),
    # array_merge(preg_split(x1, x2), preg_split(x1, x3),..., preg_split(x1, xn))
    # for only 1 arg: preg_split('#,\s*#', x1)
    'split' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$sep = (count($args) < 2 ? "#,\s*#" : array_shift($args)); $res = array(); foreach ($args as $a) $res[] = preg_split($sep, $a); return $obj->array_flatten($res);'),
    # implode(x1, array(x2, x3,..., xn))
    'implode' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$sep = array_shift($args); return implode($sep, $obj->array_flatten($args));'),
    # x1[x2 % count(x1)]
    'circ' => array('minargs' => 2, 'maxargs' => 2, 'returnDepth' => 0, 'code' => '$val = $obj->tpl->parseTPLexec($args[0]); if (is_array($val)) return $val[$obj->tpl->parseTPLexec($args[1]) % count($val)]; return $obj->tpl->returnWithError("array_expected", array("cmd" => "circ"));'),
    # array_flatten(x1, x2,..., xn)
    'flat' => array('minargs' => 1, 'returnDepth' => 0, 'code' => 'return $obj->array_flatten($args);'),
    # array_keys(x1, x2,..., xn)
    'keys' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) { $res = array_merge($res, array_keys($a)); } return $res;'),
    # array_values(x1, x2,..., xn)
    'values' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) { $res = array_merge($res, array_values($a)); } return $res;'),
    # like PERL's sort:
    # {sort}{array} = sort @array, using asort()
    ####### {sort}{criterion}{array} = sort {criterion} @array, using usort()
    # indices in criterion are {__a} and {__b}; array is {__array}, where '__' is $tpl->metaStrings['predefinedVarsPrefix']
    'sort' => array('minargs' => 1, 'maxargs' => 1, 'returnDepth' => 0, 'code' => '$arr = array_pop($args); if (!count($args)) { asort($arr, SORT_LOCALE_STRING); return $arr; } return $arr;'),
    # x1[] = x2, x1[] = x3,..., x1[] = xn
    'push' => array('minargs' => 2, 'returnDepth' => 2, 'code' => '$arr =& $obj->getVar($obj->processArg(array_shift($args))); foreach ($args as $a) $arr[] = $a; return $arr;'),
    # array_pop(x1)
    #'pop' => array('minargs' => 1, 'maxargs' => 2, 'returnDepth' => 2, 'code' => '$arr = array_pop($args); return $obj->array_flatten($args);'),
    # array_shift(x1, x2)
    #'shift' => array('minargs' => 1, 'returnDepth' => 2, 'code' => 'return $obj->array_flatten($args);'),
    # array_unshift(x1,...)
    #'unshift' => array('minargs' => 1, 'returnDepth' => 2, 'code' => 'return $obj->array_flatten($args);'),

    # min{x1,x2,...,xn}
    # if arrays are nested, they get flattened first
    'min' => array('minargs' => 1, 'returnDepth' => 0, 'code' => 'foreach ($args as $a) { $val = (is_array($a) ? $obj->operators["min"]["func"]($obj, $a) : $a); if (!isset($res) || $val < $res) $res = $val; } return $res;'),
    # max{x1,x2,...,xn}
    # if arrays are nested, they get flattened first
    'max' => array('minargs' => 1, 'returnDepth' => 0, 'code' => 'foreach ($args as $a) { $val = (is_array($a) ? $obj->operators["max"]["func"]($obj, $a) : $a); if (!isset($res) || $val > $res) $res = $val; } return $res;'),
    # x1+x2+...+xn
    # if xi is an array, its elements are recursively summed
    '+' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = 0; foreach ($args as $a) $res += (is_array($a) ? $obj->operators["+"]["func"]($obj, $a) : $a); return $res;'),
    # x1-x2-...-xn
    # if xi is an array, its elements are recursively summed
    '-' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array_shift($args); foreach ($args as $a) $res -= (is_array($a) ? $obj->operators["+"]["func"]($obj, $a) : $a); return $res;'),
    # x1*x2*...*xn
    # if xi is an array, its elements are recursively multiplied
    '*' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = 1; foreach ($args as $a) $res *= (is_array($a) ? $obj->operators["*"]["func"]($obj, $a) : $a); return $res;'),
    # x1/x2/.../xn
    # if xi is an array, its elements are recursively multiplied
    '/' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array_shift($args); foreach ($args as $a) $res /= (is_array($a) ? $obj->operators["*"]["func"]($obj, $a) : $a); return str_replace(",", ".", $res);'),
    # x1%xn, x2%xn,..., x[n-1]%xn
    # if xi is an array, % is recursively applied on it as well
    '%' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array(); $val = array_pop($args); foreach ($args as $a) if (is_array($a)) { $args[] = $val; $res[] = $obj->operators["%"]["func"]($obj, $a); } else $res[] = $a % $val; return (count($res) == 1 ? $res[0] : $res);'),
    # !x1, !x2,..., !xn
    # if xi is an array, ! is recursively applied on it as well
    '!' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$res = array(); foreach ($args as $a) $res[] = (is_array($a) ? $obj->operators["!"]["func"]($obj, $a) : $a ? false : true); return (count($res) == 1 ? $res[0] : $res);'),
    'not' => array('alias' => '!'),
    # x1||x2||...||xn
    '||' => array('minargs' => 1, 'returnDepth' => 2, 'code' => 'foreach ($args as $a) if ($a) return true; return false;'),
    'or' => array('alias' => '||'),
    # x1&&x2&&...&&xn
    '&&' => array('minargs' => 1, 'returnDepth' => 2, 'code' => 'foreach ($args as $a) if (!$a) return false; return true;'),
    'and' => array('alias' => '&&'),
    # x1==x2==...==xn
    '==' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$val = array_pop($args); foreach ($args as $a) if ($val != $a) return false; return true;'),
    # xi!=xj for all i!=j
    '!=' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 0; $i < $cnt; ++$i) for ($j = $i + 1; $j < $cnt; ++$j) if ($args[$i] == $args[$j]) return false; return true;'),
    # x1<x2<...<xn
    '<' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 1; $i < $cnt; ++$i) if ($args[$i-1] >= $args[$i]) return false; return true;'),
    # x1>x2>...>xn
    '>' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 1; $i < $cnt; ++$i) if ($args[$i-1] <= $args[$i]) return false; return true;'),
    # x1<=x2<=...<=xn
    '<=' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 1; $i < $cnt; ++$i) if ($args[$i-1] > $args[$i]) return false; return true;'),
    # x1>=x2>=...>=xn
    '>=' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 1; $i < $cnt; ++$i) if ($args[$i-1] < $args[$i]) return false; return true;'),
    # is_int(x1) && is_in(x2) && ...
    'is_int' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$cnt = count($args); for ($i = 0; $i < $cnt; ++$i) if (!is_int($args[$i])) return false; return true;'),

    # Are all x1,..., xn integers?
    'isint' => array('minargs' => 1, 'returnDepth' => 2, 'code' => 'foreach ($args as $a) if (!preg_match(\'#-?\\d+$#\', $a)) return false; return true;'),

    # x1 .= xn, x2 .= xn,...
    '.=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = $obj->processArg(array_pop($args)); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) . $val); return (count($res) == 1 ? $res[0] : $res);'),
    # x1 += xn, x2 += xn,...
    '+=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = $obj->processArg(array_pop($args)); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) + $val); return (count($res) == 1 ? $res[0] : $res);'),
    # ++x1, ++x2,..., ++xn
    '++' => array('minargs' => 1, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = 1; foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) + 1); return (count($res) == 1 ? $res[0] : $res);'),
    # x1 -= xn, x2 -= xn,...
    '-=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = $obj->processArg(array_pop($args)); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) - $val); return (count($res) == 1 ? $res[0] : $res);'),
    # --x1, --x2,..., --xn
    '--' => array('minargs' => 1, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) - 1); return (count($res) == 1 ? $res[0] : $res);'),
    # x1 *= xn, x2 *= xn,...
    '*=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = $obj->processArg(array_pop($args)); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) * $val); return (count($res) == 1 ? $res[0] : $res);'),
    # x1 *= xn, x2 *= xn,...
    '/=' => array('minargs' => 2, 'returnDepth' => 2, 'autoProcess' => 'return false;', 'code' => '$res = array(); $val = $obj->processArg(array_pop($args)); foreach ($args as $a) $res[] = $obj->setVarValue($a, $obj->getVarValue($a) / $val); return (count($res) == 1 ? $res[0] : $res);'),

    # x1 = min{x1, x2,..., xn}
    'min=' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$f = array_shift($args); $min = $obj->getVarValue($f); foreach ($args as $a) { $val = (is_array($a) ? $obj->operators["min"]["func"]($obj, $a) : $a); if ($val < $min) $min = $val; } $obj->setVarValue($f, $min); return $min;'),
    # x1 = max{x1, x2,..., xn}
    'max=' => array('minargs' => 1, 'returnDepth' => 2, 'code' => '$f = array_shift($args); $max = $obj->getVarValue($f); foreach ($args as $a) { $val = (is_array($a) ? $obj->operators["max"]["func"]($obj, $a) : $a); if ($val > $max) $max = $val; } $obj->setVarValue($f, $max); return $max;'),

    # htmlspecialchars
    'htmlspecialchars' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = htmlspecialchars($a); return (count($res) == 1 ? $res[0] : $res);'),
    # htmlentities
    'htmlentities' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = htmlentities($a); return (count($res) == 1 ? $res[0] : $res);'),
    # urlencode
    'urlencode' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = urlencode($a); return (count($res) == 1 ? $res[0] : $res);'),
    # addslashes
    'addslashes' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = addslashes($a); return (count($res) == 1 ? $res[0] : $res);'),
    # strlen
    'strlen' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = mb_strlen($obj->processArg($a), "utf8"); return (count($res) == 1 ? $res[0] : $res);'),
    # round
    'round' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = round($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # ceil
    'ceil' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = ceil($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # floor
    'floor' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = floor($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # format == sprintf(x[0], x[1],...)
    'format' => array('minargs' => 2, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) { $val = $obj->processArg($a); if (is_string($val)) $val = "\'".str_replace("\'", "\\\'", $val)."\'"; $res[] = $val; } $f = create_function(\'\', \'return sprintf(\'.implode(\', \', $res).\');\'); return $f();'),

    # ucfirst
    'ucfirst' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = ucfirst($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # lcfirst
    'lcfirst' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = lcfirst($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # ucwords
    'ucwords' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = ucwords($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # strtolower
    'strtolower' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = strtolower($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # strtoupper
    'strtoupper' => array('minargs' => 1, 'returnDepth' => 0, 'code' => '$res = array(); foreach ($args as $a) $res[] = strtoupper($obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # preg_replace
    'preg_replace' => array('minargs' => 3, 'maxargs' => 3, 'returnDepth' => 0, 'code' => '$res = array(); if (!is_array($args[2])) $args[2] = array($args[2]); foreach ($args[2] as $a) $res[] = preg_replace($args[0], $args[1], $obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),
    # array(str_replace(x[0], x[1], x[2]), str_replace(x[0], x[1], x[3]),...)
    'str_replace' => array('minargs' => 3, 'returnDepth' => 0, 'code' => '$res = array(); $search = array_shift($args); $replace = array_shift($args); foreach ($args as $a) $res[] = str_replace($search, $replace, $obj->processArg($a)); return (count($res) == 1 ? $res[0] : $res);'),

    # {time} -> time()
    # {time}{format} -> date(format)
    # {time}{formated}{time} -> date(format, time)
    'time' => array('minargs' => 0, 'maxargs' => 2, 'returnDepth' => 0, 'code' => 'if ($cnt = count($args)) return ($cnt == 1 ? date($args[0]) : date($args[0], $args[1])); else return time();'),
    'date' => array('alias' => 'time'),
  );

  # treba fja za poziv code/func!!!

  public function autoprocessArg($cmd, $args, $newArg) {
    while ($cmd === "dump" && count($args)) $cmd = array_shift($args);
    $opData = $this->getop($cmd, false);
    if ($opData === false) return true;
    if (isset($opData['autoProcess'])) {
      if (!isset($opData['apFunc']))
        $apFunc = $this->operators[$cmd]['apFunc'] = create_function('$obj, $args, $newArg', $opData['autoProcess']);
      else
        $apFunc = $this->operators[$cmd]['apFunc'];
      return $apFunc($this, $args, $newArg);
    }
    return true;
  }

  public function &getVar($name, $default = null) {
    $oldName = $name;
    $parts = array();
    if (substr($name, 0, strlen($this->tpl->metaStrings["blockStart"])) == $this->tpl->metaStrings["blockStart"]) {
      $pos = 0;
      $nameLen = strlen($name);
      while ($pos < $nameLen) {
        $res = $this->tpl->getNext($name, $pos, true);
        if ($res === false || count($res) < 2 || $res[1] === false) break;
        $parts[] = $this->tpl->parseTPLexec($res[1]);
      }
      $name = array_shift($parts);
    }
    $var = null;
    for ($i = count($this->localVars); $i >= 0; --$i)
      if (isset($this->localVars[$i][$name])) {
        $var =& $this->localVars[$i][$name];
        break;
      }
    if (!isset($var) && isset($this->variables[$name]))
      $var = &$this->variables[$name];
    if (isset($var)) {
      $cnt = count($parts);
      for ($i = 0; $i < $cnt; ++$i) {
        $p = $parts[$i];
        if (is_array($var)) {
          if (isset($var[$p]))
            $var = &$var[$p];
          else {
            $var[$p] = ($i < $cnt - 1 ? array() : $default);
            $var = &$var[$p];
          }
        } else {
          if (isset($var)) return $var;
          if ($defualt === null) return $this->null;
          $var = $default;
        }
      }
    }
    return $var;
  }

  public function varLocal($name, $scope = null) {
    $scope = (isset($scope) ? count($this->localVars) - $scope - 1 : 0);
    if ($scope < 0) return $this->varDefined($name);
    for ($i = count($this->localVars) - 1; $i >= $scope; --$i)
      if (isset($this->localVars[$i][$name]))
        return true;
    return false;
  }

  public function varDefined($name) {
    $var =& $this->getVar($name);
    return (isset($var) ? true : false);
  }

  public function getVarValue($name) {
    return $this->getVar($name);
  }

  public function setVarValue($name, $val) {
    $var =& $this->getVar($name, $val);
    if (isset($var)) return $var = $val;
    return $this->variables[$name] = $val;
  }

  public function array_flatten($a) {
    foreach ($a as $k => $v) $a[$k] = (array)$v;
    return call_user_func_array('array_merge', $a);
  }

  protected function getop($op, $reportError = true) {
    if (!isset($this->operators[$op]))
      return ($reportError ? $this->tpl->returnWithError("undefined_operator", array("op" => $op)) : false);
    $errOp = $op;
    while(1)
      if (isset($this->operators[$op]["alias"]))
        if (isset($this->operators[$op]["alias"]) && isset($this->operators[$this->operators[$op]["alias"]]))
          $this->operators[$op] = $this->operators[$errOp = $this->operators[$op]["alias"]];
        else
          return $this->tpl->returnWithError("wrong_operator_definition", array("op" => $errOp));
      else
        return $this->operators[$op];
  }

  protected function calculate($op, $args) {
    $opData = $this->getop($op);
    if ($opData === false) return false;
    if (!isset($opData['func']))
      $func = $this->operators[$op]['func'] = create_function('$obj, $args', $opData['code']);
    else
      $func = $this->operators[$op]['func'];
    $ret = $func($this, $args);
    return $ret;
  }

  public function autoload($vars) {
    if (!is_array($vars)) return false;
    foreach ($vars as $var => &$val)
      $this->variables[$var] =& $val;
    return true;
  }

  public function start($cmd) {
    return true;
  }

  public function more($cmd, $args) {
    if ($cmd === "dump") {
      $dump = true;
      while ($cmd === "dump" && count($args)) $cmd = array_shift($args);
      if (count($args) == 0) return 2;
    } else
      $dump = false;
    $opData = $this->getop($cmd, false);
    if ($opData !== false) {
      $cnt = count($args);
      if (isset($opData["minargs"]) && $cnt < $opData["minargs"]) return 2;
      if ($dump) return 0;
      if (isset($opData["maxargs"]) && $cnt >= $opData["maxargs"]) return 0;
      return 1;
    }
    return 1;
  }

  public function process($cmd, $args) {
    $dump = false;
    $cnt = count($args);
//    echo "<pre>cmd ='$cmd', parsingDepth = '", $this->tpl->parsingDepth, "', args = "; print_r($args); echo "</pre>";
    while ($cmd === "dump") {
      $cmd = array_shift($args);
      --$cnt;
      $dump = true;
    }
    $opData = $this->getop($cmd, false);
    if (!isset($opData['returnDepth']) || $this->tpl->parsingDepth >= $opData['returnDepth']) $dump = true;
    if ($opData !== false) {
      $ret = $this->calculate($cmd, $args);
      return ($dump ? $ret : "");
    }
    $val = $this->getVarValue($cmd);
//    echo "<pre style=\"background-color: #ffff80;\">$cmd: "; print_r($this->getVarValue($cmd)); echo "</pre>\n";
    if ($this->varDefined($cmd) && is_array($this->getVarValue($cmd)) && count($args) > 0) {
      foreach ($args as $a) {
        $a = $this->tpl->parseTPLexec($a);
        if (isset($val[$a])) $val = $val[$a]; else return $this->default;
//        echo "<pre style=\"background-color: #ffff80;\">$cmd ($a): "; print_r($val); echo "</pre>\n";
      }
      return $val;
    }
    return (isset($val) ? $val : $this->default);
  }

}

class vsTPLextensionBranches extends vsTPLextensionBase {

  # {if}{cond}{what if cond is true}
  # {unless}{cond}{what if cond is false}
  # {iff}{cond}{what if cond is true}{what if cond is false}

  protected $argsCnt = array("if" => 2, "unless" => 2, "iff" => 3);

  public function autoprocessArg($cmd, $args, $newArg) {
    return (count($args) == 0);
  }

  public function start($cmd) {
    return isset($this->argsCnt[$cmd]);
  }

  public function more($cmd, $args) {
    if (!isset($this->argsCnt[$cmd])) return $this->tpl->returnWithError("wrong_branch", array("cmd" => $cmd));
    return (count($args) < $this->argsCnt[$cmd] ? 2 : 0);
  }

  public function process($cmd, $args) {
    $val = $args[0];
    $val = (empty($val) || (is_array($val) && count($val) == 0) ? false : true);
    if ($cmd == "if") return $this->tpl->parseTPLexec(($val ? $args[1] : ""), array(), 0);
    if ($cmd == "unless") return $this->tpl->parseTPLexec(($val ? "" : $args[1]), array(), 0);
    if ($cmd == "iff") return $this->tpl->parseTPLexec(($val ? $args[1] : $args[2]), array(), 0);
    return $this->tpl->returnWithError("wrong_branch", array("cmd" => $cmd));
  }

}

class vsTPLextensionLoops extends vsTPLextensionBase {

  # {foreach}{array}{loopVar}{code}		= foreach (array as loopVar) code;
  # {foreach}{array}{loopKey}{loopValue}{code}	= foreach (array as loopKey => loopValue) code;
  # {map} - same as {foreach} but returns array instead of sending result to the output
  # {while}{cond}{code}
  # {dowhile}{code}{cond}
  # {until}{cond}{code}
  # {dountil}{code}{cond}
  # {for}{var}{from}{to}{code}
  # {for}{var}{from}{to}{step}{code}

  protected $argsCnt = array("foreach" => array(3, 4), "map" => array(3, 4), "while" => 2, "dowhile" => 2, "until" => 2, "dountil" => 2, "for" => array(4, 5));
  public $maxLoops = 0; // 0 for unlimited

  public function autoprocessArg($cmd, $args, $newArg) {
    return false;
  }

  public function start($cmd) {
    if (isset($this->argsCnt[$cmd])) return true;
    return false;
  }

  public function more($cmd, $args) {
    if (isset($this->argsCnt[$cmd])) {
      $cnt = count($args);
      $ac = $this->argsCnt[$cmd];
      if (is_array($ac)) {
        if ($cnt < $ac[0]) return 2;
        if ($cnt >= $ac[1]) return 0;
        return 1;
      } else
        return ($cnt < $ac ? 2 : 0);
    }
    return $this->tpl->returnWithError("wrong_loop", array("cmd" => $cmd));
  }

  public function process($cmd, $args) {
    $res = ($cmd === "map" ? array() : "");
    $prefix = $this->tpl->metaStrings['predefinedVarsPrefix'];
    if ($cmd === "for") {
      $var = $this->tpl->parseTPLexec($args[0]);
      $from = $this->tpl->parseTPLexec($args[1]);
      $to = $this->tpl->parseTPLexec($args[2]);
      $step = (count($args) == 4 ? 1 : $this->tpl->parseTPLexec($args[3]));
      $code = $args[count($args) - 1];
      if ($step == 0) return $this->tpl->returnWithError("wrong_loop_step", array("cmd" => $cmd));
      $idx = 0;
      $total = floor(($to - $from) / $step) + 1;
      for ($i = $from; $i <= $to; $i += $step) {
        $params = array(
          "${prefix}idx" => $idx,
          "${prefix}first" => ($i == $from ? true : false),
          "${prefix}last" => ($i + $step > $to ? true : false),
          "${prefix}middle" => ($i > $from && $i + $step <= $to ? true : false),
          "${prefix}total" => $total,
          "${prefix}ord" => ++$idx,
          $var => $i,
        );
        $res .= $this->tpl->parseTPLexec($code, $params, 0);
      }
    } elseif ($cmd === "foreach" || $cmd === "map") {
      $arr = $this->tpl->parseTPLexec($args[0]);
      if (!is_array($arr))
        return $this->tpl->returnWithError("arg1_not_array", array("cmd" => $cmd, "args" => $args));
      $cnt = count($arr);
      $last = $cnt - 1;
      $i = 0;
      $hasKey = (count($args) == 3 ? false : true);
      $code = array_pop($args);
      foreach ($arr as $key => $val) {
        $params = array(
          "${prefix}idx" => $i,
          "${prefix}first" => ($i == 0 ? true : false),
          "${prefix}last" => ($i == $last ? true : false),
          "${prefix}middle" => ($i > 0 && $i < $last ? true : false),
          "${prefix}total" => $cnt,
          "${prefix}ord" => ++$i,
        );
        if ($hasKey) {
          $params[$args[1]] = $key;
          $params[$args[2]] = $val;
        } else
          $params[$args[1]] = $val;
	if ($cmd === "foreach")
          $res .= $this->tpl->parseTPLexec($code, $params, 0);
        else
          $res[] = $this->tpl->parseTPLexec($code, $params, 0);
      }
    } elseif (isset($this->argsCnt)) {
      switch ($cmd) {
        case 'while': $not = false; $noCond = false; $cond = $args[0]; $code = $args[1]; break;
        case 'dowhile': $not = false; $noCond = true; $cond = $args[1]; $code = $args[0]; break;
        case 'until': $not = true; $noCond = false; $cond = $args[0]; $code = $args[1]; break;
        case 'dountil': $not = true; $noCond = true; $cond = $args[1]; $code = $args[0]; break;
        default: return $this->tpl->returnWithError("wrong_loop", array("cmd" => $cmd));
      }
      $i = 0;
      while ($noCond || ($this->processArg($cond) xor $not)) {
        $res .= $tmp = $this->tpl->parseTPLexec($code, array(
          "${prefix}idx" => $i,
          "${prefix}first" => ($i == 0 ? true : false),
          "${prefix}ord" => ++$i,
        ), 0);
        $noCond = false;
        if (isset($this->maxLoops) && $this->maxLoops && $i >= $this->maxLoops) break;
      }
    } else
      return $this->tpl->returnWithError("wrong_loop", array("cmd" => $cmd));
    return $res;
  }

}

class vsTPLextensionTPL extends vsTPLextensionBase {

  # {snippet}{name}{code} - save code as a template
  # {copy}{name}{code} - process code and save the result
  # {include}{name1}{arg1}{value1}...{argn}{valuen} - include templates name1,... namen
  # {lang}{code1}...{coden} - convert codes to language
  # {enable}{ext1}...{extn} - enable extensions
  # {disable}{ext1}...{extn} - disable extensions

  public function init() {
    if (isset($this->tpl->startupParams["snippetsTPL"]))
      $this->tpl->parse($this->tpl->startupParams["snippetsTPL"], null, false);
    else
      $this->tpl->parse("snippets", null, false);
  }

  public function autoprocessArg($cmd, $args, $newArg) {
    return ($cmd == "copy" || $cmd == "enable" || $cmd == "disable" ? true : false);
  }

  public function start($cmd) {
    if ($cmd == "snippet" || $cmd == "copy" || $cmd == "include" || $cmd == "lang" || $cmd == "enable" || $cmd == "disable") return true;
    return false;
  }

  public function more($cmd, $args) {
    if ($cmd == "snippet" || $cmd == "copy") return (count($args) < 2 ? 2 : 0);
    if ($cmd == "include") return (count($args) < 1 || count($args) % 2 == 0 ? 2 : 1);
    if ($cmd == "lang" || $cmd == "enable" || $cmd == "disable") return (count($args) < 1 ? 2 : 1);
    return $this->tpl->returnWithError("wrong_tpl_function", array("cmd" => $cmd));
  }

  public function mkArgs(&$vars) {
    $res = array();
    foreach ($vars as $var => &$val)
      $res[$var] =& $val;
    $vars[$this->tpl->metaStrings['predefinedVarsPrefix']."args"] =& $res;
  }

  public function process($cmd, $args) {
    if ($cmd == "snippet" || $cmd == "copy") {
      $this->tpl->templates[$args[0]] = array();
      $this->tpl->templates[$args[0]]["code"] = ($cmd == "copy" ? $this->tpl->parseTPLexec($args[1], array(), 0) : $args[1]);
      $this->tpl->templates[$args[0]]["processed"] = ($cmd == "copy" ? true : false);
      return "";
    }
    if ($cmd == "include") {
      $tpl = array_shift($args);
      $vars = array();
      $cnt = count($args);
      for ($i = 0; $i < $cnt; $i += 2)
        $vars[$args[$i]] = $this->tpl->parseTPLexec($args[$i+1]);
      $this->mkArgs($vars);
      return $this->tpl->parse($tpl, $vars);
    }
    if ($cmd == "lang") {
      $res = "";
      foreach ($args as $a)
        $res .= $this->tpl->langCode2Text($a);
      return $res;
    }
    if ($cmd == "enable" || $cmd == "disable") {
      $tmp = array();
      foreach ($args as $a) $tmp[$a] = true;
      if ($cmd == "enable") {
        foreach ($this->tpl->extensions as &$ext) {
          $class = get_class($ext);
          $enbLen = strlen($this->tpl->extensionNameBase);
          if (substr($class, 0, $enbLen) !== $this->tpl->extensionNameBase) return $this->tpl->returnWithError("invalid_extension_class", array("ext" => $class));
          $t = substr($class, $enbLen);
          if (isset($tmp[$t]) && $tmp[$t]) {
            $ext->enabled = true;
            $tmp[$t] = false;
          }
        }
        foreach ($tmp as $key => $t)
          if ($t && !($this->tpl->autoloadExtensions && $this->tpl->loadExtension($key))) return $this->tpl->returnWithError("nonexistent_extension", array("ext" => $key));
        return "";
      }
      if ($cmd == "disable") {
        foreach ($this->tpl->extensions as &$ext) {
          $class = get_class($ext);
          $enbLen = strlen($this->tpl->extensionNameBase);
          if (substr($class, 0, $enbLen) !== $this->tpl->extensionNameBase) continue;
          $t = substr($class, $enbLen);
          if (isset($tmp[$t]) && $tmp[$t]) $ext->enabled = false;
        }
        return "";
      }
    }
    return $this->tpl->returnWithError("wrong_tpl_function", array("cmd" => $cmd));
  }

}

class vsTPLextensionBasic extends vsTPLextensionBase {

  public function start($cmd) {
  }

  public function more($cmd, $args) {
  }

  public function process($cmd, $args) {
  }

}

?>
