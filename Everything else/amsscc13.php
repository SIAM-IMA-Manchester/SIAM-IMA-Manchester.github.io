<?php

//ini_set("display_errors", "yes");
require_once("conferences.php");

$conferenceData = array(
  "code" => "amsscc13",
  "dbh" => dbhConnect(),
  "deadline: Registration" => "2013-05-13",
  "deadline: Abstract and poster submission" => "2013-05-06",
  //"debug" => true,
  "email" => "Manchester SIAM Student Chapter <siam@manchester.ac.uk>",
  "header" => "20<sup>th</sup> May 2013.<br />\nAlan Turing Building<br />\nUniversity of Manchester",
  "membersCSS" => "bordered".(hasPerm("Events organizer") ? " hover" : ""),
  "role" => (hasPerm("Events organizer") ? vsRegs::ROLE_ADMIN : vsRegs::ROLE_LIST),
  "shortTitle" => "AMSSCC13",
  "title" => "Annual Manchester SIAM Student Chapter<br />Conference 2013 (AMSSCC13)",
  "uploadsDir" => "files/uploads/amsscc13",
);

// $defaultCaption = "Default caption"
$imgCaptions = array(
  // "filename" => "Caption"
  "amsscc13-0006.jpg" => "From FA2 to FA1",
  "amsscc13-0007.jpg" => "From FA1 to FA2",
  "amsscc13-0008.jpg" => "Sam Relton, opening",
  "amsscc13-0010.jpg" => "Dr. Stephen Hendry on GSA - Maths applied to structural analysis",
  "amsscc13-0014.jpg" => "Mary Akinyemi on Geometric Ergodicity of the Mixture Autoregressive Models",
  "amsscc13-0041.jpg" => "Denny Vitasari on Simulation of surfactant transport onto a foam lamella using material point method",
  "amsscc13-0043.jpg" => "Sophia Coban on Mathematical Modelling of X-Ray Computed Tomography",
  "amsscc13-0046.jpg" => "Dr. Oliver Dorn on Inverse problems in action",
  "amsscc13-0055.jpg" => "Prof. Des Higham on Twitter's Big Hitters",
  "amsscc13-0057.jpg" => "Hugo Eduardo Ramirez Jaime on Optimal portfolio problem under Liquidity constraint",
  "amsscc13-0059.jpg" => "Javier Hernandez on Stochastic storage systems valuation, with application to wind power generation",
  "amsscc13-0064.jpg" => "Mingliang Cheng on Optimal Bank and Regulatory - Capital Reserve Strategies under Loan-Loss Uncertainty",
  "amsscc13-0066.jpg" => "Joseph Dobson on Using PLASMA in the NAG library",
  "amsscc13-0071.jpg" => "Best tweet of the day is Sophia Coban's 'My talk is in less than 10 minutes, and i can't remember a thing! :|'. You would never guess that from her talk. Congratulations, Sophia. :)",
  "amsscc13-0074.jpg" => "Thomas Slater wins the NAG award for the best PhD student poster Chemically sensitive 3D imaging of nanoparticles. Congratulations, Thomas. :)",
  "amsscc13-0077.jpg" => "Sophia's 'I can't remember a thing' talk is the best PhD student talk of the day. Congratulations again. :)"
  );

$defaultTitle = "Annual Manchester SIAM Student Chapter Conference 2013";
$imgTitles = array(
  // "filename" => "Title"
);

outHeadConf();

?>

<p>A one day conference for all interested or working in applied or industrial mathematics, including undergraduates, postgraduates and staff, will take place on the 20th May 2013 in the Alan Turing Building, University of Manchester.</p>

<p>There is no registration fee and the deadline for the registration is 13th May 2013. An informal outing is planned for after the conference.</p>

<p>For any further information or clarification, please email <?php emailFromText($conferenceData["email"]); ?>.</p>

<p>For full details of the <b>programme</b> <a href="#prog">click here</a>.</p>

<div class="remark">
<div class="title">Update</div>
<div class="content"<p>Thanks to everyone for coming, we had a great day seeing what people were working on and meeting new people from around the university. I'm very pleased that we managed to increase the diversity of our meeting and I hope this is something we can carry on in subsequent years!</p>
<p>Photos from the meeting: <br />
Group photo: <a href="files/amsscc13/amsscc13-group_photo.jpg">lowres image</a> - <a href="files/amsscc13/amsscc13-group_photo-hires.jpg">hires image</a><br />
<a href="http://www.maths.manchester.ac.uk/~siam/amsscc13.php?gallery">Gallery</a> 
</p>
<p><b>Sam Relton - President Manchester SIAM Student Chapter</b></p></div>
</div>
<?php

outDeadlines();
echo $conf->generateDataBox();

?>


<h3>Plenary speakers</h3>
<p>Dr. Stephen Hendry, ARUP<br />
Dr. Oliver Dorn, University of Manchester<br />
Prof. Des Higham, University of Strathclyde<br /></p>


<h3>Organising Committee</h3>
<p>Samuel Relton, University of Manchester<br />
Bahar Arslan, University of Manchester<br />
Mary Aprahamian, University of Manchester<br />
Nataša Strabić, University of Manchester<br />
Vedran Šego, University of Zagreb</p>

<a name="prog" class="anchor"></a>

<h3>Programme</h3>

<p>A list of talks and posters for the conference can be found below.</p>

<h3>List of Talks</h3> 

<table cellpadding="0" cellspacing="0" class="bordered">
<tr><th>Time</th><th>Title</th><th></th></tr>
<tr><td>09.00 - 09.20</td><td>Registration</td><td></td></tr>
<tr><td>09.20 - 09.30</td><td>Opening</td><td><a href="files/amsscc13/talks/Relton_slides.pdf">slides</a></td></tr>
<tr><td>09.30 - 10.15</td><td>Dr. Stephen Hendry (Arup) - <a href="files/amsscc13/abstracts/Hendry_abstract.pdf">GSA - Maths applied to structural analysis</a></td><td><a href="files/amsscc13/talks/Hendry_slides.pptx">slides</a></td></tr>
<tr><td>10.15 - 10.40</td><td>Mary Akinyemi - <a href="files/amsscc13/abstracts/Akinyemi_abstract.pdf">Geometric Ergodicity of the Mixture Autoregressive Models</td><td><a href="files/amsscc13/talks/Akinyemi_slides.pdf">slides</a></td></tr>
<tr><td>10.40 - 11.00</td><td>Coffee break</td><td></td></tr>
<tr><td>11.00 - 11.25</td><td>Denny Vitasari - <a href="files/amsscc13/abstracts/Vitasari_abstract.pdf">Simulation of surfactant transport onto a foam lamella using material point method</a></td><td><a href="files/amsscc13/talks/Vitasari_slides.pptx">slides</a></td></tr>
<tr><td>11.25 - 11.50</td><td>Sophia Bethany Coban - <a href="files/amsscc13/abstracts/Coban_abstract.pdf">Mathematical Modelling of X-Ray Computed Tomography</a></td><td><a href="files/amsscc13/talks/Coban_slides.pdf">slides</a></td></tr>
<tr><td>11.50 - 12.35</td><td>Dr. Oliver Dorn - <a href="files/amsscc13/abstracts/Dorn_abstract.pdf">Inverse problems in action</a></td><td></td></tr>
<tr><td>12.35 - 12.40</td><td>Group Photo</td><td></td></tr>
<tr><td>12.40 - 14.00</td><td>Lunch and poster session</td><td></td></tr>
<tr><td>14.00 - 14.45</td><td>Prof. Des Higham (U. Strathclyde) - <a href="files/amsscc13/abstracts/Higham_abstract.pdf">Twitter's Big Hitters</a></td><td><a href="files/amsscc13/talks/Higham_slides.pdf">slides</a></td></tr>
<tr><td>14.45 - 15.10</td><td>Hugo Eduardo Ramirez Jaime - <a href="files/amsscc13/abstracts/Ramirez_abstract.pdf">Optimal portfolio problem under Liquidity constraint</a></td><td><a href="files/amsscc13/talks/Ramirez_slides.pdf">slides</a></td></tr>
<tr><td>15.10 - 15.35</td><td>Javier Hernandez - <a href="files/amsscc13/abstracts/Hernandez_abstract.pdf">Stochastic storage systems valuation, with application to wind power generation</a></td><td></td></tr>
<tr><td>15.35 - 16.00</td><td>Coffee break</td><td></td></tr>
<tr><td>16.00 - 16.25</td><td>Mingliang Cheng - <a href="files/amsscc13/abstracts/Cheng_abstract.pdf">Optimal Bank and Regulatory - Capital Reserve Strategies under Loan-Loss Uncertainty</a></td><td><a href="files/amsscc13/talks/Cheng_slides.pdf">slides</a></td></tr>
<tr><td>16.25 - 16.50</td><td>Joseph Dobson (NAG) - <a href="files/amsscc13/abstracts/Dobson_abstract.pdf">Using PLASMA in the NAG library</a></td><td><a href="files/amsscc13/talks/Dobson_slides.pdf">slides</a></td></tr>
<tr><td>16.50 - 17.00</td><td>Award ceremony (Sponsored by NAG)</td><td></td></tr>
<tr><td>17.00 - 18.00</td><td>Wine reception at the Atrium bridge</td><td></td></tr>
<tr><td>18.00 - </td><td>Informal dinner</td><td></td></tr>
</table>


<h3>List of Posters</h3> 

<table cellpadding="0" cellspacing="0" class="bordered" >
<tr><td>Mary Aprahamian - The matrix unwinding function</td></tr>
<tr><td>Bahar Arslan - Logarithm of structured matrices</td></tr>
<tr><td>Mingliang Cheng - <a href="files/amsscc13/posters/Cheng_poster.pdf">The Optionality of a Financial Constrained Firm</a></td></tr>
<tr><td>Sophia Bethany Coban - Introduction to X-Ray Computed Tomography and Artefact Types</td></tr>
<tr><td>Nil Mansuroglu - <a href="files/amsscc13/posters/Mansuroglu_poster.pdf">Products of Homogeneous Subspaces in Free Lie Algebras</a></td></tr>
<tr><td>Juei-chin Shen - Cooperative estimation of path loss in interference channels without primary-user CSI feedback</td></tr>
<tr><td>Thomas Slater - Chemically sensitive 3D imaging of nanoparticles</td></tr>
<tr><td>Sam Relton - Computing the matrix logarithm</td></tr>
</table>


<h3>Additional information</h3>

<p>The current list of participants can be found <a href="?list">here</a>.</p>

<p><a href="contact.php">Directions to Alan Turing Building</a><?php /* <br />
<?php echo dwldLink("files/snscc12/hotel_information.pdf", "Accommodation Guide"); */ ?></p>

<h3>Sponsors</h3>

<p><a href="http://www.siam.org/" class="img" title="SIAM: Society for Industrial and Applied Mathematics"><img src="images/siam.png" alt="SIAM" width="172" height="55" border="0" /></a></p>

<p><a href="http://www.nag.co.uk/" class="img" title="Numerical Algorithms Group"><img src="images/nag_logo.png" alt="Numerical Algorithms Group" width="289" height="52" border="0" /></a></p>

<?php

outFootConf();

?>
