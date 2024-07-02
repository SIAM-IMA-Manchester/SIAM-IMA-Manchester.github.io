<?php

require("common.php");

if (isset($_POST["email"]) && isset($_POST["pass"])) {
  $email = stripslashes($_POST["email"]);
  $pass = stripslashes($_POST["pass"]);

  $pass = ep2crypt($email, $pass);
  
  if (login($email, $pass)) {
    $_SESSION['SIAM_manchester_login'] = $email;
    $_SESSION['SIAM_manchester_pass'] = $pass;
    forwardToPostField();
    updateLoggedIn();
    outHead();
    print("<h2>Login successful.</h2>");
    outFoot();
  }
}

logout();

outHead();
print("<h2>Login failed.</h2>");
outFoot();

?>
