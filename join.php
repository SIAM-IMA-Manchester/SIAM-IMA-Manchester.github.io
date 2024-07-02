<?php

require('common.php');
outHead("Join");

?>

<center>
<form method="post" action="register.php">
<table>
<tr><td>First name</td><td><input name="name" /></td></tr>
<tr><td>Surname</td><td><input name="surname" /></td></tr>
<tr><td>Position</td><td><select name="position"><option selected value="Undergraduate"  >Undergraduate</option><option value="Graduate PhD."  >Graduate PhD.</option><option value="Graduate Masters"  >Graduate Masters</option><option value="Staff"  >Staff</option><option value="Postdoc"  >Postdocs</option><option value="other"  >Other</option></select></td></tr>
<tr><td>Institution</td><td><input name="institution" value="The University of Manchester"/></td></tr>
<tr><td>School</td><td><input name="school" value="School of Mathematics"/></td></tr>
<tr><td>E-mail address</td><td><input name="email" /></td></tr>
<tr><td>Password</td><td><input type="password" name="pass" /></td></tr>
<tr><td>Confirm password</td><td><input type="password" name="pass2" /></td></tr>
<tr><td colspan="2"><input type="submit" value="Register" /></td></tr>
</table>
<form>
</center>
<?php outFoot(); ?>
