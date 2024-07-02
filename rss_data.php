<?php

$rssData = array(

  // mktime arguments
  // hour, minute, second, MONTH, DAY, year
  /*
      array(
        "timestamp" => mktime(00, 00, 00, 7, 1, 1899), // time of the publishing (if undefined or zero, the item does not show in the RSS) hour:min:sec, month:day:year
        "title" => "", // title of the item (mandatory)
        "text" => "", // text of the item (mandatory)
        "time" => mktime(17, 19, 23, 11, 7, 2317), // the actual time of the event (after this, te RSS item expires)
        "alias" => array("category", "timestamp"), // the link to the item from which the unset values are to be copied
      ),
  */
     "info" =>

     array(
      "title" => "Information",
      "items" => array(
        array(
          "timestamp" => mktime( 12,30,00, 02, 22, 2023),
          "title" => "Manchester SIAM-IMA Student Chapter Conference 2023",
          "text" =>  "<p>
                      We are pleased to announce the 9th Manchester SIAM-IMA Student Chapter Conference (MSISCC23). It will be held in Frank Adams 1 and 2, Alan Turing Building on 27th April 2023.</p>
                      <p>
                      This annual conference is a one-day event for all interested or working in applied or industrial mathematics. The conference provides a forum for communication among students from applied mathematics, computer science and engineering, we encourage the promotion of applied mathematics and computational science to early career researchers, especially, but not limited to, graduate students and postdoctorate researchers.
                      <p>
                      Up-to-date information and registration can be found on the <a href=\"msiscc23\">MSISCC23 conference website</a>.
                      <p>",
                  "time" => mktime(00,00,00, 02, 22, 2023),
                ),


  					array(
						"timestamp" => mktime( 15, 0, 0, 10, 26, 2022),
						"title" => "Manchester SIAM-IMA Student Chapter Committee 2022/2023",
						"text" => "<p>
					We are pleased to announce this year's organising committee members:
						</p>
					<ul>
					<li> President: Xinye Chen
					<li> Vice President: Ioanna Nikolopoulou
					<li> Secretary: Junaid Ali Shah
					<li> Treasurer: Zhengbo Zhou
					<li> Webmaster: Alban Bloor Riley
					</ul>"
					  ),      
			 
  					array(
						"timestamp" => mktime( 15, 0, 0, 01, 12, 2021),
						"title" => "Manchester SIAM-IMA Student Chapter Committee 2021/2022",
						"text" => "<p>
					We are pleased to announce this year's organising committee members:
						</p>
					<ul>
					<li> President: Michael Connolly
					<li> Vice President: Xiaobo Liu
					<li> Secretary: Ioanna Nikolopoulou
					<li> Treasurer: Xinye Chen
					<li> Webmaster: Robbie Bancroft
					</ul>"
					  ),      

					array(
						"timestamp" => mktime( 15, 0, 0, 10, 26, 2020),
						"title" => "Manchester SIAM-IMA Student Chapter Committee 2020/2021",
						"text" => "<p>
					We are pleased to announce this year's organising committee members:
						</p>
					<ul>
					<li> President: Michael Connolly
					<li> Vice President: Xinye Chen
					<li> Secretary: Ioanna Nikolopoulou
					<li> Treasurer: Xiaobo Liu
					<li> Webmaster: Robbie Bancroft
					</ul>"
					  ),      

					   array(
					    "timestamp" => mktime( 10,30,00, 02, 06, 2020),
					    "title" => "N Brown Problem-solving Day",
                       "text" => "<p><font color='red'>The N Brown Day is postponed until a later date in line with N Brown's
                                      COVID-19 prevention policy</font></p>
                       
                       <del><p>The Manchester SIAM IMA Student Chapter is hosting 
                       a mathematical workshop in collaboration with N Brown on the <b>18th of March</b>.</p>
            
                       <p>N Brown is an online retailer, headquartered in Manchester, which offers a 
                       range of products; predominantly clothing, footwear and homewares. During the 
                       workshop a data scientist from N Brown, Mary Guettel,  will present a mathematical problem 
                       which arises in her work. There will then be a computer lab session where we will 
                       work in groups to come up with creative solutions to the problem. The workshop will 
                       conclude with a discussion session, with free food provided.</p>

                       <p>The students can use any of Excel, R, Python, MATLAB, or other preferred tools, 
                       however, no prior mathematical or programming knowledge is necessary. As such, this 
                       workshop is well suited for undergraduates and postgraduates alike. This will be a 
                       great opportunity to see how mathematical problems can arise in business and industry.</p>

                       <p>For more information and registration click
                       <a href=\"http://www.maths.manchester.ac.uk/~siam/nbrown20/\">here.</a></del>",
					"time" => mktime(14, 00, 00, 03, 18, 2020)
                      ),
				      array(
					    "timestamp" => mktime( 10,30,00, 10, 30, 2019),
					    "title" => "3-Minute Thesis Competition",
					    "text" =>
					    " <p>
							The Manchester SIAM-IMA Student Chapter is proud to organize the first 3-Minute Thesis Competition at the Department of Mathematics on 
						<strong>November 22, 2019 at 15:00</strong>. Do you think you can present your thesis project in just three minutes? If so, 
						<a href=\"http://maths.manchester.ac.uk/~siam/3minutethesis\"> register here </a> and try to be among the <strong> three winners </strong>! 
						If you are not interested in the competition, do not worry! You can come and enjoy the talks, together with food and refreshments at the end of the event.</p>

						<p>The 3-Minute Thesis competion is a concept developed by the <a href=\"https://threeminutethesis.uq.edu.au/\">University of Queensland</a> which quickly 
						spread across Australia and went global. We are proud to start this new tradition at our local SIAM-IMA chapter.</p>",
					    "time" => mktime(16,00,00, 12, 20, 2019)
					    ),
				      array(
					    "timestamp" => mktime( 18,30,00, 9, 30, 2019),
					    "title" => "Manchester SIAM Social Event 2019",
					    "text" =>
					    " <p>
							Join us at 4pm on 1st of November for a relaxed evening to meet the other Chapter members, have a say in future activities, and enjoy free drinks and pizza! 
							See <a href=\"http://maths.manchester.ac.uk/~siam/social1911/\"><i><b>here</b></i></a> for more information and registration.
                              </p>",
					    "time" => mktime(16,00,00, 11, 1, 2019)
					    ),
				      array(
					    "timestamp" => mktime( 12, 0, 0, 9, 23, 2019),
					    "title" => "Dr Swinton's seminars",
					    "text" => "<p>
                      At <strong>2:00 PM</strong> on Wednesday the <strong>16th of October</strong>, Jonathan Swinton will give
                      two talks about the relationship between phyllotaxis and Fibonacci's numbers,
                      and Alan Turing's life in Manchester. The first talk is open to any undergraduate
                      in Maths, while the history of Turing can be enjoyed by anyone, so feel free to come!
                      <a href=\"turing19\">Register</a> and join us in <strong>Alan Turing G.209</strong> (Alan Turing Building).
                      <strong>Free drinks  and food will be served</strong>!
                      </p>",
		      "time" => mktime(17,00,00,10,30,2019)
		      ),
		array(
	 	      "timestamp" => mktime( 12, 0, 0, 6, 19, 2019),
		      "title" => "Annual General Meeting 2019",
                      "text" => "<p>
                      At 5:00PM on Thursday the 27th of June we will be hosting our Annual
                      General Meeting of the Chapter in Frank Adams 2. The agenda for the 
                      meeting will consist of a review of this year's activities including 
                      plans for next year's events. It is an opportunity for you to have a 
                      say in the future activities. Free drinks and pizza will be served. 
                      Places are limited, please register <a href=\"agm19\">here</a>.
                      </p>",
		      "time" => mktime(17,00,00,6,27,2019)
		      ),


		 array(
		       "timestamp" => mktime(12, 00, 00, 2, 7, 2019),
		       "title" => "SIAM UKIE National Student Chapter Conference",
                       "text" => "<p>
                                     The Manchester SIAM IMA Student Chapter is proud to be hosting
                                      the SIAM UKIE National Student Chapter Conference 2019 
                                     <a href=\"https://twitter.com/?logout=1549544827311\"> (#SNSCC19) </a>on June 10th-11th.
                                 </p>
                       <p>
                        Up-to-date information and registration can be found on the <a href=\"snscc19\"> conference website</a>.
                        </p>",
		       "time" => mktime(14,00,00,7,2,2019)
                      ),


		 array(
		       "timestamp" => mktime(12, 00, 00, 11, 1, 2018),
		       "title" => "N Brown Day Update",
                       "text" => "<p>The Manchester SIAM IMA Student Chapter hosted 
                       a mathematical workshop in collaboration with N Brown on the 17th of 
                       October.</p>
            
                       <p>In a follow-up event on November 1, the best teams, represented by Andra Popa, 
                       Jonathan Deakin, Mante Zemaityte, Steven Elsworth, and Wenrui Li, showcased their 
                       results at the N Brown headquarters in Manchester. The winners of the Challenge were 
                       awarded prizes sponsored by N Brown, including a brand-new notebook. 

                       <p>For more information click
                       <a href=\"http://www.maths.manchester.ac.uk/~siam/nbrown18/post_event_update\">here.</a>",
		       "time" => mktime(14,00,00,11,1,2018)
                      ),

				                     
		 array(
		       "timestamp" => mktime(12, 00, 00, 9, 18, 2018),
		       "title" => "N Brown Day",
                       "text" => "<p> The Manchester SIAM IMA Student Chapter is hosting 
                       a mathematical workshop in collaboration with N Brown on the <b>17th of October</b>.</p>
            
                       <p>N Brown is an online retailer, headquartered in Manchester, which offers a 
                       range of products; predominantly clothing, footwear and homewares. During the 
                       workshop Phil Nash, a data scientist at N Brown will present a mathematical problem 
                       which arises in his work. There will then be a computer lab session where we will 
                       work in groups to come up with creative solutions to the problem. The workshop will 
                       conclude with a discussion session, with free food provided.</p>

                       <p>The students can use any of Excel, R, Python, MATLAB, or other preferred tools, 
                       however, no prior mathematical or programming knowledge is necessary. As such, this 
                       workshop is well suited for undergraduates and postgraduates alike. This will be a 
                       great opportunity to see how mathematical problems can arise in business and industry.</p>

                       <p>For more information and registration click
                       <a href=\"http://www.maths.manchester.ac.uk/~siam/nbrown18/event\">here.</a>",
		       "time" => mktime(14, 00, 00, 10, 17, 2018)
                      ),

		array(
		      "timestamp" => mktime( 09,00,00, 9, 1, 2018),
		      "title" => "New Webmaster",
		      "text" => "Congratulations to our new webmaster Puneet Matharu.",
		      "time" => mktime(00,00,00, 9, 1, 2018),
		      ),

		 array(
                      "timestamp" => mktime( 12, 0, 0, 6, 10, 2018),
                      "title" => "Annual General Meeting 2018",
                      "text" => "<p>
                      At 5:00PM on Wednesday the 20th of June we will be hosting our Annual
                      General Meeting of the Chapter. The agenda for the meeting will consist
                      of a review of this year's activities including plans for next year's
                      events. It is an opportunity for you to have a say in the future
                      activities. Free drinks and pizza will be served. Places are limited,
                      please register <a href=\"agm18\">here</a>.
                      </p>"
                    ),


		 array(
                       "timestamp" => mktime( 12,00,00, 5, 21, 2018),
                       "title" => "<a href='etymo18'> Etymo Problem Solving Event</a>",
 		       "text" => " <p> The Manchester SIAM IMA student chapter is
                      hosting a mathematical workshop in collaboration with <a href='https://etymo.io/'>Etymo</a> on
                      Wednesday 13th June.</p>

                     <p>Etymo is a startup founded by current and former students from the University of Manchester. 
			Etymo leverages data summarisation and visualisation to empower individuals and organizations 
			to understand information fast. Their current focus is on identifying interesting and relevant 
			research papers.</p>

		      <p> Find out more information about the event and register
			 <a href='http://www.maths.manchester.ac.uk/~siam/etymo18/'> here</a>.
		      </p>",
                       "time" => mktime(00,00,00, 01, 01, 2019),
                     ), 

		 array(
                       "timestamp" => mktime( 12,00,00, 4, 22, 2018),
                       "title" => "New Secretary",
                       "text" => "Congratulations to our new secretary Bindu Vekaria, who joined the committee during the organisation of the Student Chapter Conference.",
                       "time" => mktime(00,00,00, 1, 1, 2019),
                     ),

           array(
                  "timestamp" => mktime( 12,00,00, 4, 21, 2018),
                  "title" => "MSISCC18 Update",
                  "text" =>  "

                      <p>Thank you to everyone who attended the conference. We hope you
                      enjoyed, and got as much out of the day as we in the committee did. In
                      particular we would like to thank all those who presented, including our
                      plenary speakers and students with talks.</p>

		      <p> This years plenary speakers were:
		      <ul>
			<li>Dr Louise Dyson (The University of Warwick)</li>
			<li>Dr Silvia Gazzola (The University of Bath)</li>
		     	<li>Dr Nick Dingle (NAG, Numerical Algebra Group)</li>
			<li>Prof. Gunnar Martinsson (The University of Oxford)</li>
		      </ul>

		      <p>Congratulations to the award winner of this year's best talk,
		      Jonathan Deakin with his presentation <q>Optimal Coordinate Transformations 
		      for the Perfectly Matched Layer Method</q> with a prize kindly sponsored by NAG.</p>

                      <p>If you haven't yet <a
                      href='http://www.siam.org/students/memberships.php'>joined SIAM</a>,
                      membership is free for all students. For postgraduates this should be
                      straightforward, but for undergraduates you may need a nonstudent
                      nomination; if you have any trouble <a
                      href='mailto:siam@maths.man.ac.uk'>let us know</a>
                      and we should be able to help sort it out. </p>

                      <p> In addition, you can sign up as an IMA e-Student for free <a
                      href='https://docs.google.com/forms/d/e/1FAIpQLSfpSzkCiPmrI-MNfDbogfl9jknpD2kVQQn8FYQK6MvonSlE7g/viewform?formkey=3DdHdXOTdKellPdXQ5VVRyQzZKZ2pfR3c6MQ'>
                      here</a>, and if you haven't yet joined the Manchester SIAM-IMA Student
                      Chapter, the registration page is <a
                      href='http://maths.manchester.ac.uk/~siam/profile.php'>here</a>.</p>

<!--
                      <p>The group photo from the event is below (click <a
                      href='/~siam/images/msiscc17gp.jpg'>here</a> for a higher resolution
                      version). If you have any good photos of  the conference that you would
                      let us use for future publicity, please <a
                      href='mailto:matthew.gwynne@postgrad.manchester.ac.uk'>send them</a> to
                      us!</p> <img class='img-responsive' src='/~siam/images/msiscc17gp_smaller.jpg' alt='Image of MSISCC17
                      attendees'>
-->
			",

                  "time" => mktime(00,00,00, 01, 01, 2019),
                ),


           array(
                  "timestamp" => mktime( 12,30,00, 03, 25, 2018),
                  "title" => "Manchester SIAM-IMA Student Chapter Conference",
                  "text" =>  "<p>
                      We are pleased to announce the 8th Manchester SIAM Student Chapter Conference (MSISCC18). It will be held in Frank Adams 1, Alan Turing Building on 20th April 2018.</p>
                      <p>
                      This annual conference is a one-day event for all interested or working in applied or industrial mathematics. The event is organised for undergraduates, postgraduates and staff of The University of Manchester.
                      <p>
                      Up-to-date information and registration can be found on the <a href=\"msiscc18\">conference website</a>.
                      <p>",
                  "time" => mktime(00,00,00, 01, 01, 2019),
                ),

           array(
                  "timestamp" => mktime( 09,50,00, 02, 07, 2018),
                  "title" => "Math Dot Seminar Series",
                  "text" => "Math Dot is back. We will explore skills that might be useful for a scientific career, but aren't necessarily
                  covered in other courses. Vote for what skills you would like to learn <a href =\"http://www.maths.manchester.ac.uk/~siam/dot2018\">here </a> now!",
                  "time" => mktime(00,00,00, 01, 01, 2019),
                ),

           array(
                  "timestamp" => mktime( 09,50,00, 01, 17, 2018),
                  "title" => "OR society event",
                  "text" => "Join us at the OR society event on 5th Feb 2018, Register <a href =\"http://www.maths.manchester.ac.uk/~siam/or2018\">here </a> now!",
                  "time" => mktime(00,00,00, 02, 6, 2018),
                ),

                array(
                       "timestamp" => mktime( 09,50,00, 01, 15, 2018),
                       "title" => "New Webmaster",
                       "text" => "Congratulations to our new webmaster Steven Elsworth!",
                       "time" => mktime(00,00,00, 01, 16, 2018),
                     ),

                array(
                      "timestamp" => mktime( 15, 0, 0, 11, 3, 2017),
                      "title" => "Manchester SIAM-IMA Student Chapter Committee 2017/2018",
                      "text" => "<p>
                We are delighted to announce this year's organising committee members:
                      </p>
                <ul>
                <li> President: Yuqing Zhang
                <li> Vice President: Gian Maria Negri Porzio
                <li> Secretary: Massimiliano Fasi
                <li> Treasurer: Thomas McSweeney
                <li> Webmaster: Mante Zemaityte
                </ul>"
                    ),

                array(
                      "timestamp" => mktime( 12, 0, 0, 6, 13, 2017),
                      "title" => "Annual General Meeting 2017",
                      "text" => "<p>
                      At 5:00PM on Wednesday the 21st of June we will be hosting our Annual
                      General Meeting of the Chapter. The agenda for the meeting will consist
                      of a review of this year's activities including plans for next year's
                      events. It is an opportunity for you to have a say in the future
                      activities. Free drinks and pizza will be served. Places are limited,
                      please register <a href=\"agm17\">here</a>.
                      </p>"
                    ),
                    array(
                      "timestamp" => mktime(15, 0, 0, 5, 9, 2017),
                      "title" => "#MSISCC17 update",
                      "text" => "
                      <p>Thank you to everyone who attended the conference. We hope you
                      enjoyed, and got as much out of the day as we in the committee did. In
                      particular we would like to thank all those who presented, including our
                      plenary speakers and students with talks and posters.</p>

                      <p>Congratulations to our prize winners: the prize for best poster went
                      to Paul Russell, and the prizes for best talks went to Helena Stage and
                      Pallav Kant</p>

                      <p>If you haven't yet <a
                      href='http://www.siam.org/students/memberships.php'>joined SIAM</a>,
                      membership is free for all students. For postgraduates this should be
                      straightforward, but for undergraduates you may need a nonstudent
                      nomination; if you have any trouble <a
                      href='mailto:matthew.gwynne@postgrad.manchester.ac.uk'>let us know</a>
                      and we should be able to help sort it out. </p>

                      <p> In addition, you can sign up as an IMA e-Student for free <a
                      href='https://docs.google.com/forms/d/e/1FAIpQLSfpSzkCiPmrI-MNfDbogfl9jknpD2kVQQn8FYQK6MvonSlE7g/viewform?formkey=3DdHdXOTdKellPdXQ5VVRyQzZKZ2pfR3c6MQ'>
                      here</a>, and if you haven't yet joined the Manchester SIAM-IMA Student
                      Chapter, the registration page is <a
                      href='http://maths.manchester.ac.uk/~siam/profile.php'>here</a>.</p>

                      <p> The next major Chapter event will be our AGM, which will take place
                      in mid June. If you are signed up as a Chapter member you will receive
                      an email about this soon. We will also be continuing our math dot
                      seminar series through the summer, so look out for that too.</p>

                      <p>The group photo from the event is below (click <a
                      href='/~siam/images/msiscc17gp.jpg'>here</a> for a higher resolution
                      version). If you have any good photos of  the conference that you would
                      let us use for future publicity, please <a
                      href='mailto:matthew.gwynne@postgrad.manchester.ac.uk'>send them</a> to
                      us!</p> <img class='img-responsive' src='/~siam/images/msiscc17gp_smaller.jpg' alt='Image of MSISCC17
                      attendees'>
                      "
                    ),
                    array(
                      "timestamp" => mktime(13, 0, 0, 4, 4, 2017),
                      "title" => "Registration Open for MSISCC17",
                      "text" => "
                      <p>We are pleased to invite you to the <a href='msiscc17'>7th
                      Manchester SIAM-IMA Student Chapter Conference (MSISCC17)</a>. It will
                      be held in Frank Adams 1, Alan Turing Building on 5th May 2017.</p>

                      <p>This annual conference is a one-day event for all interested or
                      working in applied or industrial mathematics. The event is organised for
                      undergraduates, postgraduates and staff of the University of
                      Manchester.</p>

                      <p>Registration is now open! For more information or to register go to
                      the <a href='msiscc17'>conference page</a></p>"
                    ),
                    array(
                      "timestamp" => mktime(17, 00, 0, 3, 28, 2017),
                      "title" => "math.svg Seminar",
                      "text" => "
                      <p>The next seminar of the Manchester SIAM-IMA Student Chapter's Math
                      Dot seminar series will take place this Thursday, 30th March, at 4pm in
                      Frank Adams 2, Alan Turing building. Georgia Lynott will be giving an
                      introduction to using Inkscape.</p>

                      <p>A good diagram can make all the difference to the readability of your
                      paper, thesis or presentation, but making high-quality graphics can be
                      challenging. Inkscape is a free, open-source software for creating
                      vector graphics, with a host of tools to help you make beautiful,
                      professional-looking figures. This seminar is aimed at complete
                      beginners and will focus on the basics of creating diagrams in Inkscape,
                      whilst pointing out some of the most useful features of the software
                      along the way. Don't forget to bring your laptop (plug sockets will be
                      available) and be sure to download and install Inkscape from the <a
                      href='https://inkscape.org/en/'>website</a> (available for Windows, Mac
                      and Linux)</p>"
                    ),
                    array(
                      "timestamp" => mktime(13, 00, 0, 2, 17, 2017),
                      "title" => "Auto Trader Day",
                      "text" => "<p> The Manchester SIAM IMA student chapter is
                      hosting a mathematical workshop in collaboration with Auto Trader on the
                      22nd of February.</p>

                      <p>Auto Trader is the largest digital car marketplace with headquarters in
                      Manchester.  During the workshop Dr. Peter Appleby, a data scientist at
                      Auto Trader will present two mathematical problems that arise in his
                      work. There will then be a computer lab session where we will work in
                      groups to come up with creative solutions to these problems. The
                      workshop will conclude with a discussion session, with free food
                      provided.</p>


                      <p>No prior mathematical or programming knowledge is necessary, and this
                      workshop is well suited for undergraduates and postgraduates alike. This
                      will be a great opportunity to see how mathematical problems can arise
                      in business and industry.</p>

                      <p> For more information and registration click
                      <a href='http://www.maths.manchester.ac.uk/~siam/autotrader17/'> here </a>.</p>"
                    ),
                    array(
                      "timestamp" => mktime(16, 00, 0, 2, 9, 2017),
                      "title" => "Second Math Dot Seminar: math.git",
                      "text" => "<p>The second seminar of the Chapter seminar series Math Dot
                      will take place next Thursday (16th) at 4pm, FA2. Weijian Zhang will give an
                      introduction and advice to using version control with Git. No prior
                      experience assumed! We would like to encourage you to bring your own
                      laptops (power supply will be provided) and register to either <a href='https://github.com'>GitHub</a> or
                      <a href='https://bitbucket.org'>Bitbucket</a>, and download <a href='https://git-scm.com/'>Git</a> in advance.</p>"
                    ),
                    array(
                      "timestamp" => mktime(14, 44, 0, 12, 2, 2016),
                      "title" => "Launch of Math Dot Seminar Series with math.html",
                      "text" => "<p> We are about to start a new series of seminars, entitled Math Dot,
                      about skills that might be useful for a scientific career, but aren't necessarily
                      covered in other courses. In the future we will be covering topics such as Linux,
                      version control and LaTeX. The first seminar in this series, titled math.html, will be led by
                      Jonathan Deakin and will cover html and basic web design. No experience is
                      required, and there is no registration, so come along next Thursday to find out more.</p>"
                    ),
                    array(
                      "timestamp" => mktime(11, 14, 0, 11, 15, 2016),
                      "title" => "Chapter Social 2016 (Pizza!)",
                      "text" => "Join us at 5pm on the 23 November for a relaxed evening to meet the other Chapter members,
                      have a say in future activities, and enjoy free drinks and pizza! See <a href=\"/~siam/social1611\">here</a> for more information and registration."
                    ),
                    array(
                      "timestamp" => mktime(16, 0, 0, 10, 6, 2016),
                      "title" => "Manchester SIAM-IMA Student Chapter Committee 2016/2017",
                      "text" => "<p> We are delighted to announce this year's
                      <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">
                      organising committee</a> members: <p>
                      <ul>
                      <li> President: Mante Zemaityte </li>
                      <li> Vice President: Georgia Lynott </li>
                      <li> Secretary: Matthew Gwynne </li>
                      <li> Treasurer: Massimiliano Fasi </li>
                      <li> Webmaster: Jonathan Deakin</li>
                      </ul>"
                    ),
                    array(
                      "timestamp" => mktime( 22,30,43, 6, 1, 2016),
                      "title" => "<a href=\"julia16\">Manchester Julia Workshop 2016</a>",
                      "text" => "  The Manchester SIAM-IMA Student Chapter is proud to host the first Julia workshop in the UK,
                    			consisting of six plenary talks and two practical sessions led by core Julia contributors.
                    			The workshop aims to explain what Julia is, how to use it and how it is used in areas such as
                    			optimisation, natural language processing, statistics, materials science and computational biology.
                    			The <b>deadline for registration is 11 September</b>. The event is free for students but there is a
                    			registration fee of £25 for non-students. Click <a href=\"julia16\"> here </a> for more information."
                    ),
                    array(
                      "timestamp" => mktime( 22,30,43, 6, 1, 2016),
                      "title" => "Annual General Meeting 2016",
                      "text" => "<p>
                      At 5:30PM on Thursday the 9th of June we will be hosting our Annual General Meeting of the Chapter. The agenda for the meeting will consist of a review of this year's activities including plans for next year's events. It is an opportunity for you to have a say in the future activities. Free drinks and pizza will be served. Places are limited, please register <a href=\"agm16\">here</a>.
                      <p>"
                    ),
                    array(
                      "timestamp" => mktime( 9,41,00, 5, 12, 2016),
                      "title" => "Weijian Zhang wins SIAM Student Chapter Certificate of Recognition",
                      "text" => "<div class=\"col-md-4\"><img src=\"/~siam/msscc16/images/DSC04822-small.JPG\" class=\"img-responsive\" title=\"Weijin Zhang receiving the SIAM Student Chapter Certificate of Recognition\" /></div><div class=\"col-md-8\"> <p>We are pleased to announce that the Manchester SIAM Student Chapter president Weijian Zhang has been awarded the SIAM Student Chapter Certificate of Recognition, Congratulations! The award is to recognise Weijian's outstanding service and contribution to the chapter, and was presented at the closing of the 6th annual Manchester SIAM student Chapter conference.<p></div>"
                    ),
                    array(
                      "timestamp" => mktime( 14,30,43, 03, 17, 2016),
                      "title" => "Manchester SIAM Student Chapter Conference",
                      "text" => "<p>
                      We are pleased to announce the 6th Manchester SIAM Student Chapter Conference (MSSCC16). It will be held in Frank Adams 1, Alan Turing Building on 4th May 2016.</p>
                      <p>
                      This annual conference is a one-day event for all interested or working in applied or industrial mathematics. The event is organised for undergraduates, postgraduates and staff of the University of Manchester.
                      <p>
                      Up-to-date information and registration can be found on the <a href=\"msscc16\">conference website</a>.
                      <p>"
                    ),



              array(
                     "timestamp" => mktime( 13,49,33, 02, 23, 2016),
                     "title" => "Webmaster appointed",
                     "text" => "<p>Jonathan Deakin will be the new webmaster for the rest of the academic year 2015/2016. Congratulations!<p>",
                     "time" => mktime(00,00,00, 10, 5, 2016),
                    ),

				      array("timestamp" => mktime(16,25,30, 11, 27, 2015),
                           "title" => "Manchester SIAM Student Chapter Blog",
                           "text" => "Our <a href =\"http://manchestersiam.wordpress.com\">Manchester SIAM Student Chapter
                                        Blog</a> is live now!",
                           "time" => mktime(00,00,00, 25, 12, 2015),
                          ),

		     array(
			   "timestamp" => mktime( 13,49,33, 10, 31, 2015),
			   "title" => "Vice president election",
			   "text" => "<p>Mante Zemaityte will be the new vice president for the academic year 2015/2016. Congratulations!<p>",
			   "time" => mktime(00,00,00, 25,12,2015),
			   ),


          	     array(
			   "timestamp" => mktime( 16,32,33, 10, 01, 2015),
			   "title" => "Second Manchester SIAM Sabisu Challenge",
			   "text" => "<p>The second <a href =\"http://www.maths.manchester.ac.uk/~siam/sabisu1511\"> Manchester SIAM Sabisu Challenge</a>
                           is approaching. The event is organised for undergraduate and postgraduate students from the University of Manchester, and will be <b>on Wednesday, the 9th of December</b>.
                          </p>",
			   "time" => mktime(00,00,00, 25,12,2015),
			   ),

                     array(
                           "timestamp" => mktime( 9,38,00, 9,28,2015),
                           "title" => "Second Manchester SIAM Social Event 2015",
                           "text" =>
                           "<p> Our second <a href=\"http://maths.manchester.ac.uk/~siam/social1511/\">Manchester SIAM Social Event</a> is to be held <b>on Thursday, the 19th of November</b>. You are encouraged to join us to learn about the SIAM organisation, our chapter and future activities, and have some free pizza and drinks! </p><p>You need to be a member of the Chapter to attend this event. If you haven't joined us yet, you can <a href=\"http://www.maths.manchester.ac.uk/~siam/profile.php\"> register online</a> now!</p>",
                           "time" => mktime(00,00,00, 11, 19, 2015),
                           ),


		     array(
			   "timestamp" => mktime(12, 48, 12, 9, 21, 2015),
			   "title" => "Manchester SIAM Student Chapter Committee 2015/2016",
			   "text" => "<p> We are delighted to announce this year's
                             <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">
                             organising committee</a> members: <p>
                             <ul>
                               <li> President: Weijian Zhang </li>
                               <li> Secretary: Matthew Gwynne </li>
                               <li> Treasurer: Mario Berljafa </li>
                               <li> Webmaster: Massimiliano Fasi </li>
                             </ul>",
			   "time" => mktime(00,00,00, 10, 5, 2016),
			   ),

		     array(
			   "timestamp" => mktime( 14,00,30, 5, 13, 2015),
			   "title" => "Thanks and Photos",
			   "text" => "<p> A big thank you to those that have attened the
                             <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc15\">
                             Manchester SIAM Student Chapter Conference 2015 </a> and the
                             <a href =\"http://www.maths.manchester.ac.uk/~siam/sabisu1505\">
                             Manchester SIAM Sabisu Challenge </a>. There will be more events
                             like these in the next academic year! </p>
                             <p> Photos can be found at the event webpages. </p>",
			   "time" => mktime(00,00,00, 09,20,2015),
			   ),

          	     array(
			   "timestamp" => mktime( 18,00,30, 4, 15, 2015),
			   "title" => "Manchester SIAM Sabisu Challenge",
			   "text" => "<p> Hello everyone, </p>
                             <p>
                            We are happy to host our first ever
                            <a href =\"http://www.maths.manchester.ac.uk/~siam/sabisu1505\"> Manchester SIAM Sabisu Challenge </a>.
                           The event is organised for undergraduate and postgraduate students from the School of Mathematics and it
                          will be held at G.113, Alan Turing Building at <b> 10:00 am Thursday, 7th May, 2015 </b>.
                          </p>",
			   "time" => mktime(00,00,00, 09,20,2015),
			   ),

            	     array(
			   "timestamp" => mktime( 18,00,30, 3, 25, 2015),
			   "title" => "Manchester SIAM Student Chapter Conference 2015",
			   "text" =>
                         " <p>
                              Hello everyone,
                           </p>
		           <p>
			This year's SIAM Student Chapter Conference will take place on <b> Friday, 1st May, 2015. </b>
			For more information and registration, please visit <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc15\">the conference page</a>.
                              </p>",
			   "time" => mktime(00,00,00, 09,20,2015),
			   ),



		     array(
			   "timestamp" => mktime( 18,00,30, 2, 6, 2015),
			   "title" => "Manchester SIAM Social Event 2015",
			   "text" =>
			   " <p>
                             Our first <a href=\"http://maths.manchester.ac.uk/~siam/social1502/\">Manchester SIAM Social Event</a> was held on Friday, the 6th of February. We want to say a huge <b>thank you</b> to our members that joined us to learn about the SIAM organisation, our chapter and future activities, and some free pizza and drinks! You can see our gallery of the event <a href=\"http://maths.manchester.ac.uk/~siam/social1502?gallery\">here</a>.
                              </p>",
			   "time" => mktime(00,00,00, 09,20,2015),
			   ),



		     array(
			   "timestamp" => mktime( 8,27,30, 11,12, 2014),
			   "title" => "Chapter Participation in the Postgraduate Open Day and the SIAM Student Blog Post",
			   "text" =>
			   "<p>
                            Recently, the organising committee had participated in introducing the Chapter and SIAM
                            at the Postgraduate Open Day organised annually at the School of Mathematics! The president
                            and vice-president were invited to submit their accounts to SIAM, along with the promotional
                            poster created. You can find the blog post on the
                            <a href=\"http://connect.siam.org/category/siam-student-blog/\">SIAM student blog</a> and
                            <a href=\"http://connect.siam.org/wp-content/uploads/sites/3/2014/11/Machester_PG_day.png\">the promotional poster</a>.
                            </p>",
			   "time" => mktime(00,00,00, 09, 20, 2015),
			   ),



		     array(
			   "timestamp" => mktime(19, 22, 30, 10, 18, 2014),
			   "title" => "Manchester SIAM Student Chapter Committee 2014/2015",
			   "text" =>
			   "<p>
         We are delighted to announce this year's <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">
         organising committee</a> members: <p>
      <ul>
         <li> President: Sophia Bethany Coban </li>
         <li> Vice-President: Christopher Mower </li>
         <li> Secretary: Zehui Jin </li>
         <li> Treasurer: Mario Berljafa </li>
         <li> Webmaster: Weijian Zhang </li>
      </ul>
     ",
     "time" =>   mktime(22, 20, 59, 09, 20, 2015),
			   ),



		     array(
			   "timestamp" => mktime(18, 15, 54, 1, 10, 2014),
			   "title" => "Busy, Busy, Busy!",
			   "text" => "The new <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">organising committee</a> has been formed and the roles are delegated! <p>We are currently busy with organising the Annual Manchester SIAM Student Chapter 2014. If you have any suggestions for this year's chapter, or ideas for future events, please <a href=\"http://www.maths.manchester.ac.uk/~siam/contact.php\">contact us</a>.",
			   "time" => mktime(23, 59, 59, 5, 1, 2014),
			   ),



		     array(
			   "timestamp" => mktime(17, 51, 54, 3, 1, 2014),
			   "title" => "Annual Manchester SIAM Student Chapter Conference 2014",
			   "text" => "Ladies and gents! It is that time of the year for another SIAM Student Chapter Conference!<br>
<p>
This conference is a one-day event for all interested or working in applied or industrial mathematics. The event is organised for undergraduates, postgraduates and staff of University of Manchester, and will take place on <b>Friday, 2nd May, 2014</b>, in the <a href=\"http://www.mims.manchester.ac.uk/info/directions.html\">Alan Turing Building</a>.
<p>
We are looking for PhD students to present talks and posters throughout the day.
<p>
For more information and registration, please visit <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc14.php\">the conference page</a>.",
			   "time" => mktime(23, 59, 59, 5, 1, 2014),
			   ),
		     		     array(
			   "timestamp" => mktime(17, 19, 24, 4, 1, 2013),
			   "title" => "Annual Manchester SIAM Student Chapter Conference 2013",
			   "text" => "<a href=\"amsscc13.php\" class=\"img\" style=\"float: right;\"><img src=\"images/registration.png\" width=\"113\" height=\"119\" border=\"0\" align=\"right\" title=\"Register here\" /></a><p>A one day conference for all interested or working in applied or industrial mathematics, including undergraduates, postgraduates and staff, will take place on the <b>20th May 2013</b> in the Alan Turing Building, Manchester University.</p>
<p>We are looking for PhD students to present talks and posters throughout the day.</p>
<p>More information and registration page for the conference can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc13.php\">here</a>.</p>
",
			   "time" => mktime(23, 59, 59, 6, 8, 2013),
			   ),
		     array(
			   "timestamp" => mktime(17, 19, 24, 1, 7, 2013),
			   "title" => "SIAM Annual Meeting 2013",
			   "text" => "As part of the <a href=\"http://www.siam.org/meetings/an13/\">2013 SIAM Annual Meeting</a> in San Diego, California, <b> July 8 - 12, 2013</b> a representative of the University of Manchester Chapter of SIAM will present a paper during Student Days on <b>Tuesday, July 9, 2013</b> and represent the Chapter at the <i>Chapter Meeting with SIAM Leadership</i>.",
			   "time" => mktime(23, 59, 59, 7, 12, 2013),
			   ),

		     array(
			   ":timestamp" => mktime(17, 19, 23, 1, 7, 2013),
			   "title" => "SIAM Chapter Day",
			   "text" => "This one-day event will take place on <b>January 21<sup>st</sup>, 2013</b> at <a href=\"http://www.cardiff.ac.uk/maths/\">Cardiff School of Mathematics</a>. It is the first in an annual series which aims to provide a platform for discussions on current research on mathematical modelling, analysis, and simulation of problems in science and engineering.<br />More information on the event can be found <a href=\"http://www.cardiff.ac.uk/maths/research/researchgroups/applied/siam/siamday2013.html\">here</a>.",
			   "time" => mktime(23, 59, 59, 1, 21, 2013),
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "SIAM National Student Chapter Conference 2012",
			   "text" => "This conference aimed to bring PhD and postdoctoral applied mathematicians from a wide variety of research areas into one place. This one day event consisted of four plenary lectures, sixteen talks from PhDs and postdocs (split into two parallel sessions) and a poster session and there were prizes for the best talk and poster of the day.<br />More information on this conference can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/snscc12.php\">here</a>.",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "Advances in Numerical Computation: A Workshop in Honour of Sven Hammarling",
			   "text" => "This workshop was held on Tuesday, 5<sup>th</sup> July, 2011 and it presented recent advances in several areas of numerical computation. It was held to honour Sven Hammarling on the occasion of his 70<sup>th</sup> birthday. Sven is a <a href=\"http://www.maths.manchester.ac.uk/~sven/\">Senior Honorary Research Fellow</a> in the Numerical Analysis Group at the University of Manchester and a <a href=\"http://www.nag.co.uk/about/shammarling.asp\">Principal Consultant</a> at NAG.<br />More information on the workshop can be found <a href=\"http://www.mims.manchester.ac.uk/events/workshops/ANC11/index.php\">here</a>.",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "Second Manchester SIAM Student Chapter Conference, 20<sup>th</sup> May, 2011",
			   "text" => "This was a one day conference held in the Frank Adams Rooms 1 and 2, <a href=\"http://www.mims.manchester.ac.uk/info/directions.html\">Alan Turing Building</a>, University of Manchester and aimed at those interested or working in applied or industrial mathematics, including undergraduates, postgraduates and staff. The talks were aimed at a general applied maths background and were given by students and members of staff from in- and outside of the University of Manchester. This conference also included a <a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=pos2\">poster session</a>.<br />More information on the conference can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=cfn5\">here</a>.",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "SIAM Afternoon of Talks, 25<sup>th</sup> March, 2011",
			   "text" => "This meeting took place in Frank Adams Room 1.212,  <a href=\"http://www.mims.manchester.ac.uk/info/directions.html\">Alan Turing Building</a>, University of Manchester, at 2pm. The schedule can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=cfn3\">here</a>.",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "SIAM UKIE 2011",
			   "text" => "At <a href=\"http://www.mims.manchester.ac.uk/events/workshops/SIAM_UKIE_2011/\">the annual SIAM UKIE meeting</a>, Friday, 7<sup>th</sup> January, 2011, <a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=poss\">a poster session</a> was organized by the Manchester SIAM Student Chapter.",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "Annual Business Meeting",
			   "text" => "The annual business meeting was organized in Frank Adams Room 2, on Wednesday, 27<sup>th</sup> July, 2010, at 2pm.<br />Minutes can be found ".dwldLink("files/minutes2011.pdf", "here").".",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "Manchester SIAM Student Chapter Event",
			   "text" => "<a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=cfn2\">Manchester SIAM Student Chapter Event</a> was held on Thursday, 22<sup>nd</sup> July, 2010 and featured a talk by <a href=\"http://www.netlib.org/utk/people/JackDongarra/\">Jack Dongarra</a>: ".dwldLink("files/siam_student_chapter_talk22july.pdf", "Impact of Architecture and Technology for Extreme Scale on Software and Algorithm Design").".",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "Annual Business Meeting",
			   "text" => "The annual business meeting was organized on Tuesday, 4<sup>th</sup> May, 2010 at 12pm.<br />Minutes can be found ".dwldLink("files/minutes2010.pdf", "here").".",
			   "time" => 0,
			   ),
		     array(
			   "timestamp" => 0,
			   "title" => "First Manchester SIAM Student Chapter Conference",
			   "text" => " This was a one day conference held in the Alan Turing Building, University of Manchester, aimed at those interested or working in applied or industrial mathematics, including undergraduates, postgraduates and staff. There were talks given by students, faculty members, and industry. The talks were aimed at a general applied maths background.</br />More information on the conference can be found<a href=\"http://www.maths.manchester.ac.uk/~siam/old_site/index.php?lang=en&page=cfn1\">here</a>.",
			   "time" => 0,
			   ),
		     ),
		  ),

		 );

?>
