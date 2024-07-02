<?php

require('common.php');
outHead("Login");

?>

<center>
<form name="login" action="login_check.php" method="post">
<?php echo get2hidden(); ?>
<table>
<tr><td>E-mail Address:</td><td><input name="email" type="text" /></td></tr>
<tr><td>Password:</td><td><input name="pass" type="password" /></td></tr>
<tr><td colspan="2" align="center"><a href="recover.php">Forgotten password</a></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input name="Login" type="submit" value="     Login     " /></td></tr>
</table>
</form>
</center>

<?php outFoot(); ?>
