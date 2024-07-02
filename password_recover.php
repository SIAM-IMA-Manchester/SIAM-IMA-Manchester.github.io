<?php

require("common.php");

if (isset($_POST["email"])) {
  $email = stripslashes($_POST["email"]);
  global $userData;
  $db = db_connect();
  $query = mysql_query("SELECT pkm FROM members WHERE email='".mysql_real_escape_string($email)."' LIMIT 1", $db);
  $userData = mysql_fetch_assoc($query);
  if ($userData) {
    // Generate and update password
    $newpassword = substr(md5(microtime()), rand(0,26), 5);
    $encpasswd = ep2crypt($email, $newpassword);
    $query = mysql_query("UPDATE members SET password='".mysql_real_escape_string($encpasswd).
    			 "' WHERE pkm='".$userData["pkm"]."'", $db);
    // Send new password by email
    mail($email, "[Manchester SIAM Student Chapter] New login information",
	 "Your new password to access the website of the Chapter is ".$newpassword.".");
    outHead();
    print("<h2>Recovery instructions have been sent<br/>to the mail address you specified.</h2>");
    outFoot();
  } else {
    outHead();
    print("<h2>The email address you entered does not appear<br/>to belong to a member of the Chapter.</h2>");
    outFoot();
  }
} else {
  outHead();
  print("<h2>You need to insert a valid email address<br/>to get your password recovered.</h2>");
  outFoot();
}

?>
