<?php

//Testimonials

$qodeTestimonials = new QodeMetaBox("testimonials", "Qode Testimonials");
$qodeFramework->qodeMetaBoxes->addMetaBox("testimonials",$qodeTestimonials);

	$qode_testimonial_author = new QodeMetaField("text","qode_testimonial-author","","Author","Enter the author name");
	$qodeTestimonials->addChild("qode_testimonial-author",$qode_testimonial_author);

	$qode_testimonial_text = new QodeMetaField("textarea","qode_testimonial-text","","Text","Enter the testimonial text");
	$qodeTestimonials->addChild("qode_testimonial-text",$qode_testimonial_text);

	$qode_testimonial_website = new QodeMetaField("text","qode_testimonial_website","","Website","Enter full URL of the author's website");
	$qodeTestimonials->addChild("qode_testimonial_website",$qode_testimonial_website);