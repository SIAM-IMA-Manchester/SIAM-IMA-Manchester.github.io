<?php

require('common.php');
outHead("Poll: Survey for Math dot Seminars");

?>


<center>
<form name="dotSeminars" action="dotSeminars_check.php" method="post">
<?php echo get2hidden(); ?>

	     <fieldset style="width:80%;">
  
  <legend>Ballot</legend>
     <table class="grid" cellspacing="10" width=100%>
       <tr>
         <td colspan="2"> E-mail Address:</td>
         <td colspan="2"> <input  name="email" type="text" /></td>
       </tr>
     
       <tr>
          <td colspan="2">Password:</td>
          <td colspan="2"><input name="pass" type="password" /></td>
       </tr>

       <tr> 
         <td></td>
         <td>Strongly<br>Interested</td>
         <td>Interested</td>
         <td>Not <br/> interested</td>
       </tr>

       <tr>
         <td class="griditem"> TiKz.</td>
         <td><input type=radio  name="griditem1" value="1"></td>
         <td><input type=radio  name="griditem1" value="2"></td>
         <td><input type=radio  name="griditem1" value="3"></td>
       </tr>
  
       <tr>
         <td class="griditem"> Emacs for LaTeX.</td>
         <td><input type=radio  name="griditem1" value="1"></td>
         <td><input type=radio  name="griditem1" value="2"></td>
         <td><input type=radio  name="griditem1" value="3"></td>
       </tr>

       <tr>
         <td class="griditem"> Data Science (Kaggle).</td>
         <td><input type=radio  name="griditem1" value="1"></td>
         <td><input type=radio  name="griditem1" value="2"></td>
         <td><input type=radio  name="griditem1" value="3"></td>
       </tr>

       <tr>
         <td align="center" colspan="4"><input name="Submit" type="submit" value="     Submit     " /></td>
       </tr>

</table>
</fieldset>
</form>
</center>
