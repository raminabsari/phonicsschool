<?php
$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if ( file_exists( $root.'/wp-load.php' ) ) {
    require_once( $root.'/wp-load.php' );
//    require_once( $root.'/wp-config.php' );
} else {
	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	if ( file_exists( $root.'/wp-load.php' ) ) {
    require_once( $root.'/wp-load.php' );
//    require_once( $root.'/wp-config.php' );
	}
}
header("Content-type: text/css; charset=utf-8");
?>
@media only screen and (max-width: 1000px){
	<?php if (!empty($qode_options_proya['header_background_color'])) { ?>
	.header_bottom {
		background-color: <?php echo $qode_options_proya['header_background_color'];  ?>;
	}
	<?php } ?>
	<?php if (!empty($qode_options_proya['mobile_background_color'])) { ?>
		.header_bottom,
		nav.mobile_menu{
				background-color: <?php echo $qode_options_proya['mobile_background_color'];  ?> !important;
		}
	<?php } ?>
	<?php if (isset($qode_options_proya['blog_slider_box_width']) && $qode_options_proya['blog_slider_box_width']!== '' && $qode_options_proya['blog_slider_box_width'] <= 40){ ?>
		.blog_slider_holder .blog_slider.simple_slider .blog_text_holder_inner2{
			width: <?php echo $qode_options_proya['blog_slider_box_width']*1.3;?>%;
		}
	<?php }
	?>

	<?php if (isset($qode_options_proya['margin_after_title_responsive']) && $qode_options_proya['margin_after_title_responsive'] !== '' ) { ?>
		.content .container .container_inner.default_template_holder,
		.content .container .container_inner.page_container_inner
		{
		padding-top:<?php echo $qode_options_proya['margin_after_title_responsive'];  ?>px;
		}
	<?php } ?>
}
@media only screen and (min-width: 480px) and (max-width: 768px){
	
	<?php if (isset($qode_options_proya['parallax_minheight']) && !empty($qode_options_proya['parallax_minheight'])) { ?>
        section.parallax_section_holder{
		height: auto !important;
		min-height: <?php echo $qode_options_proya['parallax_minheight']; ?>px;
	}
	<?php } ?>
	
	<?php if (isset($qode_options_proya['header_background_color_mobile']) &&  !empty($qode_options_proya['header_background_color_mobile'])) { ?>
	header
	{
		 background-color: <?php echo $qode_options_proya['header_background_color_mobile'];  ?> !important;
		 background-image:none;
	}
	<?php } ?>
}

@media only screen and (max-width: 768px){
	
	<?php if (isset($qode_options_proya['footer_bottom_padding_right']) && $qode_options_proya['footer_bottom_padding_right'] != "" && $qode_options_proya['footer_bottom_padding_right'] > 10){ ?>
		.footer_bottom_holder{
			padding-right:10px;
		}
	<?php }
	if (isset($qode_options_proya['footer_bottom_padding_left']) && $qode_options_proya['footer_bottom_padding_left'] != "" && $qode_options_proya['footer_bottom_padding_left'] > 10){ ?>
		.footer_bottom_holder{
			padding-left:10px;
		}
	<?php } ?>

	<?php if (isset($qode_options_proya['blog_slider_box_width']) && $qode_options_proya['blog_slider_box_width'] !== '' && $qode_options_proya['blog_slider_box_width'] < 65){ ?>
		.blog_slider_holder .blog_slider.simple_slider .blog_text_holder_inner2{
			width: 65%;
		}
	<?php }
	?>
}
@media only screen and (max-width: 480px){

	<?php if (isset($qode_options_proya['parallax_minheight']) && !empty($qode_options_proya['parallax_minheight'])) { ?>
	section.parallax_section_holder{
		height: auto !important;
		min-height: <?php echo $qode_options_proya['parallax_minheight']; ?>px;
	}
	<?php } ?>


	<?php if (isset($qode_options_proya['header_background_color_mobile']) &&  !empty($qode_options_proya['header_background_color_mobile'])) { ?>
	header
	{
		 background-color: <?php echo $qode_options_proya['header_background_color_mobile'];  ?> !important;
		 background-image:none;
	}
	<?php } ?>
	<?php if (isset($qode_options_proya['footer_bottom_text_line_height']) && $qode_options_proya['footer_bottom_text_line_height'] !== "") { ?>
		.footer_bottom{
		line-height: <?php echo $qode_options_proya['footer_bottom_text_line_height']; ?>px;
		}
	<?php } ?>

	<?php if (isset($qode_options_proya['footer_top_padding_right']) && $qode_options_proya['footer_top_padding_right'] != "" && $qode_options_proya['footer_top_padding_right'] > 10){ ?>
		.footer_top.footer_top_full{
			padding-right:10px;
		}
	<?php }
	if (isset($qode_options_proya['footer_top_padding_left']) && $qode_options_proya['footer_top_padding_left'] != "" && $qode_options_proya['footer_top_padding_left'] > 10){ ?>
		.footer_top.footer_top_full{
			padding-left:10px;
		}
	<?php } ?>
}