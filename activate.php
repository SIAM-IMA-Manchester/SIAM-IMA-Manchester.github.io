<?php

include_once('common.php');

function fixit($str) {
  $str = stripslashes($str);
  $str = mysql_real_escape_string($str);

  return $str;
}

outHead();

$sql = db_connect();

if( $sql ) {
	$email = $_GET["email"];
	$code = $_GET["code"];

	$email = fixit($email);
	$code = fixit($code);

	$check = mysql_query("SELECT password FROM members WHERE email = '$email' LIMIT 1",$sql);
	$check2 = mysql_num_rows($check);

	if ($check2 > 0) {
		$db_field = mysql_fetch_assoc($check);
		$pass = $db_field['password'];
		$encpass = crypt(md5($pass),md5($email));

		if( strcmp( $code, $encpass ) == 0 ) {
			if( mysql_query("UPDATE  `members` SET  `activated` =  '1' WHERE  `members`.`email` = '$email' LIMIT 1",$sql) ) {
				print("<h2>Your account has been successfuly activated. You can now log in.<h2>");
			} else {
				print("<h2>There was an error processing your activation.<h2>");
			}
		} else {
			print("<h2>Wrong activation code.<h2>");
		}
	} else {
		print("<h2>This email address does not exist in our database.<h2>");
		outFoot();
		return;

	}
} else {
	print("<h1>Database down. Sorry.</h1>");
}

outFoot();

?>
