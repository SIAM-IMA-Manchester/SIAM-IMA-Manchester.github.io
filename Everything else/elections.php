<?php

require('common.php');
outHead("Elections: Vice President for the Chapter");

?>

<center>
<form name="elections" action="elections_check.php" method="post">
<?php echo get2hidden(); ?>
<table>
<tr><td><b>Candidate name: </b>Monisha Natchiar Subbiah Renganathan</td></tr>
<tr><td><b>Current education: </b>MSc Applied Mathematics</td></tr>
<tr><td><b>Past education: </b>Master of Technology in Chemical Engineering, Indian Institute of Technology, Madras (2013-15);
Bachelor of Technology in Chemical Engineering, Coimbatore Institute of (2007-11).</td></tr>
<tr><td><b>Professional Memberships: </b> Indian Institute of Chemical Engineers (2009-2011) - Student Member</td></tr>
<tr><td><b>Statement: </b>The main purpose of the SIAM Student Chapter has been to enhance the 
collaboration between industry and academia, thereby encouraging the student members to find new and interesting applications of mathematics in diverse and varied fields. One of the major issues that I see at present is the need to increase the interaction among the student members at different levels (undergraduate, masters and PhD) and also between the University’s Student Chapter and the industry. The latter can be accomplished by organizing featured talks and seminars by the relevant industry personnel. Opportunities have to be created for the members to share their research among themselves and also with other interdisciplinary research groups in order for them to see the usefulness and applicability of mathematics in the other fields. Networking among different student chapters can be enhanced by holding regular meetings of SIAGs (SIAM Activity Groups).  In addition, career development seminars and talks on non-academic careers should serve as vital aspects of the SIAM Student Chapter. As part of the Committee member, I would work with my team members to bring into effect the above said terms and also establish good rapport among the past and current SIAM student members.</td><td></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><b>Candidate name: </b>Mante Zemaityte</td></tr>
<tr><td><b>Current education: </b>PhD student in Numerical Analysis</td></tr>
<tr><td><b>Past education: </b>MMath Mathematics, The University of Manchester</td></tr>
<tr><td><p><b>Statement: </b>My own experience with past events organised by the Chapter has been very positive and helpful with understanding the role of mathematics in industry. As a student of applied mathematics my work is directly influenced by developments in industry, which led me to run for vice president of the student committee.</p>
<p>My focus would be on promoting and furthering the objectives of SIAM and the Chapter: to improve the communication between the past and present members; to maintain and establish new links between the members and industry; to encourage the involvement of not only the postgraduates of applied mathematics, but also those working in related disciplines, including undergraduates in their final year of study, and to give them an opportunity to explore and discuss their career prospects with the more senior postgraduate students and industrial partners.</p>								
<p><b>
The election is now closed. We will announce the result shortly.
</b>
</p>

	     																											    </td></tr>
	     																											    <td>&nbsp;</td></tr>
	     <td>&nbsp;</td></tr>
	     </table>
	     <fieldset style="width:80%;">
	     																								             			    <legend>Ballot</legend>
	     																											    <table width=100%>
	     																											    <tr><td>E-mail Address:</td><td><input  name="email" type="text" /></td></tr>
	     																											    <tr><td>Password:</td><td><input name="pass" type="password" /></td></tr>
	     																											    <td>&nbsp;</td></tr>
	     <tr><td><input type="radio" name="candidate" value="monisha">Monisha N. S. Renganathan</td>
	     																											    <td><input type="radio" name="candidate" value="mante">Mante Zemaityte</td></tr>
	     																											    <td>&nbsp;</td></tr>

	     <tr><td align="center" colspan="2"><input name="Submit" type="submit" value="     Submit     " /></td></tr>

</table>
</fieldset>
</form>
</center>

<?php outFoot(); ?>
