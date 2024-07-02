<?php

require('common.php');
outHead("Password recovery");

?>

<center>
<form name="password_recover" action="password_recover.php" method="post">
<?php echo get2hidden(); ?>
<table>
<tr><td>E-mail Address:</td><td><input name="email" type="text" /></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" align=center><input name="Send" type="submit" value="     Send new password     " /></td></tr>
</table>
</form>
</center>

<?php outFoot(); ?>