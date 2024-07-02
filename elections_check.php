<?php

require("common.php");

if (isset($_POST["email"]) && isset($_POST["pass"])) {
  $email = stripslashes($_POST["email"]);
  $pass = stripslashes($_POST["pass"]);

  $pass = ep2crypt($email, $pass);
  
  // Did they vote already?
  global $userData;
  $db = db_connect();
  $query = mysql_query("SELECT pkm, tcreated FROM members WHERE email='".mysql_real_escape_string($email)."' and password='".mysql_real_escape_string($pass)."' LIMIT 1", $db);
  $userData = mysql_fetch_assoc($query);
  if ($userData)
    {
      if (strtotime($userData["tcreated"]) > strtotime('2015-10-29 00:00:00'))
	{
	  outHead();
	  print("<h2>Members who registered after 2015-10-28</br>cannot take part to this election.</h2>");
	  outFoot();
	}
      else
	{
	  $userData["pkm"];
	  $query = mysql_query("SELECT COUNT(*) AS number FROM voters WHERE voter_pkm='".$userData["pkm"]."'", $db);
	  $count = mysql_fetch_assoc($query);
	  $pip = $count["number"];
	  if ($count["number"] == 1)
	    {
	      // Already voted
	      outHead();
	      print("<h2>You have voted already. Thank you!</h2>");
	      outFoot();
	    }
	  else
	    {
	      $preference = $_POST["candidate"];
	      if ($preference == "")
		{
		  outHead();
		  print("<h2>Please select one of the candidates. Thank you!</h2>");
		  outFoot();
		}
	      else
		{
		  $query = mysql_query("INSERT INTO ".$preference." VALUES ('|')", $db);
		  $query = mysql_query("INSERT INTO voters VALUES (".$userData["pkm"].")", $db);
		  outHead();
		  print("<h2>Your vote has been registered. Thank you!</h2>");
		  outFoot();
		}
	    }
	} 
    }
  else
    {
      outHead();
      print("<h2>Wrong email and/or password.</br>Are you a member of the Chapter?</h2>");
      outFoot();
    }
}

?>
