<?php

//ini_set("display_errors", "yes");
require_once("conferences.php");

$conferenceData = array(
  "code" => "amsscc15",
  "dbh" => dbhConnect(),
  "deadline: Registration" => "2015-04-29",
  //"deadline: Abstract and poster submission" => "2015-04-17",
  //"debug" => true,
  "email" => "Manchester SIAM Student Chapter <siam@manchester.ac.uk>",
  "header" => "Friday, 1 May 2015.<br />\nAlan Turing Building<br />\nUniversity of Manchester",
  "membersCSS" => "bordered".(hasPerm("Events organizer") ? " hover" : ""),
  "role" => (hasPerm("Events organizer") ? vsRegs::ROLE_ADMIN : vsRegs::ROLE_LIST),
  "shortTitle" => "AMSSCC15",
  "title" => "Manchester SIAM Student Chapter<br />Conference 2015 (MSSCC15)",
  "uploadsDir" => "../files/uploads/amsscc15",
);

outHeadConf();

?>

<?php

outDeadlines();
echo $conf->generateDataBox();

?>



<?php

outFootConf();

?>
