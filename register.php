<?php

require("common.php");

function fixit($str) {
  $str = stripslashes($str);
  $str = mysql_real_escape_string($str);

  return $str;
}

outHead();

$sql = db_connect();

if( $sql ) {
	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$position = $_POST["position"];
	$institution = $_POST["institution"];
	$school = $_POST["school"];
	$email = $_POST["email"];
	$pass = $_POST["pass"];
	$pass2 = $_POST["pass"];

	$name = fixit($name);
	$surname = fixit($surname);
	$position = fixit($position);
	$institution = fixit($institution);
	$school = fixit($school);
	$email = fixit($email);
	$pass = fixit($pass);
	$pass2 = fixit($pass2);

	if( strlen($name) < 1 ) {
		print("<h2>Please enter your name. Try again</h2>");
		outFoot();
		return;
	}
	if( strlen($surname) < 1 ) {
		print("<h2>Please enter your surname. Try again</h2>");
		outFoot();
		return;
	}
	if( strlen($institution) < 1 ) {
		print("<h2>Please enter your institution name. Try again</h2>");
		outFoot();
		return;
	}
	if( strlen($email) < 1 ) {
		print("<h2>Please enter your e-mail address. Try again</h2>");
		outFoot();
		return;
	}
	if( strlen($pass) < 6 ) {
		print("<h2>Password has to be at least 6 characters long. Try again</h2>");
		outFoot();
		return;
	}

	if( strcmp($pass,$pass2) != 0 ) {
		print("<h2>The passwords do not match. Try again</h2>");
		outFoot();
		return;
	}

	$check = mysql_query("SELECT password FROM members WHERE email = '$email' LIMIT 1",$sql);
	$check2 = mysql_num_rows($check);

	if ($check2 > 0) {
		print("<h2>This email address already exists in our database.<h2>");
		outFoot();
		return;
	}
	
	if (!isUomAddress($email)) {
	    print("<h2>Please use your Univeristy of Manchester email address..<h2>");
	    outFoot();
	    return;
	}

	$encpass = crypt(md5($pass),md5($email));
	$encpass2 = crypt(md5($encpass),md5($email));

	if( mysql_query("INSERT INTO members (`email`, `firstname`, `lastname`, `position`, `institution`, `school`, `password`) VALUES ('$email', '$name', '$surname', '$position', '$institution', '$school', '$encpass');",$sql) ) {
		if( mail ( $email , "Manchester SIAM Student Chapter Registration" , "Please follow this link to finish your registration process:\nhttp://www.maths.manchester.ac.uk/~siam/activate?email=$email&code=$encpass2\n\nThank you.\n" ) ) {
			print("<h2>Your registration has been successfuly processed. An account activation code has been sent to your e-mail address. Please follow the link in the mailbox to finish your registration.<h2>");
		} else {
			print("Sending of activation e-mail has failed.");
		}
	} else {
		print("There was an error registering you, sorry. Try again later.");
	}

} else {
	print("<h1>Database down. Sorry.</h1>");
}

outFoot();

?>
