<?php

/*****************************************************************
 *                                                               *
 * SimplePerms -- PHP class for handling simple user permissions *
 *                                                               *
 * Developed 2013. by Vedran Sego <vsego@vsego.org>              *
 *                                                               *
 *****************************************************************/

class SimplePerms {

  public $userPerms = array();
  public $userPermsImply = array();

  public function __construct($userPerms = null, $userPermsImply = null, $permsField = "perms") {
    $this->userPerms = (isset($userPerms) ? $userPerms : array("Member" => "0", "Admin" => "1"));
    $this->userPermsImply = (isset($userPermsImply) ? $userPermsImply : array(
      "Admin" => array_keys($this->userPerms),
    ));
    $this->userPermsInv = array_flip($this->userPerms);
    $this->permsField = $permsField;
  }

  public function hasExactPerm($user, $perm = "Admin") {
    if (!isset($user)) return false;
    if (is_array($user))
      if (isset($user[$this->permsField]))
        $user = $user[$this->permsField];
      else
        return false;
    return ($user === $this->userPerms[$perm]);
  }

  function hasPerm($user, $perm = "Admin") {
    if (!isset($user)) return false;
    if (!isset($this->userPerms[$perm])) return false;
    if (is_array($user))
      if (isset($user[$this->permsField]))
        $user = $user[$this->permsField];
      else
        return false;
    if ($user == $this->userPerms[$perm]) return true;

    $user = $this->userPermsInv[$user];
    if (is_array($this->userPermsImply[$user])) {
      $checked = array($perm => true);
      $implied = $this->userPermsImply[$user];
      while ($implied) {
        $imp = array_pop($implied);
        if (isset($checked[$imp])) continue;
        if ($user === $imp) return true;
        $checked[] = $imp;
        if (isset($this->userPermsImply[$imp]))
          $implied = array_merge($this->userPermsImply[$imp], $implied);
      }
    }
    return false;
  }

}

?>
