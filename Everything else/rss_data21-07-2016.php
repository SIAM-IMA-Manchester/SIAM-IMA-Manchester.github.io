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
  "info" => array(
    "title" => "Information",
    "items" => array(
      array(
        "timestamp" => mktime( 22,30,43, 6, 1, 2016),
        "title" => "Annual General Meeting 2016",
        "text" => "<p>
        At 5:30PM on Thursday the 9th of June we will be hosting our Annual General Meeting of the Chapter. The agenda for the meeting will consist of a review of this year's activities including plans for next year's events. It is an opportunity for you to have a say in the future activities. Free drinks and pizza will be served. Places are limited, please register <a href=\"agm16\">here</a>. 
        <p>",
        "time" => mktime(9,00,00, 10, 5, 2016),
      ),
      array(
        "timestamp" => mktime( 9,41,00, 5, 12, 2016),
        "title" => "Weijian Zhang wins SIAM Student Chapter Certificate of Recognition",
        "text" => "<div class=\"col-md-4\"><img src=\"/~siam/msscc16/images/DSC04822-small.JPG\" class=\"img-responsive\" title=\"Weijin Zhang receiving the SIAM Student Chapter Certificate of Recognition\" /></div><div class=\"col-md-8\"> <p>We are pleased to announce that the Manchester SIAM Student Chapter president Weijian Zhang has been awarded the SIAM Student Chapter Certificate of Recognition, Congratulations! The award is to recognise Weijian's outstanding service and contribution to the chapter, and was presented at the closing of the 6th annual Manchester SIAM student Chapter conference.<p></div>",
        "time" => mktime(00,00,00, 10, 5, 2016),
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
        <p>",
        "time" => mktime(9,00,00, 10, 5, 2016),
      ),
      array(
        "timestamp" => mktime( 13,49,33, 02, 23, 2016),
        "title" => "Webmaster appointed",
        "text" => "<p>Jonathan Deakin will be the new webmaster for the rest of the academic year 2015/2016. Congratulations!<p>",
        "time" => mktime(00,00,00, 10, 5, 2016),
      ),
      
      array(
        "timestamp" => mktime(16,25,30, 11, 27, 2015),
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
        "time" => mktime(00,00,00, 09,20,2015)
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
        "time" => mktime(00,00,00, 09,20,2015)
      ),
      
      
      
      array(
        "timestamp" => mktime( 18,00,30, 2, 6, 2015),
        "title" => "Manchester SIAM Social Event 2015",
        "text" =>
        " <p>
        Our first <a href=\"http://maths.manchester.ac.uk/~siam/social1502/\">Manchester SIAM Social Event</a> was held on Friday, the 6th of February. We want to say a huge <b>thank you</b> to our members that joined us to learn about the SIAM organisation, our chapter and future activities, and some free pizza and drinks! You can see our gallery of the event <a href=\"http://maths.manchester.ac.uk/~siam/social1502?gallery\">here</a>.
        </p>",
        "time" => mktime(00,00,00, 09,20,2015)
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
        "time" => mktime(00,00,00, 09, 20, 2015)
      ),
      
      
      
      array(
        "timestamp" => mktime(19, 22, 30, 10, 18, 2014),
        "title" => "Manchester SIAM Student Chapter Committee 2014/2015",
        "text" => 
        "<p> 
        We are delighted to announce this year's <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">
        organising committee</a> members: </p>
        <ul>
        <li> President: Sophia Bethany Coban </li>
        <li> Vice-President: Christopher Mower </li>
        <li> Secretary: Zehui Jin </li>
        <li> Treasurer: Mario Berljafa </li>
        <li> Webmaster: Weijian Zhang </li>
        </ul>   
        ",
        "time" =>   mktime(22, 20, 59, 09, 20, 2015)
      ),
      
      
      
      array(
        "timestamp" => mktime(18, 15, 54, 1, 10, 2014),
        "title" => "Busy, Busy, Busy!",
        "text" => 
        "The new <a href=\"http://www.maths.manchester.ac.uk/~siam/committee.php\">organising committee</a> has been formed and the roles are delegated! <p>We are currently busy with organising the Annual Manchester SIAM Student Chapter 2014. If you have any suggestions for this year's chapter, or ideas for future events, please <a href=\"http://www.maths.manchester.ac.uk/~siam/contact.php\">contact us</a>.
        ",
        "time" => mktime(23, 59, 59, 5, 1, 2014)
      ), 	
      
      
      
      array(
        "timestamp" => mktime(17, 51, 54, 3, 1, 2014),
        "title" => "Annual Manchester SIAM Student Chapter Conference 2014",
        "text" => "Ladies and gents! It is that time of the year for another SIAM Student Chapter Conference!<br>
        <p>
        This conference is a one-day event for all interested or working in applied or industrial mathematics. The event is organised for undergraduates, postgraduates and staff of University of Manchester, and will take place on <b>Friday, 2nd May, 2014</b>, in the <a href=\"http://www.mims.manchester.ac.uk/info/directions.html\">Alan Turing Building</a>.
        </p>
        <p>
        We are looking for PhD students to present talks and posters throughout the day.
        </p>
        <p>
        For more information and registration, please visit <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc14.php\">the conference page</a>.",
        "time" => mktime(23, 59, 59, 5, 1, 2014)
      ),
      
      array(
        "timestamp" => mktime(17, 19, 24, 4, 1, 2013),
        "title" => "Annual Manchester SIAM Student Chapter Conference 2013",
        "text" => "<a href=\"amsscc13.php\" class=\"img\" style=\"float: right;\"><img src=\"images/registration.png\" width=\"113\" height=\"119\" border=\"0\" align=\"right\" title=\"Register here\" /></a><p>A one day conference for all interested or working in applied or industrial mathematics, including undergraduates, postgraduates and staff, will take place on the <b>20th May 2013</b> in the Alan Turing Building, Manchester University.</p>
        <p>We are looking for PhD students to present talks and posters throughout the day.</p>
        <p>More information and registration page for the conference can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/amsscc13.php\">here</a>.</p>",
        "time" => mktime(23, 59, 59, 6, 8, 2013)
      ),
      array(
        "timestamp" => mktime(17, 19, 24, 1, 7, 2013),
        "title" => "SIAM Annual Meeting 2013",
        "text" => "As part of the <a href=\"http://www.siam.org/meetings/an13/\">2013 SIAM Annual Meeting</a> in San Diego, California, <b> July 8 - 12, 2013</b> a representative of the University of Manchester Chapter of SIAM will present a paper during Student Days on <b>Tuesday, July 9, 2013</b> and represent the Chapter at the <i>Chapter Meeting with SIAM Leadership</i>.",
        "time" => mktime(23, 59, 59, 7, 12, 2013)
      ),
      
      array(
        "timestamp" => mktime(17, 19, 23, 1, 7, 2013),
        "title" => "SIAM Chapter Day",
        "text" => "This one-day event will take place on <b>January 21<sup>st</sup>, 2013</b> at <a href=\"http://www.cardiff.ac.uk/maths/\">Cardiff School of Mathematics</a>. It is the first in an annual series which aims to provide a platform for discussions on current research on mathematical modelling, analysis, and simulation of problems in science and engineering.<br />More information on the event can be found <a href=\"http://www.cardiff.ac.uk/maths/research/researchgroups/applied/siam/siamday2013.html\">here</a>.",
        "time" => mktime(23, 59, 59, 1, 21, 2013)
      ),
      array(
        "timestamp" => 0,
        "title" => "SIAM National Student Chapter Conference 2012",
        "text" => "This conference aimed to bring PhD and postdoctoral applied mathematicians from a wide variety of research areas into one place. This one day event consisted of four plenary lectures, sixteen talks from PhDs and postdocs (split into two parallel sessions) and a poster session and there were prizes for the best talk and poster of the day.<br />More information on this conference can be found <a href=\"http://www.maths.manchester.ac.uk/~siam/snscc12.php\">here</a>.",
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
