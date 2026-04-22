---
layout: default
title: Home
navigation: 0
---

The University of Manchester SIAM-IMA Student Chapter encourages the promotion of applied mathematics and computational science to students, especially, but not limited to, graduate students.
The Chapter was set up in December 2009, and is run by a committee of PhD students at [The University of Manchester](http://www.manchester.ac.uk), with help from our Faculty Advisors [David Silvester](http://www.maths.manchester.ac.uk/djs/) and [Marcus Webb](https://personalpages.manchester.ac.uk/staff/marcus.webb/).

After a period of inactivity, the student chapter is up and running again!

## Latest Seminars

<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
{% assign seminars = site.posts | where: "category", "seminar" | sort: "date" | reverse | slice: 0, 3 %}
{% for post in seminars %}
  <div class="col">
    <div class="card h-100">
      {% if post.speaker.image %}
      <img src="{{ post.speaker.image | relative_url }}" class="card-img-top" alt="{{ post.speaker.name }}" style="object-fit: cover; height: 200px;">
      {% endif %}
      <div class="card-body">
        <h5 class="card-title"><a href="{{ post.url | relative_url }}" class="text-decoration-none">{{ post.title }}</a></h5>
        <p class="card-text text-muted mb-1">{{ post.date | date: "%B %-d, %Y" }}</p>
        {% if post.speaker %}
        <p class="card-text mb-1"><strong>{{ post.speaker.name }}</strong>{% if post.speaker.affiliation %}, {{ post.speaker.affiliation }}{% endif %}</p>
        {% endif %}
        {% if post.abstract %}<p class="card-text">{{ post.abstract | truncatewords: 20 }}</p>{% endif %}
      </div>
      <div class="card-footer">
        <a href="{{ post.url | relative_url }}" class="btn btn-sm bg-manchester-purple text-white">Read more</a>
      </div>
    </div>
  </div>
{% endfor %}
</div>

<a href="{{ '/seminars/' | relative_url }}" class="btn bg-manchester-purple text-white">View all seminars</a>

## SIAM and IMA

A student membership for [SIAM](https://www.siam.org/) is free of charge for all PhD students at the University of Manchester.
If you are not yet a member, you can apply [here](https://www.siam.org/membership/individual-membership/).
Information on student memberships for the IMA can be found [here](https://ima.org.uk/membership/membership-grades/student/).

<div style="text-align: center;">
    <img class="mx-auto d-block mw-100" src="assets/images/46-alan-turing.jpg" alt="The Alan Turing Building, University of Manchester">
</div>
