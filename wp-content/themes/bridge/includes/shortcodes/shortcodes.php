<?php
if (!function_exists('register_button')){
    function register_button( $buttons ){
        array_push( $buttons, "|", "qode_shortcodes" );
        return $buttons;
    }
}

if (!function_exists('add_plugin')){
    function add_plugin( $plugin_array ) {
        $plugin_array['qode_shortcodes'] = get_template_directory_uri() . '/includes/shortcodes/qode_shortcodes.js';
        return $plugin_array;
    }
}

if (!function_exists('qode_shortcodes_button')){
    function qode_shortcodes_button(){
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', 'add_plugin' );
            add_filter( 'mce_buttons', 'register_button' );
        }
    }
}
add_action('init', 'qode_shortcodes_button');


if (!function_exists('num_shortcodes')){
    function num_shortcodes($content){
        $columns = substr_count( $content, '[pricing_cell' );
        return $columns;
    }
}

/* Action shortcode */

if (!function_exists('action')) {
    function action($atts, $content = null) {
        $args = array(
            "type"						        => "normal",
            "full_width"                        => "yes",
            "content_in_grid"                   => "yes",
            "icon"						        => "",
            "icon_size"					        => "fa-2x",
            "icon_color"				        => "",
            "custom_icon"				        => "",
            "background_color"                  => "",
            "background_image"                  => "",
            "use_background_as_pattern"         => "",
            "border_color"                      => "",
			"padding_top"						=> "",
			"padding_bottom"					=> "",
            "show_button"                       => "yes",
            "button_size"                       => "",
            "button_link"                       => "",
            "button_text"                       => "",
            "button_target"                     => "",
            "button_text_color"                 => "",
            "button_hover_text_color"           => "",
            "button_background_color"           => "",
            "button_hover_background_color"     => "",
            "button_border_color"               => "",
            "button_hover_border_color"         => "",
            "text_color"                        => "", //used only when shortcode is called from call to action widget
            "text_size"                         => "",
			"text_font_weight"					=> "",
			"text_letter_spacing"				=> ""
        );

        extract(shortcode_atts($args, $atts));

        $html                   = '';
        $action_classes         = '';
        $action_styles          = '';
        $text_wrapper_classes   = '';
        $button_styles          = '';
        $icon_styles			= '';
        $data_attr              = '';
        $content_styles         = '';

        if($show_button == 'yes') {
            $text_wrapper_classes   .= 'column1';
        }

		$action_classes .= $type;

        if($background_color != '') {
            $action_styles .= 'background-color: '.$background_color.';';
        }

		if($padding_top != '') {
			$action_styles .= 'padding-top: '.$padding_top.'px;';
		}

		if($padding_bottom != '') {
			$action_styles .= 'padding-bottom: '.$padding_bottom.'px;';
		}

        if($border_color != '') {
            $action_styles .= 'border-top: 1px solid '.$border_color.';';
        }

		if($background_image !== '') {
			$background_image_src = is_numeric($background_image) ? wp_get_attachment_url($background_image) : $background_image;

			$action_classes = ' with_background_image';
			$action_styles = 'background-image: url('.$background_image_src.');';

			if($use_background_as_pattern == 'yes') {
				$action_styles .= 'background-repeat: repeat;';
			}
		}

        if($button_text_color != '') {
            $button_styles .= 'color: '.$button_text_color.';';
        }
        if($icon_color != "") {
            $icon_styles = " style='color: ".$icon_color . ";'";
        }
        if($button_border_color != '') {
            $button_styles .= 'border-color: '.$button_border_color.';';
        }

        if($button_background_color != '') {
            $button_styles .= "background-color: {$button_background_color};";

        }

        if($button_hover_background_color != "") {
            $data_attr .= "data-hover-background-color=".$button_hover_background_color." ";
        }

        if($button_hover_border_color != "") {
            $data_attr .= "data-hover-border-color=".$button_hover_border_color." ";
        }

        if($button_hover_text_color != "") {
            $data_attr .= "data-hover-color=".$button_hover_text_color;
        }

        if($full_width == "no") {
            $html .= '<div class="container_inner">';
        }

        $html.=  '<div class="call_to_action '.$action_classes.'" style="'.$action_styles.'">';

        if($content_in_grid == 'yes' && $full_width == 'yes') {
            $html .= '<div class="container_inner">';
        }

        if($show_button == 'yes') {
            $html .= '<div class="two_columns_75_25 clearfix">';
        }

        if($text_size != '') {
            $content_styles .= 'font-size:'. $text_size.'px;';
        }

        if($text_color != '') {
            $content_styles .= 'color:'.$text_color.';';
        }

		if($text_font_weight !== '') {
			$content_styles .= 'font-weight: '.$text_font_weight.';';
		}

		if($text_letter_spacing != '') {
			$content_styles .= 'letter-spacing: '.$text_letter_spacing.'px;';
		}

        $html .= '<div class="text_wrapper '.$text_wrapper_classes.'">';

        if($type == "with_icon"){
            $html .= '<div class="call_to_action_icon_holder">';
            $html .= '<div class="call_to_action_icon">';
            $html .= '<div class="call_to_action_icon_inner">';
            if($custom_icon != "") {
                if(is_numeric($custom_icon)) {
                    $custom_icon_src = wp_get_attachment_url( $custom_icon );
                } else {
                    $custom_icon_src = $custom_icon;
                }

                $html .= '<img src="' . $custom_icon_src . '" alt="">';
            } else {
                $html .= "<i class='fa ".$icon." pull-left . ". $icon_size . "'".$icon_styles."></i>";
            }

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '<div class="call_to_action_text" style="'.$content_styles.'">'.$content.'</div>';
        $html .= '</div>'; //close text_wrapper

        if($show_button == 'yes') {
            $button_link = ($button_link != '') ? $button_link : 'javascript: void(0)';

            $html .= '<div class="button_wrapper column2">';
            $html .= '<a href="'.$button_link.'" class="qbutton white '. $button_size . '" target="'.$button_target.'" style="'.$button_styles.'"'. $data_attr . '>'.$button_text.'</a>';
            $html .= '</div>';//close button_wrapper
        }

        if($show_button == 'yes') {
            $html .= '</div>'; //close two_columns_75_25 if opened
        }

        if($content_in_grid == 'yes' && $full_width == 'yes') {
            $html .= '</div>'; // close .container_inner if oppened
        }

        $html .= '</div>';//close .call_to_action

        if($full_width == 'no') {
            $html .= '</div>'; // close .container_inner if oppened
        }

        return $html;
    }
}
add_shortcode('action', 'action');

/* Accordion shortcode */

if (!function_exists('accordion')) {
    function accordion($atts, $content = null) {
        extract(shortcode_atts(array("accordion_type"=>""), $atts));
        return "<div class='q_accordion_holder $accordion_type clearfix'>" . $content . "</div>";
    }
}
add_shortcode('accordion', 'accordion');

/* Accordion item shortcode */

if (!function_exists('accordion_item')) {
    function accordion_item($atts, $content = null) {
        extract(shortcode_atts(array("caption"=>"","title_color"=>"","icon"=>"","icon_color"=>"","background_color"=>""), $atts));
        $html           = '';
        $heading_styles = '';
        $no_icon        = '';

        if($icon == "") {
            $no_icon = 'no_icon';
        }

        if($title_color != "") {
            $heading_styles .= "color: ".$title_color.";";
        }

        if($background_color != "") {
            $heading_styles .= " background-color: ".$background_color.";";
        }

        $html .= "<h5 style='".$heading_styles."'>";
        if($icon != "") {
            $html .= '<div class="icon-wrapper"><i class="fa '.$icon.'" style="color: '.$icon_color.';"></i></div>';
        }
        $html .= "<div class='accordion_mark'></div><span class='tab-title'>".$caption."</span><span class='accordion_icon_mark'></span></h5><div class='accordion_content ".$no_icon."'><div class='accordion_content_inner'>" . $content . "</div></div>";

        return $html;
    }
}
add_shortcode('accordion_item', 'accordion_item');


/* Blockquote item shortcode */

if (!function_exists('blockquote')) {
    function blockquote($atts, $content = null) {
        $args = array(
            "text"              => "",
            "text_color"        => "",
            "width"             => "",
            "line_height"       => "",
            "background_color"  => "",
            "border_color"      => "",
            "quote_icon_color"  => "",
            "show_quote_icon"   => ""
        );

        extract(shortcode_atts($args, $atts));

        //init variables
        $html               = "";
        $blockquote_styles  = "";
        $blockquote_classes = "";
        $heading_styles     = "";
        $quote_icon_styles  = "";

        if($show_quote_icon == 'yes') {
            $blockquote_classes .= ' with_quote_icon';
        }

        if($width != "") {
            $blockquote_styles .= "width: ".$width."%;";
        }

        if($border_color != "") {
            $blockquote_styles .= "border-left-color: ".$border_color.";";
        }

        if($background_color != "") {
            $blockquote_styles .= "background-color: ".$background_color.";";
        }

        if($text_color != "") {
            $heading_styles .= "color: ".$text_color.";";
        }

        if($line_height != "") {
            $heading_styles .= " line-height: ".$line_height."px;";
        }

        if($quote_icon_color != "") {
            $quote_icon_styles .= "color: ".$quote_icon_color.";";
        }

        $html .= "<blockquote class='".$blockquote_classes."' style='".$blockquote_styles."'>"; //open blockquote
        if($show_quote_icon == 'yes') {
            $html .= "<i class='fa fa-quote-right pull-left' style='".$quote_icon_styles."'></i>";
        }

        $html .= "<h5 class='blockquote-text' style='".$heading_styles."'>".$text."</h5>";
        $html .= "</blockquote>"; //close blockquote
        return $html;
    }
}
add_shortcode('blockquote', 'blockquote');

/* Button shortcode */

if (!function_exists('button')) {
    function button($atts, $content = null) {
        global $qode_options_proya;

        $args = array(
            "size"                      => "",
            "style"                      => "",
            "text"                      => "",
            "icon"                      => "",
            "icon_color"                => "",
            "link"                      => "",
            "target"                    => "_self",
            "button_id"                 => "",
            "color"                     => "",
            "hover_color"               => "",
            "background_color"			=> "",
            "hover_background_color"    => "",
            "border_color"              => "",
            "hover_border_color"        => "",
            "font_style"                => "",
            "font_weight"               => "",
            "text_align"                => "",
            "margin"					=> "",
            "border_radius"				=> ""
        );

        extract(shortcode_atts($args, $atts));

        if($target == ""){
            $target = "_self";
        }

        //init variables
        $html  = "";
        $button_classes = "qbutton ";
        $button_styles  = "";
        $add_icon       = "";
        $data_attr      = "";

        if($size != "") {
            $button_classes .= " {$size}";
        }

        if($text_align != "") {
            $button_classes .= " {$text_align}";
        }
        if($style == "white") {
            $button_classes .= " {$style}";
        }
        if($color != ""){
            $button_styles .= 'color: '.$color.'; ';
        }

        if($border_color != ""){
            $button_styles .= 'border-color: '.$border_color.'; ';
        }

        if($font_style != ""){
            $button_styles .= 'font-style: '.$font_style.'; ';
        }

        if($font_weight != ""){
            $button_styles .= 'font-weight: '.$font_weight.'; ';
        }

        if($icon != ""){
            $icon_style = "";
            if($icon_color != ""){
                $icon_style .= 'color: '.$icon_color.';';
            }
            $add_icon .= '<i class="fa '.$icon.'" style="'.$icon_style.'"></i>';
        }

        if($margin != ""){
            $button_styles .= 'margin: '.$margin.'; ';
        }

		if($border_radius != ""){
			$button_styles .= 'border-radius: '.$border_radius.'px;-moz-border-radius: '.$border_radius.'px;-webkit-border-radius: '.$border_radius.'px; ';
		}

        if($background_color != "" ) {
            $button_styles .= "background-color: {$background_color};";
        }

        if($hover_background_color != "") {
            $data_attr .= "data-hover-background-color=".$hover_background_color." ";
        }

        if($hover_border_color != "") {
            $data_attr .= "data-hover-border-color=".$hover_border_color." ";
        }

        if($hover_color != "") {
            $data_attr .= "data-hover-color=".$hover_color;
        }

        $html .=  '<a '.qode_get_inline_attr($button_id, 'id').' href="'.$link.'" target="'.$target.'" '.$data_attr.' class="'.$button_classes.'" style="'.$button_styles.'">'.$text.$add_icon.'</a>';

        return $html;
    }
}
add_shortcode('button', 'button');

/* Counter shortcode */

if (!function_exists('counter')) {
    function counter($atts, $content = null) {
        $args = array(
            "type"              		=> "",
            "box"               		=> "",
            "box_border_color"  		=> "",
            "position"          		=> "",
            "digit"             		=> "",
            "font_size"         		=> "",
            "font_weight"       		=> "",
            "font_color"        		=> "",
            "text"              		=> "",
            "text_size"         		=> "",
            "text_font_weight"  		=> "",
            "text_transform"    		=> "",
            "text_color"        		=> "",
            "separator"         		=> "",
            "separator_color"   		=> "",
			"separator_transparency" 	=> ""
        );

        extract(shortcode_atts($args, $atts));

        //init variables
        $html                   = "";
        $counter_holder_classes = "";
        $counter_classes        = "";
        $counter_styles         = "";
        $text_styles            = "";
        $separator_styles       = "";

        if($position != "") {
            $counter_holder_classes .= " ".$position;
        }

        if($box == "yes") {
            $counter_holder_classes .= " boxed_counter";
        }

        if($type != "") {
            $counter_classes .= " ".$type;
        }

        if($font_color != "") {
            $counter_styles .= "color: ".$font_color.";";
        }

        if($font_size != "") {
            $counter_styles .= "font-size: ".$font_size."px;";
        }
		if($font_weight != "") {
			$counter_styles .= "font-weight: ".$font_weight.";";
		}
        if($text_size != "") {
            $text_styles .= "font-size: ".$text_size."px;";
        }
		if($text_font_weight != "") {
            $text_styles .= "font-weight: ".$text_font_weight.";";
        }
		if($text_transform != "") {
			$text_styles .= "text-transform: ".$text_transform.";";
        }

        if($text_color != "") {
            $text_styles .= "color: ".$text_color.";";
        }

        if($separator_color != "") {
			if($separator_transparency !== '') {
				$rgba_color = qode_rgba_color($separator_color, $separator_transparency);
				$separator_styles .= "background-color: ".$rgba_color.';';
			} else {
				$separator_styles .= "background-color: ".$separator_color.";";
			}
        }

        $html .= '<div class="q_counter_holder '.$counter_holder_classes.'">';
        $html .= '<span class="counter '.$counter_classes.'" style="'.$counter_styles.'">'.$digit.'</span>';

        if($separator == "yes") {
            $html .= '<span class="separator small" style="'.$separator_styles.'"></span>';
        }

        $html .= $content;

        if($text != "") {
            $html .= '<p class="counter_text" style="'.$text_styles.'">'.$text.'</p>';
        }

        $html .= '</div>'; //close q_counter_holder

        return $html;
    }
}
add_shortcode('counter', 'counter');

/* Custom font shortcode */

if (!function_exists('custom_font')) {
    function custom_font($atts, $content = null) {
        $args = array(
            "font_family"       => "",
            "font_size"         => "",
            "line_height"       => "",
            "font_style"        => "",
            "font_weight"       => "",
            "color"             => "",
            "text_decoration"   => "",
            "text_shadow"       => "",
            "letter_spacing"    => "",
            "background_color"  => "",
            "padding"           => "",
            "margin"            => "",
			"border_color"		=> "",
			"border_width"		=> "",
            "text_align"        => "left"
        );
        extract(shortcode_atts($args, $atts));

        $html = '';
        $html .= '<div class="custom_font_holder" style="';
        if($font_family != "") {
            $html .= 'font-family: '.$font_family.';';
        }

        if($font_size != "") {
            $html .= ' font-size: '.$font_size.'px;';
        }

        if($line_height != "") {
            $html .= ' line-height: '.$line_height.'px;';
        }

        if($font_style != "") {
            $html .= ' font-style: '.$font_style.';';
        }

        if($font_weight != "") {
            $html .= ' font-weight: '.$font_weight.';';
        }

        if($color != ""){
            $html .= ' color: '.$color.';';
        }

        if($text_decoration != "") {
            $html .= ' text-decoration: '.$text_decoration.';';
        }

        if($text_shadow == "yes") {
            $html .= ' text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);';
        }

        if($letter_spacing != "") {
            $html .= ' letter-spacing: '.$letter_spacing.'px;';
        }

        if($background_color != "") {
            $html .= ' background-color: '.$background_color.';';
        }

        if($padding != "") {
            $html .= ' padding: '.$padding.';';
        }

        if($margin != "") {
            $html .= ' margin: '.$margin.';';
        }

		$border = '';
		if($border_color != '') {
			$border .= 'border: 1px solid '.$border_color.';';

			if($border_width != '') {
				$border .= 'border-width: '.$border_width.'px;';
			}
		} elseif($border_width != '') {
			$border .= 'border: '.$border_width.'px solid;';
		}

		$html .= $border;

        $html .= ' text-align: ' . $text_align . ';';
        $html .= '">'.$content.'</div>';
        return $html;
    }
}
add_shortcode('custom_font', 'custom_font');

/* Cover Boxes shortcode */

if (!function_exists('cover_boxes')) {
    function cover_boxes($atts, $content = null) {
        $args = array(
            "active_element"    => "1",
            "title1"            => "",
            "title_color1"      => "",
            "text1"             => "",
            "text_color1"       => "",
            "image1"            => "",
            "link1"             => "",
            "link_label1"       => "",
            "target1"      => "",
            "title2"            => "",
            "title_color2"      => "",
            "text2"             => "",
            "text_color2"       => "",
            "image2"            => "",
            "link2"             => "",
            "link_label2"       => "",
            "target2"      => "",
            "title3"            => "",
            "title_color3"      => "",
            "text3"             => "",
            "text_color3"       => "",
            "image3"            => "",
            "link3"             => "",
            "link_label3"       => "",
            "target3"      => "",
            "read_more_button_style"      => ""
        );
        extract(shortcode_atts($args, $atts));

        $html = "";
        $html .= "<div class='cover_boxes' data-active-element='".$active_element."'><ul class='clearfix'>";

        $html .= "<li>";
        $html .= "<div class='box'>";
        if($target1 != ""){
            $target1 = $target1;
        }else{
            $target1 = "_self";
        }
        if(is_numeric($image1)) {
            $image_src1 = wp_get_attachment_url( $image1 );
        }else {
            $image_src1 = $image1;
        }
        if(is_numeric($image2)) {
            $image_src2 = wp_get_attachment_url( $image2 );
        }else {
            $image_src2 = $image2;
        }
        if(is_numeric($image3)) {
            $image_src3 = wp_get_attachment_url( $image3 );
        }else {
            $image_src3 = $image3;
        }
        $html .= "<a class='thumb' href='".$link1."' target='".$target1."'><img alt='".$title1."' src='".$image_src1."' /></a>";
        if($title_color1 != ""){
            $color1 = " style='color:".$title_color1."''";
        }else{
            $color1 = "";
        }
        if($text_color1 != ""){
            $t_color1 = " style='color:".$text_color1."''";
        }else{
            $t_color1 = "";
        }
        $html .= "<div class='box_content'><h3 ".$color1.">".$title1."</h3>";
        $html .= "<p ".$t_color1.">".$text1."</p>";

		$button_class = "";
		$button_class_wrapper_open = "";
		$button_class_wrapper_close = "";
		if($read_more_button_style != "no"){
			$button_class = "qbutton tiny";
		}else {
			$button_class = "cover_boxes_read_more";
			$button_class_wrapper_open = "<h5>";
			$button_class_wrapper_close = "</h5>";
		}

        if($link_label1 != "") {
            $html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link1."' target='".$target1."'>".$link_label1."</a>" . $button_class_wrapper_close;
        }

        $html .= "</div></div>";
        $html .= "</li>";

        $html .= "<li>";
        $html .= "<div class='box'>";
        if($target2 != ""){
            $target2 = $target2;
        }else{
            $target2 = "_self";
        }
        $html .= "<a class='thumb' href='".$link2."' target='".$target2."'><img alt='".$title2."' src='".$image_src2."' /></a>";
        if($title_color2 != ""){
            $color2 = " style='color:".$title_color2."''";
        }else{
            $color2 = "";
        }
        if($text_color2 != ""){
            $t_color2 = " style='color:".$text_color2."''";
        }else{
            $t_color2 = "";
        }
        $html .= "<div class='box_content'><h3 ".$color2.">".$title2."</h3>";
        $html .= "<p ".$t_color2.">".$text2."</p>";

        if($link_label2 != "") {
            $html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link2."' target='".$target2."'>".$link_label2."</a>" . $button_class_wrapper_close;
        }

        $html .= "</div></div>";
        $html .= "</li>";

        $html .= "<li>";
        $html .= "<div class='box'>";
        if($target3 != ""){
            $target3 = $target3;
        }else{
            $target3 = "_self";
        }
        $html .= "<a class='thumb' href='".$link3."' target='".$target3."'><img alt='".$title3."' src='".$image_src3."' /></a>";
        if($title_color3 != ""){
            $color3 = " style='color:".$title_color3."''";
        }else{
            $color3 = "";
        }
        if($text_color3 != ""){
            $t_color3 = " style='color:".$text_color3."''";
        }else{
            $t_color3 = "";
        }
        $html .= "<div class='box_content'><h3 ".$color3.">".$title3."</h3>";
        $html .= "<p ".$t_color3.">".$text3."</p>";

        if($link_label3 != "") {
            $html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link3."' target='".$target3."'>".$link_label3."</a>" . $button_class_wrapper_close;
        }

        $html .= "</div></div>";
        $html .= "</li>";

        $html .= "</ul></div>";
        return $html;
    }
}
add_shortcode('cover_boxes', 'cover_boxes');

/* Dropcaps shortcode */

if (!function_exists('dropcaps')) {
    function dropcaps($atts, $content = null) {
        $args = array(
            "color"             => "",
			"font_size"  => "",
            "background_color"  => "",
            "border_color"      => "",
            "type"              => ""
        );
        extract(shortcode_atts($args, $atts));

        $html = "<span class='q_dropcap ".$type."' style='";
        if($background_color != ""){
            $html .= "background-color: $background_color;";
        }
        if($color != ""){
            $html .= " color: $color;";
        }
		if ($font_size != "") {
            $html .= 'font-size: ' . $font_size . 'px;';
        }
        if($border_color != ""){
            $html .= " border-color: $border_color;";
        }
        $html .= "'>" . $content  . "</span>";

        return $html;
    }
}
add_shortcode('dropcaps', 'dropcaps');

/* Highlights shortcode */

if (!function_exists('highlight')) {
    function highlight($atts, $content = null) {
        extract(shortcode_atts(array("color"=>"","background_color"=>""), $atts));
        $html =  "<span class='highlight'";
        if($color != "" || $background_color != ""){
            $html .= " style='color: ".$color."; background-color:".$background_color.";'";
        }
        $html .= ">" . $content . "</span>";
        return $html;
    }
}
add_shortcode('highlight', 'highlight');

//Icon shortcode
if(!function_exists('icons')) {
    function icons($atts, $content = null) {
        global $qodeIconCollections;

        $default_atts = array(
            "size"                          => "",
            "custom_size"                   => "",
            "custom_shape_size"             => "",
            "type"                          => "",
            "position"                      => "",
            "border"                        => "",
            "border_color"                  => "",
            "icon_color"                    => "",
            "icon_hover_color"              => "",
            "border_radius"                 => "",
            "background_color"              => "",
            "hover_background_color"        => "",
            "margin"                        => "",
            "icon_animation"                => "",
            "icon_animation_delay"          => "",
            "link"                          => "",
            "anchor_icon"                   => "",
            "target"                        => ""
        );

        $default_atts = array_merge($default_atts, $qodeIconCollections->getShortcodeParams());

        extract(shortcode_atts($default_atts, $atts));

        $html = "";

        //generate inline icon styles
        $style = '';
        $style_normal = '';
        $icon_stack_classes = '';
        $animation_delay_style = '';

        //generate icon stack styles
        $icon_stack_style = '';
        $icon_stack_base_style = '';
        $icon_stack_circle_styles = '';
        $icon_stack_square_styles = '';
        $icon_stack_normal_style  = '';

        if($custom_size != "") {
            $style .= 'font-size: '.$custom_size;

            if($custom_shape_size !== '') {
                $icon_stack_circle_styles .= 'font-size: '.$custom_size;
                $icon_stack_square_styles .= 'font-size: '.$custom_size;
            }


            if(!strstr($custom_size, 'px')) {
                $style .= 'px;';

                if($custom_shape_size !== '') {
                    $icon_stack_circle_styles .= 'px;';
                    $icon_stack_square_styles .= 'px;';
                }
            }
        }

        if($custom_shape_size !== '') {
            $icon_stack_circle_styles .= 'font-size: '.$custom_shape_size.'px;';
            $icon_stack_square_styles .= 'font-size: '.$custom_shape_size.'px;';
        }

        if($icon_color != "") {
            $style .= 'color: '.$icon_color.';';
        }

        if($position != "") {
            $icon_stack_classes .= 'pull-'.$position;
        }

        if($background_color != "") {
            $icon_stack_base_style .= 'color: '.$background_color.';';
            $icon_stack_style .= 'background-color: '.$background_color.';';
        }

        if($border == 'yes' && $border_color != "") {
            $icon_stack_style .= 'border: 1px solid '.$border_color.';';
        }

        if($border_radius !== '') {
            $icon_stack_square_styles .= 'border-radius: '.$border_radius.'px;';
        }

        if($icon_animation_delay != ""){
            $animation_delay_style .= 'transition-delay: '.$icon_animation_delay.'ms; -webkit-transition-delay: '.$icon_animation_delay.'ms; -moz-transition-delay: '.$icon_animation_delay.'ms; -o-transition-delay: '.$icon_animation_delay.'ms;';
        }

        if($margin != "") {
            $icon_stack_style .= 'margin: '.$margin.';';
            $icon_stack_circle_styles .= 'margin: '.$margin.';';
            $icon_stack_normal_style .= 'margin: '.$margin.';';
        }

		$icon_link_class="";
		if($anchor_icon == "yes"){
			$icon_link_class = "anchor";
		}

        //have to set default because of already created shortcodes
        $icon_pack = $icon_pack == '' ? 'font_awesome' : $icon_pack;

        switch ($type) {
            case 'circle':
                $html = '<span '.qode_get_inline_attr($type, 'data-type').' '.qode_get_inline_attr($hover_background_color, 'data-hover-bg-color').' '.qode_get_inline_attr($icon_hover_color, 'data-hover-icon-color').' class="qode_icon_shortcode fa-stack q_font_awsome_icon_stack '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_circle_styles.' '.$animation_delay_style.'">';
                if($link != ""){
                    $html .= '<a '.qode_get_inline_attr($icon_link_class, 'class').' href="'.$link.'" target="'.$target.'">';
                }
                $html .= '<i class="fa fa-circle fa-stack-base fa-stack-2x" style="'.$icon_stack_base_style.'"></i>';
//                    $html .= '<i class="qode_icon_element fa '.$icon.' fa-stack-1x" style="'.$style.'"></i>';

                $html .= $qodeIconCollections->getIconHTML(
                    ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                    $icon_pack,
                    array('icon_attributes' => array('style' => $style, 'class' => 'qode_icon_element fa-stack-1x')));

                break;
            case 'square':
                $html = '<span '.qode_get_inline_attr($type, 'data-type').' '.qode_get_inline_attr($hover_background_color, 'data-hover-bg-color').' '.qode_get_inline_attr($icon_hover_color, 'data-hover-icon-color').' class="qode_icon_shortcode fa-stack q_font_awsome_icon_square '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_style.$icon_stack_square_styles.' '.$animation_delay_style.'">';
                if($link != ""){
                    $html .= '<a '.qode_get_inline_attr($icon_link_class, 'class').'  href="'.$link.'" target="'.$target.'">';
                }

                $html .= $qodeIconCollections->getIconHTML(
                    ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                    $icon_pack,
                    array('icon_attributes' => array('style' => $style, 'class' => 'qode_icon_element')));

                break;
            default:
                $html = '<span '.qode_get_inline_attr($type, 'data-type').' '.qode_get_inline_attr($icon_hover_color, 'data-hover-icon-color').' class="qode_icon_shortcode  q_font_awsome_icon '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_normal_style.' '.$animation_delay_style.'">';
                if($link != ""){
                    $html .= '<a '.qode_get_inline_attr($icon_link_class, 'class').' href="'.$link.'" target="'.$target.'">';
                }

                $html .= $qodeIconCollections->getIconHTML(
                    ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                    $icon_pack,
                    array('icon_attributes' => array('style' => $style, 'class' => 'qode_icon_element')));

                break;
        }

        if($link != ""){
            $html .= '</a>';
        }

        $html.= '</span>';
        return $html;
    }
}
add_shortcode('icons', 'icons');

/* Icon with text shortcode */

if(!function_exists('icon_text')) {
    function icon_text($atts, $content = null) {
        global $qodeIconCollections;

        $default_atts = array(
            "icon_size"             		=> "",
            "use_custom_icon_size"  		=> "",
            "custom_icon_size"      		=> "",
            "custom_icon_size_inner"      	=> "",
            "custom_icon_margin"    		=> "",
            "icon_animation"        		=> "",
            "icon_animation_delay"  		=> "",
            "image"                 		=> "",
            "icon_type"             		=> "",
            "icon_position"         		=> "",
            "icon_border_color"     		=> "",
            "icon_margin"           		=> "",
            "icon_color"            		=> "",
            "icon_hover_color"           	=> "",
            "icon_background_color" 		=> "",
            "icon_hover_background_color" 	=> "",
            "box_type"              		=> "",
            "box_border_color"      		=> "",
            "box_background_color"  		=> "",
            "title"                 		=> "",
            "title_tag"             		=> "h5",
            "title_color"           		=> "",
            "title_font_weight"				=> "",
            "text"                  		=> "",
            "text_color"            		=> "",
            "link"                  		=> "",
            "link_text"             		=> "",
            "link_color"            		=> "",
			"link_icon"						=> "",
            "target"                		=> ""
        );

        $default_atts = array_merge($default_atts, $qodeIconCollections->getShortcodeParams());

        extract(shortcode_atts($default_atts, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        //init icon styles
        $style = '';
        $icon_stack_classes = '';


        //init icon stack styles
        $icon_margin_style       = '';
        $icon_stack_square_style = '';
        $icon_stack_base_style   = '';
        $icon_stack_style        = '';
        $img_styles              = '';
        $animation_delay_style   = '';
        $icon_text_holder_styles = '';

        //generate inline icon styles
        if($use_custom_icon_size == "yes") {
            if($custom_icon_size != "") {
                //remove px if user has entered it
                $custom_icon_size = strstr($custom_icon_size, 'px', true) ? strstr($custom_icon_size, 'px', true) : $custom_icon_size;
                $icon_stack_style .= 'font-size: '.$custom_icon_size.'px;';
            }

            if($custom_icon_margin != "") {
                //remove px if user has entered it
                $custom_icon_margin = strstr($custom_icon_margin, 'px', true) ? strstr($custom_icon_margin, 'px', true) : $custom_icon_margin;
                $custom_icon_margin = $custom_icon_size + $custom_icon_margin;

                if($icon_position !== 'right') {
                    $icon_text_holder_styles .= 'padding-left:'.$custom_icon_margin.'px;';
                } else {
                    $icon_text_holder_styles .= 'padding-right:'.$custom_icon_margin.'px;';
                }

            }

			if($custom_icon_size_inner != '' && in_array($icon_type, array('circle', 'square'))) {
				$style .= 'font-size: '.$custom_icon_size_inner.'px;';
			}

        }

        if($icon_color != "") {
            $style .= 'color: '.$icon_color.';';
        }

        //generate icon stack styles
        if($icon_background_color != "") {
            $icon_stack_base_style .= 'background-color: '.$icon_background_color.';';
            $icon_stack_square_style .= 'background-color: '.$icon_background_color.';';
        }

        if($icon_border_color != "") {
            $icon_stack_style .= 'border-color: '.$icon_border_color.';';
        }

        if($icon_margin != "" && ($icon_position == "" || $icon_position == "top")) {
            $icon_margin_style .= "margin: ".$icon_margin.";";
            $img_styles       .= "margin: ".$icon_margin.";";
        }

        if($icon_animation_delay != ""){
            $animation_delay_style .= 'transition-delay: '.$icon_animation_delay.'ms; -webkit-transition-delay: '.$icon_animation_delay.'ms; -moz-transition-delay: '.$icon_animation_delay.'ms; -o-transition-delay: '.$icon_animation_delay.'ms;';
        }

        $box_size = '';
        //generate icon text holder styles and classes

        //map value of the field to the actual class value
        switch ($icon_size) {
            case 'large': //smallest icon size
                $box_size = 'tiny';
                break;
            case 'fa-2x':
                $box_size = 'small';
                break;
            case 'fa-3x':
                $box_size = 'medium';
                break;
            case 'fa-4x':
                $box_size = 'large';
                break;
            case 'fa-5x':
                $box_size = 'very_large';
                break;
            default:
                $box_size = 'tiny';
        }

        if($image != "") {
            $icon_type = 'image';
        }

        $box_icon_type = '';
        switch ($icon_type) {
            case 'normal':
                $box_icon_type = 'normal_icon';
                break;
            case 'square':
                $box_icon_type = 'square';
                break;
            case 'circle':
                $box_icon_type = 'circle';
                break;
            case 'image':
                if($box_type == 'normal') {
                    $box_icon_type = 'custom_icon_image';
                } else {
                    $box_icon_type = 'image';
                }
                break;
        }

        /* Generate text styles */
        $title_style = "";
        if($title_color != "") {
            $title_style .= "color: ".$title_color.";";
        }
		if($title_font_weight !== "") {
			$title_style .= "font-weight: ".$title_font_weight.";";
		}

        $text_style = "";
        if($text_color != "") {
            $text_style .= "color: ".$text_color;
        }

        $link_style = "";

        if($link_color != "") {
            $link_style .= "color: ".$link_color.";";
        }

        $html = "";
        $html_icon = "";

		if($link_icon == 'yes' && $link !== '') {
			$html_icon .= '<a href="'.$link.'" target="'.$target.'" class="q_icon_link">';
		}

        //have to set default because of already created shortcodes
        $icon_pack = $icon_pack == '' ? 'font_awesome' : $icon_pack;

        if($image == "") {
            //genererate icon html
            switch ($icon_type) {
                case 'circle':
                    $html_icon .= '<span '.qode_get_inline_attr($icon_type, 'data-icon-type').' '.qode_get_inline_attr($icon_hover_color, 'data-icon-hover-color').' '.qode_get_inline_attr($icon_hover_background_color, 'data-icon-hover-bg-color').' class="qode_iwt_icon_holder fa-stack '.$icon_size.' '.$icon_stack_classes.'" style="'.$icon_stack_style . $icon_stack_base_style .'">';

                    $html_icon .= $qodeIconCollections->getIconHTML(
                        ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                        $icon_pack,
                        array('icon_attributes' => array('style' => $style, 'class' => 'qode_iwt_icon_element')));

                    $html_icon .= '</span>';
                    break;
                case 'square':
                    $html_icon .= '<span '.qode_get_inline_attr($icon_type, 'data-icon-type').'  '.qode_get_inline_attr($icon_hover_color, 'data-icon-hover-color').' '.qode_get_inline_attr($icon_hover_background_color, 'data-icon-hover-bg-color').' class="qode_iwt_icon_holder fa-stack '.$icon_size.' '.$icon_stack_classes.'" style="'.$icon_stack_style.$icon_stack_square_style.'">';

                    $html_icon .= $qodeIconCollections->getIconHTML(
                        ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                        $icon_pack,
                        array('icon_attributes' => array('style' => $style, 'class' => 'qode_iwt_icon_element')));

                    $html_icon .= '</span>';
                    break;
                default:
                    $html_icon .= '<span '.qode_get_inline_attr($icon_type, 'data-icon-type').'  '.qode_get_inline_attr($icon_hover_color, 'data-icon-hover-color').' style="'.$icon_stack_style.'" class="qode_iwt_icon_holder q_font_awsome_icon '.$icon_size.' '.$icon_stack_classes.'">';

                    $html_icon .= $qodeIconCollections->getIconHTML(
                        ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                        $icon_pack,
                        array('icon_attributes' => array('style' => $style, 'class' => 'qode_iwt_icon_element')));

                    $html_icon .= '</span>';
                    break;
            }
        } else {
            if(is_numeric($image)) {
                $image_src = wp_get_attachment_url( $image );
            }else {
                $image_src = $image;
            }
            $html_icon = '<img style="'.$img_styles.'" src="'.$image_src.'" alt="">';
        }

		if($link_icon == 'yes' && $link !== '') {
			$html_icon .= '</a>';
		}

        //generate normal type of a box html
        if($box_type == "normal") {

            //init icon text wrapper styles
            $icon_with_text_clasess = '';
            $icon_with_text_style   = '';
            $icon_text_inner_style = '';

            $icon_with_text_clasess .= $box_size;
            $icon_with_text_clasess .= ' '.$box_icon_type;

            if($box_border_color != "") {
                $icon_text_inner_style .= 'border-color: '.$box_border_color;
            }

            if($icon_position == "" || $icon_position == "top") {
                $icon_with_text_clasess .= " center";
            }
            if($icon_position == "left_from_title"){
                $icon_with_text_clasess .= " left_from_title";
            }

            if($icon_position == 'right') {
                $icon_with_text_clasess .= ' right';
            }

            $html .= "<div class='q_icon_with_title ".$icon_with_text_clasess."'>";
            if($icon_position != "left_from_title") {
                //generate icon holder html part with icon
                $html .= '<div class="icon_holder '.$icon_animation.'" style="'.$icon_margin_style.' '.$animation_delay_style.'">';
                $html .= $html_icon;
                $html .= '</div>'; //close icon_holder
            }
            //generate text html
            $html .= '<div class="icon_text_holder" style="'.$icon_text_holder_styles.'">';
            $html .= '<div class="icon_text_inner" style="'.$icon_text_inner_style.'">';
            if($icon_position == "left_from_title") {
                $html .= '<div class="icon_title_holder">'; //generate icon_title holder for icon from title
                //generate icon holder html part with icon
                $html .= '<div class="icon_holder '.$icon_animation.'" style="'.$icon_margin_style.' '.$animation_delay_style.'">';
                $html .= $html_icon;
                $html .= '</div>'; //close icon_holder
            }
            $html .= '<'.$title_tag.' class="icon_title" style="'.$title_style.'">'.$title.'</'.$title_tag.'>';
            if($icon_position == "left_from_title") {
                $html .= '</div>'; //close icon_title holder for icon from title
            }
            $html .= "<p style='".$text_style."'>".$text."</p>";
            if($link != ""){
                if($target == ""){
                    $target = "_self";
                }

                if($link_text == ""){
                    $link_text = "Read More";
                }

                $html .= "<a class='icon_with_title_link' href='".$link."' target='".$target."' style='".$link_style."'>".$link_text."</a>";
            }
            $html .= '</div>';  //close icon_text_inner
            $html .= '</div>'; //close icon_text_holder

            $html.= '</div>'; //close icon_with_title     
        } else {
            //init icon text wrapper styles
            $icon_with_text_clasess = '';
            $box_holder_styles = '';

            if($box_border_color != "") {
                $box_holder_styles .= 'border-color: '.$box_border_color.';';
            }

            if($box_background_color != "") {
                $box_holder_styles .= 'background-color: '.$box_background_color.';';
            }

            $icon_with_text_clasess .= $box_size;
            $icon_with_text_clasess .= ' '.$box_icon_type;

            $html .= '<div class="q_box_holder with_icon" style="'.$box_holder_styles.'">';

            $html .= '<div class="box_holder_icon">';
            $html .= '<div class="box_holder_icon_inner '.$icon_with_text_clasess.' '.$icon_animation.'" style="'.$animation_delay_style.'">';
            $html .= $html_icon;
            $html .= '</div>'; //close box_holder_icon_inner
            $html .= '</div>'; //close box_holder_icon

            //generate text html
            $html .= '<div class="box_holder_inner '.$box_size.' center">';
            $html .= '<'.$title_tag.' class="icon_title" style="'.$title_style.'">'.$title.'</'.$title_tag.'>';
            $html .= '<span class="separator transparent" style="margin: 8px 0;"></span>';
            $html .= '<p style="'.$text_style.'">'.$text.'</p>';
            $html .= '</div>'; //close box_holder_inner

            $html .= '</div>'; //close box_holder
        }

        return $html;

    }
}
add_shortcode('icon_text', 'icon_text');

/* Image hover shortcode */

if (!function_exists('image_hover')) {

    function image_hover($atts, $content = null) {
        $args = array(
            "image"             => "",
            "hover_image"       => "",
            "link"              => "",
            "target"            => "_self",
            "animation"         => "",
            "transition_delay"  => ""
        );

        extract(shortcode_atts($args, $atts));

        //init variables
        $html               = "";
        $image_classes      = "";
        $image_src          = $image;
        $hover_image_src    = $hover_image;
        $images_styles      = "";

        if (is_numeric($image)) {
            $image_src = wp_get_attachment_url($image);
        }

        if (is_numeric($hover_image)) {
            $hover_image_src = wp_get_attachment_url($hover_image);
        }

        if($hover_image_src != "") {
            $image_classes .= "active_image ";
        }

        $css_transition_delay = ($transition_delay != "" && $transition_delay > 0) ? $transition_delay / 1000 . "s" : "";

        $animate_class = ($animation == "yes") ? "hovered" : "";

        //generate output
        $html .= "<div class='image_hover {$animate_class}' style='' data-transition-delay='{$transition_delay}'>";
        $html .= "<div class='images_holder'>";

        if($link != "") {
            $html .= "<a href='{$link}' target='{$target}'>";
        }

        $html .= "<img class='{$image_classes}' src='{$image_src}' alt='' style='{$images_styles}' />";
        $html .= "<img class='hover_image' src='{$hover_image_src}' alt='' style='{$images_styles}' />";

        if($link != "") {
            $html .= "</a>";
        }

        $html .= "</div>"; //close image_hover
        $html .= "</div>"; //close images_holder

        return $html;
    }

    add_shortcode('image_hover', 'image_hover');
}

/* Icon List Item shortcode */

if (!function_exists('icon_list_item')) {
    function icon_list_item($atts, $content = null) {
        $args = array(
            "icon"                                  => "",
            "icon_type"                             => "",
            "icon_color"                            => "",
            "icon_background_color"                 => "",
            "icon_border_color"                     => "",
            "title"                                 => "",
            "title_color"                           => "",
            "title_size"                            => ""
        );

        extract(shortcode_atts($args, $atts));
        $html           = '';
        $icon_style     = "";
        $icon_classes   = "";
        $title_style    = "";

        $icon_classes .= $icon_type." ";

        if($icon_color != "") {
            $icon_style .= "color:".$icon_color.";";
        }

        if($icon_background_color != "") {
            $icon_style .= "background-color: {$icon_background_color};";
        }

        if($icon_border_color != "") {
            $icon_style .= "border-color:".$icon_border_color.";";
        }

        if($title_color != "") {
            $title_style .= "color:".$title_color.";";
        }

        if($title_size != "") {
            $title_style .= "font-size: ".$title_size."px;";
        }

        $html .= '<div class="q_icon_list">';
        $html .= '<i class="fa '.$icon.' pull-left '.$icon_classes.'" style="'.$icon_style.'"></i>';
        $html .= '<p style="'.$title_style.'">'.$title.'</p>';
        $html .= '</div>';
        return $html;
    }
}
add_shortcode('icon_list_item', 'icon_list_item');

/* Image with text shortcode */

if (!function_exists('image_with_text')) {

    function image_with_text($atts, $content = null) {
        $args = array(
            "image" => "",
            "title" => "",
            "title_color" => "",
            "title_tag" => "h3"
        );
        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = '';
        $html .= '<div class="image_with_text">';
        if (is_numeric($image)) {
            $image_src = wp_get_attachment_url($image);
        } else {
            $image_src = $image;
        }
        $html .= '<img src="' . $image_src . '" alt="' . $title . '" />';
        $html .= '<'.$title_tag.' ';
        if ($title_color != "") {
            $html .= 'style="color:' . $title_color . ';"';
        }
        $html .= '>' . $title . '</'.$title_tag.'>';
        $html .= '<span style="margin: 6px 0px;" class="separator transparent"></span>';
        $html .= do_shortcode($content);
        $html .= '</div>';

        return $html;
    }

    add_shortcode('image_with_text', 'image_with_text');
}

/* Image with text over shortcode */

if (!function_exists('image_with_text_over')) {

    function image_with_text_over($atts, $content = null) {
        $args = array(
            "layout_width"			=> "",
            "image"					=> "",
            "image_shader_color"	=> "",
            "icon"					=> "",
            "icon_size"				=> "",
            "icon_color"			=> "",
            "title"					=> "",
            "title_color"			=> "",
            "title_size"			=> "",
            "title_tag"				=> "h3"
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        //init variables
        $html            = "";
        $title_styles    = "";
        $subtitle_styles = "";
        $line_styles     = "";
        $no_icon         = "";
        $icon_styles     = "";
        $shader_style    = "";

        //generate styles
        if($title_color != "") {
            $title_styles .= "color: ".$title_color.";";
        }

        if($title_size != "") {
            $valid_title_size = (strstr($title_size, 'px', true)) ? $title_size : $title_size.'px';
            $title_styles .= "font-size: ".$valid_title_size.";";
        }

        if($icon_color != "") {
            $bcolor = qode_hex2rgb($icon_color);
            $icon_styles .= "style='color: ".$icon_color."; border-color: rgba(".$bcolor[0].",".$bcolor[1].",".$bcolor[2].",0.6);'";
        }

        if (is_numeric($image)) {
            $image_src = wp_get_attachment_url($image);
        } else {
            $image_src = $image;
        }

		if ($image_shader_color !== '') {
			$shader_style = 'style = "background-color: ' . $image_shader_color . '"';
		}

        if($icon == ""){
            $no_icon = "no_icon";
        }

        //generate output
        $html .= '<div class="q_image_with_text_over '.$layout_width.'">';
        $html .= '<div class="shader" ' . $shader_style . '></div>';

        $html .= '<img src="' . $image_src . '" alt="' . $title . '" />';
        $html .= '<div class="text">';

        //title and subtitle table html
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>';
        if($icon != ""){
            $html .= '<i class="icon_holder fa '.$icon.' '.$icon_size.' " '.$icon_styles .'></i>';
        }
        $html .= '<'.$title_tag.' class="caption '.$no_icon.'" style="'.$title_styles.'">'.$title.'</'.$title_tag.'>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        //image description table html which appears on mouse hover
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<div class="desc">' . do_shortcode($content) . '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '</div>'; //close text div
        $html .= '</div>'; //close image_with_text_over

        return $html;
    }

    add_shortcode('image_with_text_over', 'image_with_text_over');
}

/* Latest post shortcode */

if (!function_exists('latest_post')) {
    function latest_post($atts, $content = null) {
        $blog_hide_comments = "";
        if (isset($qode_options_proya['blog_hide_comments'])) {
            $blog_hide_comments = $qode_options_proya['blog_hide_comments'];
        }

        $qode_like = "on";
        if (isset($qode_options_proya['qode_like'])) {
            $qode_like = $qode_options_proya['qode_like'];
        }

        $args = array(
            "type"       			=> "date_in_box",
            "number_of_posts"       => "",
            "number_of_colums"      => "",
            "number_of_rows"        => "1",
            "text_from_edge"      	=> "",
            "rows"                  => "",
            "order_by"              => "",
            "order"                 => "",
            "category"              => "",
            "text_length"           => "",
            "title_tag"             => "h5",
            "display_category"    	=> "0",
            "display_time"          => "1",
            "display_comments"      => "1",
            "display_like"          => "0",
            "display_share"         => "0",
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        if($type != "boxes" && $type != "dividers"){
            $q = new WP_Query(
                array('orderby' => $order_by, 'order' => $order, 'posts_per_page' => $number_of_posts, 'category_name' => $category)
            );
        } else {
            $q = new WP_Query(
                array('orderby' => $order_by, 'order' => $order, 'posts_per_page' => $number_of_colums*$number_of_rows, 'category_name' => $category)
            );
        }

        $columns_number = "";
		if($type == 'boxes' || $type == 'dividers') {
			if($number_of_colums == 2){
				$columns_number = "two_columns";
			} else if ($number_of_colums == 3) {
				$columns_number = "three_columns";
			} else if ($number_of_colums == 4) {
				$columns_number = "four_columns";
			}
		}

        //get number of rows class for boxes type
        $rows_number = "";
        if($type == 'boxes' || $type == 'dividers') {
            switch($number_of_rows) {
                case 1:
                    $rows_number = 'one_row';
                    break;
                case 2:
                    $rows_number = 'two_rows';
                    break;
                case 3:
                    $rows_number = 'three_rows';
                    break;
                case 4:
                    $rows_number = 'four_rows';
                    break;
                case 5:
                    $rows_number = 'five_rows';
                    break;  
                default:
                    break;
            }
        }

        $html = "";
        $html .= "<div class='latest_post_holder $type $columns_number $rows_number'>";
        $html .= "<ul>";

        while ($q->have_posts()) : $q->the_post();
            $li_classes = "";

            $cat = get_the_category();

            $html .= '<li class="clearfix">';
            if($type == "date_in_box") {
                $html .= '<div class="latest_post_date">';
                $html .= '<div class="post_publish_day">'.get_the_time('d').'</div>';
                $html .= '<div class="post_publish_month">'.get_the_time('M').'</div>';
                $html .= '</div>';
            }

            if($type == "boxes" || $type == 'dividers'){
                $html .= '<div class="boxes_image">';
                $html .= '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'latest_post_boxes').'</a>';
                $html .= '</div>';
            }
			$padding_latest_post = "";
			if($text_from_edge == "yes" && $type == "boxes"){
				$padding_latest_post = " style='padding-left:0;padding-right:0;'";
			}
            $html .= '<div class="latest_post"'. $padding_latest_post .'>';
            if($type == "image_in_box") {
                $html .= '<div class="latest_post_image clearfix">';
                $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                $html .= '<img src="'. $featured_image_array[0] .'" alt="" />';
                $html .= '</div>';
            }
            $html .= '<div class="latest_post_text">';
            $html .= '<div class="latest_post_inner">';
            if ($type == "dividers") {
                $html .= '<div class="latest_post_date">';
                $html .= '<div class="latest_post_day">'.get_the_time('d').'</div>';
                $html .= '<div class="latest_post_month">'.get_the_time('M').'</div>';
                $html .= '</div>';
            }
            $html .= '<div class="latest_post_text_inner">';
            if($type != "minimal") {
                $html .= '<'.$title_tag.' class="latest_post_title "><a href="' . get_permalink() . '">' . get_the_title() . '</a></'.$title_tag.'>';
            }
            if($type != "minimal") {
                if($text_length != '0') {
                    $excerpt = ($text_length > 0) ? substr(get_the_excerpt(), 0, intval($text_length)) : get_the_excerpt();
                    $html .= '<p class="excerpt">'.$excerpt.'...</p>';
                }

            }
            $html .= '<span class="post_infos">';
            if($display_time == '1'  && $type !== 'dividers'){
                $html .= '<span class="date_hour_holder">';
                if($type != 'date_in_box'){
                    $html .= '<span class="date">' . get_the_time('d F, Y') . '</span>';
                } else {
                    $html .= '<span class="date">' . get_the_time('g:h') . 'h</span>';
                }

                $html .= '</span>';//close date_hour_holder
            }
            if($display_category == '1'){
            	if ($type == "dividers"){
	                foreach ($cat as $categ) {
	                    $html .='<a href="' . get_category_link($categ->term_id) . '">' . $categ->cat_name . '</a>';
	                }
            	}
            	else{
	                $html .= '<span class="dots"><i class="fa fa-square"></i></span>';
	                foreach ($cat as $categ) {
	                    $html .=' <a href="' . get_category_link($categ->term_id) . '">' . $categ->cat_name . ' </a> ';
	                }
	            }
            }
            //generate comments part of description
            if ($blog_hide_comments != "yes" && $display_comments == "1") {
                $comments_count = get_comments_number();

                switch ($comments_count) {
                    case 0:
                        $comments_count_text = __('No comment', 'qode');
                        break;
                    case 1:
                        $comments_count_text = $comments_count . ' ' . __('Comment', 'qode');
                        break;
                    default:
                        $comments_count_text = $comments_count . ' ' . __('Comments', 'qode');
                        break;
                }
            	if ($type != "dividers"){
	                $html .= '<span class="dots"><i class="fa fa-square"></i></span>';
		        }
                $html .= '<a class="post_comments" href="' . get_comments_link() . '">';
                $html .= $comments_count_text;
                $html .= '</a>';//close post_comments
            }

            if($qode_like == "on" && function_exists('qode_like')) {
                if($display_like == '1'){
                	if ($type != "dividers"){
		                $html .= '<span class="dots"><i class="fa fa-square"></i></span>';
			        }
                    $html .= '<span class="blog_like">'.qode_like_latest_posts().'</span>';
                }
            }
            if($display_share == '1'){
            	if ($type != "dividers"){
	                $html .= '<span class="dots"><i class="fa fa-square"></i></span>';
		        }
                $html .= do_shortcode('[social_share]');
            }
            $html .= '</span>'; //close post_infos span
            if($type == "minimal") {
                $html .= '<'.$title_tag.' class="latest_post_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></'.$title_tag.'>';
            }
            $html .= '</div>'; //close latest_post_text_inner span
            $html .= '</div>'; //close latest_post_inner div
            $html .= '</div>'; //close latest_post_text div
            $html .= '</div>'; //close latest_post div

        endwhile;
        wp_reset_postdata();

        $html .= "</ul></div>";
        return $html;
    }

}
add_shortcode('latest_post', 'latest_post');

/* Masonry blog list shortcode */

if (!function_exists('masonry_blog')) {
    function masonry_blog($atts, $content = null) {
        $blog_hide_comments = "";
        if (isset($qode_options_proya['blog_hide_comments'])) {
            $blog_hide_comments = $qode_options_proya['blog_hide_comments'];
        }

        $qode_like = "on";
        if (isset($qode_options_proya['qode_like'])) {
            $qode_like = $qode_options_proya['qode_like'];
        }

        $args = array(
            "number_of_posts"       => "",
            "order_by"              => "",
            "order"                 => "",
            "category"              => "",
            "text_length"           => "",
            "title_tag"             => "h5",
            "display_time"          => "1",
            "display_comments"      => "1",

        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $q = new WP_Query(
            array('orderby' => $order_by, 'order' => $order, 'posts_per_page' => $number_of_posts, 'category_name' => $category)
        );



        $html = "";
        $html .= "<div class='q_masonry_blog'>";
        while ($q->have_posts()) : $q->the_post();
            $_post_format = get_post_format();
            $_post_classes =  get_post_class();
            $article_class = " class='";
            foreach($_post_classes as $_post_class){
                $article_class .= $_post_class . " ";
            }
            $article_class .= "'";
            $html .= "<article " .  $article_class .  ">";
            $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if($_post_format != 'quote' && $_post_format != 'link'){
                $html .= '<div class="q_masonry_blog_post_image">';
                switch ($_post_format) {
                    case "video":
                        $_video_type = get_post_meta(get_the_ID(), "video_format_choose", true);
                        if($_video_type == "youtube") {
                            $html .= '<iframe src="//www.youtube.com/embed/' . get_post_meta(get_the_ID(), "video_format_link", true) . '?wmode=transparent" wmode="Opaque" frameborder="0" allowfullscreen></iframe>';
                        } elseif ($_video_type == "vimeo"){
                            $html .= '<iframe src="//player.vimeo.com/video/' . get_post_meta(get_the_ID(), "video_format_link", true) . '?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                        } elseif ($_video_type == "self"){
                            $html .= '<div class="video"> ';
                            $html .= '<div class="mobile-video-image" style="background-image: url(' . get_post_meta(get_the_ID(), "video_format_image", true) . ');"></div> ';
                            $html .= '<div class="video-wrap">';
                            $html .= '<video class="video" poster="' . get_post_meta(get_the_ID(), "video_format_image", true) . '" preload="auto">';
                            if(get_post_meta(get_the_ID(), "video_format_webm", true) != "") {
                                $html .= '<source type="video/webm" src="' . get_post_meta(get_the_ID(), "video_format_webm", true). '">';
                            }
                            if(get_post_meta(get_the_ID(), "video_format_mp4", true) != "") {
                                $html .= '<source type="video/mp4" src="' . get_post_meta(get_the_ID(), "video_format_mp4", true) . '">';
                            }
                            if(get_post_meta(get_the_ID(), "video_format_ogv", true) != "") {
                                $html .= '<source type="video/ogg" src="'. get_post_meta(get_the_ID(), "video_format_ogv", true).'">';
                            }
                            $html .= '<object width="320" height="240" type="application/x-shockwave-flash" data="' . get_template_directory_uri() . '/js/flashmediaelement.swf">';
                            $html .= '<param name="movie" value="' . get_template_directory_uri() . '/js/flashmediaelement.swf" />';
                            $html .= '<param name="flashvars" value="controls=true&file=' . get_post_meta(get_the_ID(), "video_format_mp4", true) . '" />';
                            $html .= '<img src="' . get_post_meta(get_the_ID(), "video_format_image", true)  . '" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" /> ';
                            $html .= '</object>';
                            $html .= '</video>';
                            $html .= '</div></div>';
                        }
                        break;
                    case "audio":
                        $html .= '<audio class="blog_audio" src="' . get_post_meta(get_the_ID(), "audio_link", true). '" controls="controls">';
                        $html .=  __("Your browser don't support audio player","qode");
                        $html .= '</audio>';
                        break;
                    case "gallery":
                        $html .= '<div class="flexslider">';
                        $html .= '<ul class="slides">';
                        $post_content = get_the_content();
                        preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
                        $array_id = explode(",", $ids[1]);
                        foreach($array_id as $img_id){
                            $html .= '<li><a target="_self" href="' . get_permalink() . '">' . wp_get_attachment_image( $img_id, 'full' ) . '</a></li>';
                        }
                        $html .= '</ul>';
                        $html .= '</div>';
                        break;
                    default:
                        if(!empty($featured_image_array)){
                        $html .= '<a href="' . get_permalink()  . '" target="_self">';
                        $html .= '<img src="' . $featured_image_array[0] . '" />';
                        $html .= '</a>';
                        }
                        break;
                }
                $html .= '</div>';

                $html .= '<div class="q_masonry_blog_post_text">';
                $html .= '<'.$title_tag.' class="q_masonry_blog_title "><a href="' . get_permalink() . '">' . get_the_title() . '</a></'.$title_tag.'>';
                $excerpt = ($text_length > 0) ? substr(get_the_excerpt(), 0, intval($text_length)) : get_the_excerpt();
                $html .= '<p class="q_masonry_blog_excerpt">'.$excerpt.'...</p>';
                $html .= '<div class="q_masonry_blog_post_info">';
                if ($display_time != "0") {
                    $html .= '<span class="time">'. get_the_time('d F, Y') .'</span>';
                }
                if ($blog_hide_comments != "yes" && $display_comments == "1") {
                    $comments_count = get_comments_number();

                    switch ($comments_count) {
                        case 0:
                            $comments_count_text = __('No comment', 'qode');
                            break;
                        case 1:
                            $comments_count_text = $comments_count . ' ' . __('Comment', 'qode');
                            break;
                        default:
                            $comments_count_text = $comments_count . ' ' . __('Comments', 'qode');
                            break;
                    }
                    $html .= ' / <a class="post_comments" href="' . get_comments_link() . '">';
                    $html .= $comments_count_text;
                    $html .= '</a>';//close post_comments
                }
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $html .= '<div class="q_masonry_blog_post_text">';
                $html .= '<div class="q_masonry_blog_post_info">';
                if ($display_time != "0") {
                    $html .= '<span class="time">'. get_the_time('d F, Y') .'</span>';
                }
                if ($blog_hide_comments != "yes" && $display_comments == "1") {
                    $comments_count = get_comments_number();

                    switch ($comments_count) {
                        case 0:
                            $comments_count_text = __('No comment', 'qode');
                            break;
                        case 1:
                            $comments_count_text = $comments_count . ' ' . __('Comment', 'qode');
                            break;
                        default:
                            $comments_count_text = $comments_count . ' ' . __('Comments', 'qode');
                            break;
                    }
                    $html .= ' / <a class="post_comments" href="' . get_comments_link() . '">';
                    $html .= $comments_count_text;
                    $html .= '</a>';//close post_comments
                }
                $html .= '</div>';
                if($_post_format == "quote") {
                    $html .= '<i class="qoute_mark fa fa-quote-right pull-left"></i>';
                }else{
                    $html .= '<i class="link_mark fa fa-link pull-left"></i>';
                }
                $html .= '<div class="q_masonry_blog_post_title">';
                if($_post_format == "quote") {
                    $html .= '<p><a href="' . get_permalink(). '">' . get_post_meta(get_the_ID(), "quote_format", true) . '</a></p>';
                    $html .= '<span class="quote_author">&mdash;' . get_the_title() . '</span>';
                } else {
                    $html .= '<p><a href="' . get_permalink(). '">' . get_the_title()  . '</a></p>';
                }
                $html .= '</div></div>';

            }
            $html .= '</article>';
        endwhile;
        wp_reset_postdata();

        $html .= "</div>";
        return $html;
    }

}
add_shortcode('masonry_blog', 'masonry_blog');

/* Line graph shortcode */

if (!function_exists('line_graph')) {
    function line_graph($atts, $content = null) {
        global $qode_options_proya;
        extract(shortcode_atts(array("type" => "rounded", "custom_color" => "", "labels" => "", "width" => "750", "height" => "350", "scale_steps" => "6", "scale_step_width" => "20"), $atts));
        $id = mt_rand(1000, 9999);
        if($type == "rounded"){
            $bezierCurve = "true";
        }else{
            $bezierCurve = "false";
        }

        $id = mt_rand(1000, 9999);
        $html = "<div class='q_line_graf_holder'><div class='q_line_graf'><canvas id='lineGraph".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_line_graf_legend'><ul>";
        $line_graph_array = explode(";", $content);
        for ($i = 0 ; $i < count($line_graph_array) ; $i = $i + 1){
            $line_graph_el = explode(",", $line_graph_array[$i]);
            $html .=  "<li><div class='color_holder' style='background-color: ".trim($line_graph_el[0]).";'></div><p style='color: ".$custom_color.";'>".trim($line_graph_el[1])."</p></li>";
        }
        $html .=  "</ul></div></div><script>var lineGraph".$id." = {labels : [";
        $line_graph_labels_array = explode(",", $labels);
        for ($i = 0 ; $i < count($line_graph_labels_array) ; $i = $i + 1){
            if ($i > 0) $html .= ",";
            $html .=  '"'.$line_graph_labels_array[$i].'"';
        }
        $html .= "],";
        $html .= "datasets : [";
        $line_graph_array = explode(";", $content);
        for ($i = 0 ; $i < count($line_graph_array) ; $i = $i + 1){
            $line_graph_el = explode(",", $line_graph_array[$i]);
            if ($i > 0) $html .= ",";
            $values = "";
            for ($j = 2 ; $j < count($line_graph_el) ; $j = $j + 1){
                if ($j > 2) $values .= ",";
                $values .= $line_graph_el[$j];
            }
            $color = qode_hex2rgb(trim($line_graph_el[0]));
            $html .=  "{fillColor: 'rgba(".$color[0].",".$color[1].",".$color[2].",0.7)',data:[".$values."]}";
        }
        if(!empty($qode_options_proya['text_fontsize'])){
            $text_fontsize = $qode_options_proya['text_fontsize'];
        }else{
            $text_fontsize = 15;
        }
        if(!empty($qode_options_proya['text_color']) && $custom_color == ""){
            $text_color = $qode_options_proya['text_color'];
        } else if(empty($qode_options_proya['text_color']) && $custom_color != ""){
            $text_color = $custom_color;
        } else if(!empty($qode_options_proya['text_color']) && $custom_color != ""){
            $text_color = $custom_color;
        }else{
            $text_color = '#818181';
        }
        $html .= "]};
			var \$j = jQuery.noConflict();
			\$j(document).ready(function() {
				if(\$j('.touch .no_delay').length){
					new Chart(document.getElementById('lineGraph".$id."').getContext('2d')).Line(lineGraph".$id.",{scaleOverride : true,
					scaleStepWidth : ".$scale_step_width.",
					scaleSteps : ".$scale_steps.",
					bezierCurve : ".$bezierCurve.",
					pointDot : false,
					scaleLineColor: '#505050',
					scaleFontColor : '".$text_color."',
					scaleFontSize : ".$text_fontsize.",
					scaleGridLineColor : '#e1e1e1',
					datasetStroke : false,
					datasetStrokeWidth : 0,
					animationSteps : 120,});
				}else{
					\$j('#lineGraph".$id."').appear(function() {
						new Chart(document.getElementById('lineGraph".$id."').getContext('2d')).Line(lineGraph".$id.",{scaleOverride : true,
						scaleStepWidth : ".$scale_step_width.",
						scaleSteps : ".$scale_steps.",
						bezierCurve : ".$bezierCurve.",
						pointDot : false,
						scaleLineColor: '#000000',
						scaleFontColor : '".$text_color."',
						scaleFontSize : ".$text_fontsize.",
						scaleGridLineColor : '#e1e1e1',
						datasetStroke : false,
						datasetStrokeWidth : 0,
						animationSteps : 120,});
					},{accX: 0, accY: -200});
				}						
			});
		</script>";
        return $html;
    }
}
add_shortcode('line_graph', 'line_graph');

/* Message shortcode */

if (!function_exists('message')) {
    function message($atts, $content = null) {
        global $qode_options_proya;

        $args = array(
            "type"                  => "",
            "background_color"      => "",
            "border"     			=> "",
            "border_width"     		=> "",
            "border_color"      	=> "",
            "icon"                  => "",
            "icon_size"            	=> "fa-2x",
            "icon_color"            => "",
            "icon_background_color" => "",
            "custom_icon"           => "",
            "close_button_color"    => ""
        );
        extract(shortcode_atts($args, $atts));

        //init variables
        $html                   = "";
        $icon_html              = "";
        $message_classes        = "";
        $message_styles         = "";
        $icon_styles            = "";
        $close_button_styles    = "";

        if($type == "with_icon"){
            $message_classes .= " with_icon";
        }

        if($background_color != "") {
            $message_styles .= "background-color: ".$background_color.";";
        }
		if($border == "yes"){

			$message_styles .= "border-style:solid;";
			if($border_width!= ""){
				$message_styles .= "border-width: ".$border_width."px;";
			}
			if($border_color != ""){
				$message_styles .= "border-color: ".$border_color.";";
			}

		}
        if($icon_color != "") {
            $icon_styles .= "color: ".$icon_color;
        }

        if($icon_background_color != "") {
            $icon_styles .= " background-color: ".$icon_background_color;
        }

        if($close_button_color != "") {
            $close_button_styles .= "color: ".$close_button_color.";";
        }

        $html .= "<div class='q_message ".$message_classes."' style='".$message_styles."'>";
        $html .= "<div class='q_message_inner'>";
        if($type == "with_icon"){
            $icon_html .= '<div class="q_message_icon_holder"><div class="q_message_icon"><div class="q_message_icon_inner">';
            if($custom_icon != "") {
                if(is_numeric($custom_icon)) {
                    $custom_icon_src = wp_get_attachment_url( $custom_icon );
                } else {
                    $custom_icon_src = $custom_icon;
                }

                $icon_html .= '<img src="' . $custom_icon_src . '" alt="">';
            } else {
                $icon_html .= "<i class='fa ".$icon." ". $icon_size . "' style='".$icon_styles."'></i>";
            }
            $icon_html .= '</div></div></div>';
        }

        $html .= $icon_html;

        $html .= "<a href='#' class='close'>";
        $html .= "<i class='fa fa-times' style='".$close_button_styles."'></i>";
        $html .= "</a>"; //close a.close
        $html .= "<div class='message_text_holder'><div class='message_text'><div class='message_text_inner'>".do_shortcode($content)."</div></div></div>";

        $html .= "</div></div>"; //close message text div
        return $html;
    }
}
add_shortcode('message', 'message');

/* Ordered List shortcode */

if (!function_exists('ordered_list')) {
    function ordered_list($atts, $content = null) {
        $html =  "<div class=ordered>" . $content . "</div>";
        return $html;
    }
}
add_shortcode('ordered_list', 'ordered_list');

/* Pie Chart shortcode */

if (!function_exists('pie_chart')) {

    function pie_chart($atts, $content = null) {
        $args = array(
            "title"                 => "",
            "title_color"           => "",
            "title_tag"             => "h4",
            "percent"               => "",
            "percentage_color"      => "",
            "percent_font_size"     => "",
            "percent_font_weight"   => "",
            "active_color"          => "",
            "noactive_color"        => "",
            "line_width"            => "",
            "text"                  => "",
            "text_color"            => "",
            "separator"           	=> "yes",
            "separator_color"       => ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = '';
        $html .= '<div class="q_pie_chart_holder"><div class="q_percentage" data-percent="' . $percent . '" data-linewidth="' . $line_width . '" data-active="' . $active_color . '" data-noactive="' . $noactive_color . '"';
        if ($percentage_color != "" || $percent_font_size != "" || $percent_font_weight != "") {
            $html .= ' style="';

			if($percentage_color != ""){
				$html .= 'color:'.$percentage_color.';';
			}
			if($percent_font_size != ""){
				$html .= 'font-size:'.$percent_font_size.'px;';
			}
			if($percent_font_weight != ""){
				$html .= 'font-weight:'.$percent_font_weight.';';
			}
			$html .= '"';
        }
        $html .= '><span class="tocounter">' . $percent . '</span>%';
        $html .= '</div><div class="pie_chart_text">';
        if ($title != "") {
            $html .= '<'.$title_tag.' class="pie_title"';
            if ($title_color != "") {
                $html .= ' style="color: ' . $title_color . ';"';
            }
            $html .= '>' . $title . '</'.$title_tag.'>';
        }
        $separator_styles = "";
        if($separator_color != "") {
            $separator_styles .= " style='background-color: ".$separator_color.";'";
        }

        if($separator == "yes") {
            $html .= '<span class="separator small"'.$separator_styles.'"></span>';
        }

        if ($text != "") {
            $html .= '<p';
            if($text_color != ""){
                $html .= ' style="color: '.$text_color.';"';
            }
            $html .= '>' . $text . '</p>';
        }
        $html .= "</div></div>";
        return $html;
    }

}
add_shortcode('pie_chart', 'pie_chart');

/* Pie Chart With Icon shortcode */

if (!function_exists('pie_chart_with_icon')) {

    function pie_chart_with_icon($atts, $content = null) {
        $args = array(
            "percent" => "",
            "active_color" => "",
            "noactive_color" => "",
            "line_width" => "",
            "icon" => "",
            "icon_size" => "",
            "icon_color" => "",
            "title" => "",
            "title_color" => "",
            "title_tag" => "h3",
            "text" => "",
            "text_color" => ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = '';
        $html .= '<div class="q_pie_chart_with_icon_holder"><div class="q_percentage_with_icon" data-percent="' . $percent . '" data-linewidth="' . $line_width . '" data-active="' . $active_color . '" data-noactive="' . $noactive_color . '">';
        $html .= '<i class="fa '.$icon.' '.$icon_size.'"';
        if ($icon_color != "") {
            $html .= ' style="color: ' . $icon_color . ';"';
        }
        $html .= '></i>';
        $html .= '</div><div class="pie_chart_text">';
        if ($title != "") {
            $html .= '<'.$title_tag.' class="pie_title"';
            if ($title_color != "") {
                $html .= ' style="color: ' . $title_color . ';"';
            }
            $html .= '>' . $title . '</'.$title_tag.'>';
        }
        if ($text != "") {
            $html .= '<p ';
            if ($text_color != "") {
                $html .= ' style="color: ' . $text_color . ';"';
            }
            $html .= '>' . $text . '</p>';
        }
        $html .= "</div></div>";
        return $html;
    }
}
add_shortcode('pie_chart_with_icon', 'pie_chart_with_icon');

/* Pie Chart Full shortcode */

if (!function_exists('pie_chart2')) {
    function pie_chart2($atts, $content = null) {
        extract(shortcode_atts(array("width" => "120", "height" => "120", "color" => ""), $atts));
        $id = mt_rand(1000, 9999);
        $html = "<div class='q_pie_graf_holder'><div class='q_pie_graf'><canvas id='pie".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_pie_graf_legend'><ul>";
        $pie_chart_array = explode(";", $content);
        for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
            $pie_chart_el = explode(",", $pie_chart_array[$i]);
            $html .= "<li><div class='color_holder' style='background-color: ".trim($pie_chart_el[1]).";'></div><p style='color: ".$color.";'>".trim($pie_chart_el[2])."</p></li>";
        }
        $html .= "</ul></div></div><script>var pie".$id." = [";
        $pie_chart_array = explode(";", $content);
        for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
            $pie_chart_el = explode(",", $pie_chart_array[$i]);
            if ($i > 0) $html .= ",";
            $html .= "{value: ".trim($pie_chart_el[0]).",color:'".trim($pie_chart_el[1])."'}";
        }
        $html .= "];
		var \$j = jQuery.noConflict();
		\$j(document).ready(function() {
			if(\$j('.touch .no_delay').length){
				new Chart(document.getElementById('pie".$id."').getContext('2d')).Pie(pie".$id.",{segmentStrokeColor : 'transparent',});
			}else{
				\$j('#pie".$id."').appear(function() {
					new Chart(document.getElementById('pie".$id."').getContext('2d')).Pie(pie".$id.",{segmentStrokeColor : 'transparent',});
				},{accX: 0, accY: -200});
			}
		});
	</script>";
        return $html;
    }
}
add_shortcode('pie_chart2', 'pie_chart2');


/* Pie Chart Doughnut shortcode */

if (!function_exists('pie_chart3')) {
    function pie_chart3($atts, $content = null) {
        extract(shortcode_atts(array("width" => "120", "height" => "120", "color" => ""), $atts));
        $id = mt_rand(1000, 9999);
        $html = "<div class='q_pie_graf_holder'><div class='q_pie_graf'><canvas id='pie".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_pie_graf_legend'><ul>";
        $pie_chart_array = explode(";", $content);
        for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
            $pie_chart_el = explode(",", $pie_chart_array[$i]);
            $html .= "<li><div class='color_holder' style='background-color: ".trim($pie_chart_el[1]).";'></div><p style='color: ".$color.";'>".trim($pie_chart_el[2])."</p></li>";
        }
        $html .= "</ul></div></div><script>var pie".$id." = [";
        $pie_chart_array = explode(";", $content);
        for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
            $pie_chart_el = explode(",", $pie_chart_array[$i]);
            if ($i > 0) $html .= ",";
            $html .= "{value: ".trim($pie_chart_el[0]).",color:'".trim($pie_chart_el[1])."'}";
        }
        $html .= "];
		var \$j = jQuery.noConflict();
		\$j(document).ready(function() {
			if(\$j('.touch .no_delay').length){
				new Chart(document.getElementById('pie".$id."').getContext('2d')).Doughnut(pie".$id.",{segmentStrokeColor : 'transparent',});
			}else{
				\$j('#pie".$id."').appear(function() {
					new Chart(document.getElementById('pie".$id."').getContext('2d')).Doughnut(pie".$id.",{segmentStrokeColor : 'transparent',});
				},{accX: 0, accY: -200});
			}							
		});
	</script>";
        return $html;
    }
}
add_shortcode('pie_chart3', 'pie_chart3');

/* Portfolio list shortcode */

if (!function_exists('portfolio_list')) {

    function portfolio_list($atts, $content = null) {

        global $wp_query;
        global $portfolio_project_id;
        global $qode_options_proya;
        $portfolio_qode_like = "on";
        if (isset($qode_options_proya['portfolio_qode_like'])) {
            $portfolio_qode_like = $qode_options_proya['portfolio_qode_like'];
        }

        $args = array(
            "type"                  		    => "standard",
            "spacing"						    => "",
            "hover_type_standard"               => "default",
            "hover_type_text_on_hover_image"    => "default",
            "hover_type_text_before_hover"      => "default",
            "hover_type_masonry"                => "default",
            "box_border"            		    => "",
            "box_background_color"  		    => "",
            "box_border_color"      		    => "",
            "box_border_width"      		    => "",
            "columns"               		    => "3",
            "portfolio_loading_type" 		    => "",
            "portfolio_loading_type_masonry"    => "",
            "grid_size"               		    => "",
            "image_size"            		    => "",
            "overlay_background_color"          => "",
            "order_by"              		    => "date",
            "order"                 		    => "ASC",
            "number"                		    => "-1",
            "filter"                		    => "no",
            "filter_color"          		    => "",
            "lightbox"              		    => "yes",
            "view_button"           		    => "yes",
            "category"              		    => "",
            "selected_projects"     		    => "",
            "show_load_more"        		    => "yes",
            "show_title"             		    => "",
            "title_tag"             		    => "h5",
            "title_color"                       => "",
            "title_font_size"                   => "",
            "show_categories"                   => "",
            "category_color"                    => "",
            "portfolio_separator"   			=> "",
            "separator_color"                   => "",
            "text_align"			            => ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = "";

        $_type_class = '';
        $_portfolio_space_class = '';
        $_portfolio_masonry_with_space_class = '';
        if ($type == "hover_text") {
            $_type_class = " hover_text";
            $_portfolio_space_class = "portfolio_with_space";
        } elseif ($type == "standard" || $type == "masonry_with_space"){
            $_type_class = " standard";
            $_portfolio_space_class = "portfolio_with_space";
            if($type == "masonry_with_space"){
                $_portfolio_masonry_with_space_class = ' masonry_with_space';
            }
        } elseif ($type == "standard_no_space"){
            $_type_class = " standard_no_space";
            $_portfolio_space_class = "portfolio_no_space";
        } elseif ($type == "hover_text_no_space"){
            $_type_class = " hover_text no_space";
            $_portfolio_space_class = "portfolio_no_space";
        }

        $_portfolio_masonry_with_space_class = '';
        if ($type == "hover_text") {
            $_type_class = " hover_text";
            $_portfolio_space_class = "portfolio_with_space portfolio_with_hover_text";
        } elseif ($type == "standard" || $type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
            $_type_class = " standard";
            $_portfolio_space_class = "portfolio_with_space portfolio_standard";
            if($type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
                $_portfolio_masonry_with_space_class = ' masonry_with_space';

                if($type == "masonry_with_space_without_description") {
                    $_portfolio_masonry_with_space_class .= ' masonry_with_space_only_image';
                }
            }
        } elseif ($type == "standard_no_space"){
            $_type_class = " standard_no_space";
            $_portfolio_space_class = "portfolio_no_space portfolio_standard";
        } elseif ($type == "hover_text_no_space"){
            $_type_class = " hover_text no_space";
            $_portfolio_space_class = "portfolio_no_space portfolio_with_hover_text";
        }

        $article_style = "";
        if (($type == "masonry_with_space" || $type == 'masonry_with_space_without_description') && $spacing !== ''){
            $article_style .= "padding: 0 " . intval($spacing)/2 . "px;";
            $article_style .= "margin-bottom: ".$spacing."px !important;";
        }
        $article_style = "style='".$article_style."'";

        $portfolio_box_style = "";
        $portfolio_description_class = "";
        if($box_border == "yes" || $box_background_color != ""){

            $portfolio_box_style .= "style=";
            if($box_border == "yes"){
                $portfolio_box_style .= "border-style:solid;";
                if($box_border_color != "" ){
                    $portfolio_box_style .= "border-color:" . $box_border_color . ";";
                }
                if($box_border_width != "" ){
                    $portfolio_box_style .= "border-width:" . $box_border_width . "px;";
                }
            }
            if($box_background_color != ""){
                $portfolio_box_style .= "background-color:" . $box_background_color . ";";
            }
            $portfolio_box_style .= "'";

        }

        if($text_align !== '') {
            $portfolio_description_class .= 'text_align_'.$text_align;
        }

        $portfolio_separator_aignment = "center";
        if($text_align != ""){
            $portfolio_separator_aignment = $text_align;
        }

        // adding portfolio loading
        $portfolio_loading_class = '';
        if($portfolio_loading_type !== '' && (!in_array($type, array('masonry_with_space', 'masonry','masonry_with_space_without_description'))) ) {
            $portfolio_loading_class = $portfolio_loading_type;
        }
        elseif($portfolio_loading_type_masonry !== ''){
            $portfolio_loading_class = $portfolio_loading_type_masonry;
        }

        $filter_style = "";
        if($filter_color != ""){
            $filter_style = " style='";
            $filter_style .= "color:$filter_color";
            $filter_style .= "'";
        }

        // adding hover type
        $hover_type = "";
        if ($type == 'standard' || $type == 'standard_no_space' || $type == 'masonry_with_space') {
            $hover_type = $hover_type_standard;
        }
        if ($type == 'hover_text' || $type == 'hover_text_no_space' || $type == 'masonry_with_space_without_description') {
            $hover_type = $hover_type_text_on_hover_image;
        }
        if ($type == 'masonry') {
            $hover_type = $hover_type_masonry;
        }

        $overlay_styles= array();
        if($hover_type !== 'default' && $overlay_background_color !== '') {
            $overlay_styles[] = 'background-color: '.$overlay_background_color;
        }

        $title_styles = array();
        if($title_color !== '') {
            $title_styles[] = 'color: '.$title_color;
        }

        if($title_font_size !== '') {
            $title_styles[] = 'font-size: '.$title_font_size.'px';
        }

        $category_styles = array();
        if($category_color !== '') {
            $category_styles[] = 'color: '.$category_color;
        }

        $separator_styles = array();
        if($separator_color !== '') {
            $separator_styles[] = 'background-color: '.$separator_color;
        }

        $show_description_box = $show_title == 'no' && $show_categories == 'no' ? false : true;

        if($type != 'masonry') {
            $html .= "<div class='projects_holder_outer v$columns $_portfolio_space_class $_portfolio_masonry_with_space_class'>";
            if ($filter == "yes") {

                if($type == 'masonry_with_space' || $type == 'masonry_with_space_without_description'){
                    $html .= "<div class='filter_outer'>";
                    $html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
                    if ($category == "") {
                        $args = array(
                            'parent' => 0
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    } else {
                        $top_category = get_term_by('slug', $category, 'portfolio_category');
                        $term_id = '';
                        if (isset($top_category->term_id))
                            $term_id = $top_category->term_id;
                        $args = array(
                            'parent' => $term_id
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    }
                    foreach ($portfolio_categories as $portfolio_category) {
                        $html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
                        $args = array(
                            'child_of' => $portfolio_category->term_id
                        );
                        $html .= '</li>';
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";

                }else{

                    $html .= "<div class='filter_outer'>";
                    $html .= "<div class='filter_holder'>
                            <ul>
                            <li class='filter' data-filter='all'><span". $filter_style .">" . __('All', 'qode') . "</span></li>";
                    if ($category == "") {
                        $args = array(
                            'parent' => 0
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    } else {
                        $top_category = get_term_by('slug', $category, 'portfolio_category');
                        $term_id = '';
                        if (isset($top_category->term_id))
                            $term_id = $top_category->term_id;
                        $args = array(
                            'parent' => $term_id
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    }
                    foreach ($portfolio_categories as $portfolio_category) {
                        $html .= "<li class='filter' data-filter='portfolio_category_$portfolio_category->term_id'><span". $filter_style .">$portfolio_category->name</span>";
                        $args = array(
                            'child_of' => $portfolio_category->term_id
                        );
                        $html .= '</li>';
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";
                }
            }

            $thumb_size_class = "";
            //get proper image size
            switch($image_size) {
                case 'landscape':
                    $thumb_size_class = 'portfolio_landscape_image';
                    break;
                case 'portrait':
                    $thumb_size_class = 'portfolio_portrait_image';
                    break;
                case 'square':
                    $thumb_size_class = 'portfolio_square_image';
                    break;
                default:
                    $thumb_size_class = 'portfolio_full_image';
                    break;
            }

            $html .= "<div class='projects_holder portfolio_main_holder clearfix v$columns$_type_class $thumb_size_class $portfolio_loading_class'>\n";
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }

            if ($category == "") {
                $args = array(
                    'post_type' => 'portfolio_page',
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            } else {
                $args = array(
                    'post_type' => 'portfolio_page',
                    'portfolio_category' => $category,
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            }
            $project_ids = null;
            if ($selected_projects != "") {
                $project_ids = explode(",", $selected_projects);
                $args['post__in'] = $project_ids;
            }
            query_posts($args);
            if (have_posts()) : while (have_posts()) : the_post();
                $terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');
                $html .= "<article class='mix ";
                foreach ($terms as $term) {
                    $html .= "portfolio_category_$term->term_id ";
                }

                $title = get_the_title();
                $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size

                if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
                    $large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
                } else {
                    $large_image = $featured_image_array[0];
                }

                $slug_list_ = "pretty_photo_gallery";

                //get proper image size
                switch($image_size) {
                    case 'landscape':
                        $thumb_size = 'portfolio-landscape';
                        break;
                    case 'portrait':
                        $thumb_size = 'portfolio-portrait';
                        break;
                    case 'square':
                        $thumb_size = 'portfolio-square';
                        break;
                    default:
                        $thumb_size = 'full';
                        break;
                }

                if($type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
                    $thumb_size = 'portfolio_masonry_with_space';
                }

                $custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
                $portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();

                if(get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true) != ""){
                    $custom_portfolio_link_target = get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true);
                } else {
                    $custom_portfolio_link_target = '_blank';
                }

                $target = $custom_portfolio_link != "" ? $custom_portfolio_link_target : '_self';

                $html .="' ";
                $html .= $article_style;
                $html .= ">";

                if($hover_type == 'default') {
                    $html .= "<div class='image_holder'>";
                    $html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
                    $html .= "<span class='image'>";
                    $html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
                    $html .= "</span>";
                    $html .= "</a>";

                    if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
                        $html .= "<span class='text_holder'>";
                        $html .= "<span class='text_outer'>";
                        $html .= "<span class='text_inner'>";
                        $html .= "<span class='feature_holder'>";
                        if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                            $html .= '<span class="feature_holder_icons">';
                            if ($lightbox == "yes") {
                                $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                            }
                            if($view_button !== "no"){
                                $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</a>";
                            }
                            if ($portfolio_qode_like == "on") {
                                $html .= "<span class='portfolio_like qbutton small white'>";
                                $portfolio_project_id = get_the_ID();

                                if (function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list();
                                }
                                $html .= "</span>";
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span></span></span></span>";


                    } else if ($type == "hover_text" || $type == "hover_text_no_space" || $type = 'masonry_with_space_without_description') {

                        $html .= "<span class='text_holder'>";
                        $html .= "<span class='text_outer'>";
                        $html .= "<span class='text_inner'>";
                        $html .= '<div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner">';

                        if($show_title !== 'no') {
                            $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                        }

                        if($portfolio_separator == "yes"){
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                            $k = 1;
                            foreach ($terms as $term) {
                                $html .= "$term->name";
                                if (count($terms) != $k) {
                                    $html .= ', ';
                                }
                                $k++;
                            }
                            $html .= '</span>';
                        }

                        $html .= '</div></div>';
                        $html .= "<span class='feature_holder'>";
                        if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                            $html .= '<span class="feature_holder_icons">';
                            if ($lightbox == "yes") {
                                $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                            }
                            if($view_button !== "no"){
                                $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</a>";
                            }
                            if ($portfolio_qode_like == "on") {
                                $html .= "<span class='portfolio_like qbutton small white'>";
                                $portfolio_project_id = get_the_ID();

                                if (function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list();
                                }
                                $html .= "</span>";
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span></span></span></span>";


                    }
                    $html .= "</div>";
                    if (($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") && $show_description_box) {
                        $html .= "<div class='portfolio_description ".$portfolio_description_class."'". $portfolio_box_style .">";

                        if($show_title !== 'no') {
                            $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                        }

                        if($portfolio_separator == "yes"){
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                            $k = 1;
                            foreach ($terms as $term) {
                                $html .= "$term->name";
                                if (count($terms) != $k) {
                                    $html .= ', ';
                                }
                                $k++;
                            }
                            $html .= '</span>';
                        }

                        $html .= '</div>';
                    }

                } else {
                    $category_html = "";
                    $k = 1;
                    foreach ($terms as $term) {
                        $category_html .= "$term->name";
                        if (count($terms) != $k) {
                            $category_html .= ' / ';
                        }
                        $k++;
                    }

                    $show_icons = "yes";
                    // disable icons on this hover type
                    if ($hover_type == 'cursor_change_hover' || $hover_type == 'thin_plus_only' || $hover_type == 'split_up') {
                        $show_icons = "no";
                    }

                    $disable_link = 'no';
                    // disable link if icons are shown for these hover type
                    if (($hover_type == 'subtle_vertical_hover' || $hover_type == 'image_subtle_rotate_zoom_hover' || $hover_type == 'image_text_zoom_hover') && $show_icons == 'yes') {
                        $disable_link = "yes";
                    }

                    $html .= '<div class="item_holder ' . $hover_type . '">';

                    switch ($hover_type) {
                        case 'subtle_vertical_hover':
                        case 'image_subtle_rotate_zoom_hover':
                        case 'image_text_zoom_hover':
                        case 'thin_plus_only':
                        case 'cursor_change_hover':
                            $html .= '<div class="text_holder">';
                            $html .= '<div class="text_holder_outer">';
                            $html .= '<div class="text_holder_inner">';
                            if($hover_type == 'thin_plus_only'){
                                $html .= '<span class="thin_plus_only_icon">+</span>';
                            }

                            elseif (in_array($type, array('hover_text', 'hover_text_no_space', 'masonry_with_space_without_description'))) {
                                if($show_title !== 'no') {
                                    $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                }

                                if($portfolio_separator == "yes") {
                                    $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                }

                                if($show_categories !== 'no') {
                                    $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                }
                            }

                            if($show_icons == 'yes') {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="'.__('Go to Project', 'qode').'" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>'; // icons_holder
                            }

                            $html .= '</div>'; // text_holder_inner
                            $html .= '</div>';  // text_holder_outer
                            $html .= '</div>'; // text_holder

                            break;
                        case 'slow_zoom':
                        case 'split_up':

                            if (in_array($type, array('hover_text', 'hover_text_no_space', 'masonry_with_space_without_description'))) {
                                $html .= '<div class="text_holder">';
                                $html .= '<div class="text_holder_outer">';
                                $html .= '<div class="text_holder_inner">';

                                if($show_title !== 'no') {
                                    $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                }

                                if($portfolio_separator == "yes") {
                                    $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                }

                                if($show_categories !== 'no') {
                                    $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                }

                                $html .= '</div>'; //text_holder_inner
                                $html .= '</div>';  // text_holder_outer
                                $html .= '</div>'; // text_holder
                            }

                            if ($show_icons == 'yes') {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="Go to Project" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>';  // icons_holder
                            }
                            break;
                    }

                    if($disable_link == 'no') {
                        $html .= '<a class="portfolio_link_class" title="' . $title . '" href="' . $portfolio_link . '"></a>';
                    }

                    $html .= '<div '.qode_get_inline_style($overlay_styles).' class="portfolio_shader"></div>';
                    $html .= '<div class="image_holder">';
                    $html .= '<span class="image">';
                    $html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
                    $html .= '</span>';
                    $html .= '</div>'; // close image_holder
                    $html .= '</div>'; // close item_holder
                    // portfolio description start

                    if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
                        $html .= "<div class='portfolio_description " . $portfolio_description_class . "' ". $portfolio_box_style .">";

                        if($show_title !== 'no') {
                            $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" target="' . $target . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                        }

                        if($portfolio_separator == "yes") {
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                        }

                        $html .= '</div>'; // close portfolio_description
                    }
                }

                $html .= "</article>\n";

            endwhile;

                $i = 1;
                while ($i <= $columns) {
                    $i++;
                    if ($columns != 1) {
                        $html .= "<div class='filler'></div>\n";
                    }
                }

            else:
                ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
            <?php
            endif;


            $html .= "</div>";
            if (get_next_posts_link()) {
                if ($show_load_more == "yes" || $show_load_more == "") {
                    $html .= '<div class="portfolio_paging"><span rel="' . $wp_query->max_num_pages . '" class="load_more">' . get_next_posts_link(__('Show more', 'qode')) . '</span></div>';
                    $html .= '<div class="portfolio_paging_loading"><a href="javascript: void(0)" class="qbutton">'.__('Loading...', 'qode').'</a></div>';
                }
            }
            $html .= "</div>";
            wp_reset_query();
        } else {
            if ($filter == "yes") {

                $html .= "<div class='filter_outer'>";
                $html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
                if ($category == "") {
                    $args = array(
                        'parent' => 0
                    );
                    $portfolio_categories = get_terms('portfolio_category', $args);
                } else {
                    $top_category = get_term_by('slug', $category, 'portfolio_category');
                    $term_id = '';
                    if (isset($top_category->term_id))
                        $term_id = $top_category->term_id;
                    $args = array(
                        'parent' => $term_id
                    );
                    $portfolio_categories = get_terms('portfolio_category', $args);
                }
                foreach ($portfolio_categories as $portfolio_category) {
                    $html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
                    $args = array(
                        'child_of' => $portfolio_category->term_id
                    );
                    $html .= '</li>';
                }
                $html .= "</ul></div>";
                $html .= "</div>";


            }

            $grid_number_of_columns = "gs5";
            if($grid_size == 4){
                $grid_number_of_columns = "gs4";
            }
            $html .= "<div class='projects_masonry_holder portfolio_main_holder ". $grid_number_of_columns ." ".$portfolio_loading_class."'>";
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
            if ($category == "") {
                $args = array(
                    'post_type' => 'portfolio_page',
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            } else {
                $args = array(
                    'post_type' => 'portfolio_page',
                    'portfolio_category' => $category,
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            }
            $project_ids = null;
            if ($selected_projects != "") {
                $project_ids = explode(",", $selected_projects);
                $args['post__in'] = $project_ids;
            }
            query_posts($args);
            if (have_posts()) : while (have_posts()) : the_post();
                $terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');
                $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size

                if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
                    $large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
                } else {
                    $large_image = $featured_image_array[0];
                }

                $custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
                $portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();

                if(get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true) != ""){
                    $custom_portfolio_link_target = get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true);
                } else {
                    $custom_portfolio_link_target = '_blank';
                }

                $target = $custom_portfolio_link != "" ? $custom_portfolio_link_target : '_self';

                $masonry_size = "default";
                $masonry_size =  get_post_meta(get_the_ID(), "qode_portfolio_type_masonry_style", true);
                $image_size="";
                if($masonry_size == "large_width"){
                    $image_size = "portfolio_masonry_wide";
                }elseif($masonry_size == "large_height"){
                    $image_size = "portfolio_masonry_tall";
                }elseif($masonry_size == "large_width_height"){
                    $image_size = "portfolio_masonry_large";
                } else{
                    $image_size = "portfolio_masonry_regular";
                }

                if($type == "masonry_with_space"){
                    $image_size = "portfolio_masonry_with_space";
                }

                $slug_list_ = "pretty_photo_gallery";
                $title = get_the_title();
                $html .= "<article class='portfolio_masonry_item ";
                foreach ($terms as $term) {
                    $html .= "portfolio_category_$term->term_id ";
                }
                $html .=" " . $masonry_size;
                $html .="'>";

                if($hover_type == 'default') {
                    $html .= "<div class='image_holder'>";
                    $html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
                    $html .= "<span class='image'>";
                    $html .= get_the_post_thumbnail(get_the_ID(), $image_size);
                    $html .= "</span>";
                    $html .= "</a>";
                    $html .= "<span class='text_holder'>";
                    $html .= "<span class='text_outer'>";
                    $html .= "<span class='text_inner'>";
                    $html .= '<div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner">';

                    if($show_title !== 'no') {
                        $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                    }

                    if($portfolio_separator == "yes"){
                        $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                    }

                    if($show_categories !== 'no') {
                        $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                        $k = 1;
                        foreach ($terms as $term) {
                            $html .= "$term->name";
                            if (count($terms) != $k) {
                                $html .= ', ';
                            }
                            $k++;
                        }
                        $html .= '</span>';
                    }

                    $html .= '</div></div>';
                    if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                        $html .= "<span class='feature_holder'>";

                        $html .= '<span class="feature_holder_icons">';
                        if ($lightbox == "yes") {
                            $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                        }
                        if($view_button !== "no"){
                            $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</i></a>";
                        }
                        if ($portfolio_qode_like == "on") {
                            $html .= "<span class='portfolio_like qbutton small white'>";
                            $portfolio_project_id = get_the_ID();

                            if (function_exists('qode_like_portfolio_list')) {
                                $html .= qode_like_portfolio_list();
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span>";

                        $html .= "</span>";
                    }
                    $html .= "</span></span></span>";
                    $html .= "</div>";
                } else {
                    $category_html = "";
                    $k = 1;
                    foreach ($terms as $term) {
                        $category_html .= "$term->name";
                        if (count($terms) != $k) {
                            $category_html .= ' / ';
                        }
                        $k++;
                    }

                    $show_icons = "yes";
                    // disable icons on this hover type
                    if ($hover_type == 'cursor_change_hover' || $hover_type == 'thin_plus_only' || $hover_type == 'split_up') {
                        $show_icons = "no";
                    }

                    $disable_link = 'no';
                    // disable link if icons are shown for these hover type
                    if (($hover_type == 'subtle_vertical_hover' || $hover_type == 'image_subtle_rotate_zoom_hover' || $hover_type == 'image_text_zoom_hover') && $show_icons == 'yes') {
                        $disable_link = "yes";
                    }

                    $html .= '<div class="item_holder ' . $hover_type . '">';

                    switch ($hover_type) {
                        case 'subtle_vertical_hover':
                        case 'image_subtle_rotate_zoom_hover':
                        case 'cursor_change_hover':
                        case 'image_text_zoom_hover':
                        case 'thin_plus_only':
                            if ( $show_icons == 'yes' || $hover_type == 'thin_plus_only' || $hover_type = 'cursor_change_hover') {
                                $html .= '<div class="text_holder">';
                                $html .= '<div class="text_holder_outer">';
                                $html .= '<div class="text_holder_inner">';
                                if($hover_type == 'thin_plus_only') {
                                    $html .= '<span class="thin_plus_only_icon">+</span>';
                                } else {
                                    if($show_title !== 'no') {
                                        $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                    }

                                    if($portfolio_separator == "yes") {
                                        $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                    }

                                    if($show_categories !== 'no') {
                                        $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                    }

                                    if ($show_icons == 'yes') {
                                        $html .= '<div class="icons_holder">';

                                        if($lightbox == "yes") {
                                            $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                        }

                                        if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                            $html .= qode_like_portfolio_list('icon');
                                        }

                                        if($view_button !== "no") {
                                            $html .= '<a class="preview" title="Go to Project" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                        }

                                        $html .= '</div>'; // icons_holder
                                    }
                                }
                                $html .= '</div>'; // text_holder_inner
                                $html .= '</div>';  // text_holder_outer
                                $html .= '</div>'; // text_holder
                            }

                            break;
                        case 'slow_zoom':
                        case 'split_up':
                            $html .= '<div class="text_holder">';
                            $html .= '<div class="text_holder_outer">';
                            $html .= '<div class="text_holder_inner">';

                            if($show_title !== 'no') {
                                $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                            }

                            if($portfolio_separator == "yes") {
                                $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                            }

                            if($show_categories !== 'no') {
                                $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                            }

                            $html .= '</div>'; //text_holder_inner
                            $html .= '</div>'; // text_holder_outer
                            $html .= '</div>';  // text_holder
                            if ($show_icons == "yes") {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="Preview" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>';  // icons_holder
                            }

                            break;
                    }

                    if($disable_link == 'no') {
                        $html .= '<a class="portfolio_link_class" title="' . $title . '" href="' . $portfolio_link . '"></a>';
                    }

                    $html .= '<div '.qode_get_inline_style($overlay_styles).' class="portfolio_shader"></div>';
                    $html .= '<div class="image_holder">';
                    $html .= '<span class="image">';
                    $html .= get_the_post_thumbnail(get_the_ID(), $image_size);
                    $html .= '</span>';
                    $html .= '</div>'; // close text_holder
                    $html .= '</div>'; // close item_holder
                }

                $html .= "</article>";

            endwhile;
            else:
                ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
            <?php
            endif;
            wp_reset_query();
            $html .= "</div>";
        }
        return $html;
    }

}
add_shortcode('portfolio_list', 'portfolio_list');

/* Portfolio Slider shortcode */

if (!function_exists('portfolio_slider')) {
    function portfolio_slider( $atts, $content = null ) {

        global $portfolio_project_id;
        global $qode_options_proya;
        $portfolio_qode_like = "on";
        if (isset($qode_options_proya['portfolio_qode_like'])) {
            $portfolio_qode_like = $qode_options_proya['portfolio_qode_like'];
        }

        $args = array(
            "order_by"          =>  "menu_order",
            "order"             =>  "ASC",
            "number"            =>  "-1",
            "category"          =>  "",
            "selected_projects" =>  "",
            "number_of_items"	=>  "",
            "lightbox"          =>  "",
            "title_tag"         =>  "h3",
            "separator"         =>  "",
            "hide_button"		=>  "",
            "image_size"        =>  "portfolio-square",
            "enable_navigation" =>  ""
        );
        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = "";
        $data_attr = "";

        if ($number_of_items !== ''){
        	$data_attr .= " data-number_of_items='".$number_of_items."' ";
        }

        $html .= "<div class='portfolio_slider_holder clearfix'><div class='portfolio_slider' " . $data_attr . " ><ul class='portfolio_slides'>";

        if ($category == "") {
            $q = array(
                'post_type' => 'portfolio_page',
                'orderby' => $order_by,
                'order' => $order,
                'posts_per_page' => $number
            );
        } else {
            $q = array(
                'post_type' => 'portfolio_page',
                'portfolio_category' => $category,
                'orderby' => $order_by,
                'order' => $order,
                'posts_per_page' => $number
            );
        }

        $project_ids = null;
        if ($selected_projects != "") {
            $project_ids = explode(",", $selected_projects);
            $q['post__in'] = $project_ids;
        }

        query_posts($q);

        if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();

            $title = get_the_title();
            $terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');

			//get proper image size
			switch($image_size) {
				case 'landscape':
					$thumb_size = 'portfolio-landscape';
					break;
                case 'portfolio_slider':
                    $thumb_size = 'portfolio_slider';
                    break;
                case 'portrait':
					$thumb_size = 'portfolio-portrait';
					break;
				case 'square':
					$thumb_size = 'portfolio-square';
					break;
				default:
					$thumb_size = 'full';
					break;
			}

            $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
            
            if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
                $large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
            } else {
                $large_image = $featured_image_array[0];
            }

            $custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
            $portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();
            
            if(get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true) != ""){
                $custom_portfolio_link_target = get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true);
            } else {
                $custom_portfolio_link_target = '_blank';
            }

            $target = $custom_portfolio_link != "" ? $custom_portfolio_link_target : '_self';

            $html .= "<li class='item'>";

            $html .= "<div class='image_holder'>";
            $html .= "<span class='image'>";
            $html .= "<span class='image_pixel_hover'></span>";
            $html .= "<a href='" . $portfolio_link . "' target='".$target."'>";
            $html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
            $html .= "</a>";
            $html .= "</span>"; /* close span.image */

            $html .= "<div class='hover_feature_holder'>";
            $html .= '<div class="hover_feature_holder_outer">';
            $html .= '<div class="hover_feature_holder_inner">';
            $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
			$separator_class = "";
			if($separator == "no"){
				$separator_class = " transparent";
			}

			$html .= '<span class="separator small' . $separator_class .'"></span>';
            $html .= '<div class="project_category">';
            $k = 1;
            foreach ($terms as $term) {
                $html .= "$term->name";
                if (count($terms) != $k) {
                    $html .= ', ';
                }
                $k++;
            }
            $html .= '</div>'; /* close div.project_category */

            if ($lightbox == "yes") {
                $html .= "<a class='lightbox qbutton white small' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[portfolio_slider]'>".__('zoom', 'qode')."</a>";
            }
			if ($hide_button !== 'yes'){
				$html .= '<a href="' . $portfolio_link . '" target="'.$target.'" class="qbutton white small">'.__('view', 'qode').'</a>';
			}
            $html .= '</div>'; /* close div.hover_feature_holder_inner */
            $html .= '</div>'; /* close div.hover_feature_holder_outer */
            $html .= "</div>"; /* close div.hover_feature_holder */
            $html .= "</div>"; /* close div.image_holder */

            $html .= "</li>";

            $postCount++;

        endwhile;

        else:
            $html .= __('Sorry, no posts matched your criteria.','qode');
        endif;

        wp_reset_query();

        $html .= "</ul>";
        if($enable_navigation){
            $html .= '<ul class="caroufredsel-direction-nav"><li><a id="caroufredsel-prev" class="caroufredsel-prev" href="#"><div><i class="fa fa-angle-left"></i></div></a></li><li><a class="caroufredsel-next" id="caroufredsel-next" href="#"><div><i class="fa fa-angle-right"></i></div></a></li></ul>';
        }
        $html .= "</div></div>";

        return $html;
    }
}
add_shortcode('portfolio_slider', 'portfolio_slider');

/* Progress bar horizontal shortcode */

if (!function_exists('progress_bar')) {

    function progress_bar($atts, $content = null) {
        $args = array(
            "title"                     				=> "",
            "title_color"               				=> "",
            "title_tag"                 				=> "h5",
            "percent"                   				=> "",
            "percent_color"             				=> "",
			"percent_font_size"         				=> "",
			"percent_font_weight"       				=> "",
            "active_background_color"   				=> "",
            "active_border_color"       				=> "",
            "noactive_background_color" 				=> "",
            "noactive_background_color_transparency" 	=> "",
            "height"                    				=> "",
            "border_radius"            					=> ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        //init variables
        $html                           = "";
        $progress_title_holder_styles   = "";
        $number_styles                  = "";
        $outer_progress_styles          = "";
        $percentage_styles              = "";

        //generate styles
        if ($title_color != "") {
            $progress_title_holder_styles .= "color: " . $title_color . ";";
        }

        if ($percent_color != "") {
            $number_styles .= "color: " . $percent_color . ";";
        }

		if ($percent_font_size != "") {
            $number_styles .= "font-size: " . $percent_font_size . "px;";
        }
		if ($percent_font_weight != "") {
			$number_styles .= "font-weight: " . $percent_font_weight . ";";
		}
        if ($height != "") {
            $valid_height = (strstr($height, 'px', true)) ? $height : $height . "px";
            $outer_progress_styles .= "height: " . $valid_height . ";";
            $percentage_styles .= "height: " . $valid_height . ";";
        }

		if ($border_radius != "") {
			$border_radius = (strstr($border_radius, 'px', true)) ? $border_radius : $border_radius . "px";
			$outer_progress_styles .= "border-radius: " . $border_radius . ";-moz-border-radius: " . $border_radius . ";-webkit-border-radius: " . $border_radius . ";";
		}

        if ($noactive_background_color != "") {

			if($noactive_background_color_transparency !== '' && ($noactive_background_color_transparency >= 0 && $noactive_background_color_transparency <= 1)) {
				$noactive_background_color = qode_hex2rgb($noactive_background_color);

				$outer_progress_styles .= "background-color: rgba(".$noactive_background_color[0].", ".$noactive_background_color[1].", ".$noactive_background_color[2].", ".$noactive_background_color_transparency.");";
			} else {
				$outer_progress_styles .= "background-color: " . $noactive_background_color . ";";
			}
        }

        if ($active_background_color != "") {
            $percentage_styles .= "background-color: " . $active_background_color . ";";
        }

        if($active_border_color != "") {
            $percentage_styles .= "border: 1px solid " . $active_border_color . ";";
        }

        $html .= "<div class='q_progress_bar'>";
        $html .= "<{$title_tag} class='progress_title_holder clearfix' style='{$progress_title_holder_styles}'>";
        $html .= "<span class='progress_title'>";
        $html .= "<span>$title</span>";
        $html .= "</span>"; //close progress_title

        $html .= "<span class='progress_number' style='{$number_styles}'>";
        $html .= "<span>0</span>%</span>";
        $html .= "</{$title_tag}>"; //close progress_title_holder

        $html .= "<div class='progress_content_outer' style='{$outer_progress_styles}'>";
        $html .= "<div data-percentage='" . $percent . "' class='progress_content' style='{$percentage_styles}'>";
        $html .="</div>"; //close progress_content
        $html .= "</div>"; //close progress_content_outer

        $html .= "</div>"; //close progress_bar
        return $html;
    }

    add_shortcode('progress_bar', 'progress_bar');
}

/* Progress bar vertical shortcode */

if (!function_exists('progress_bar_vertical')) {

    function progress_bar_vertical($atts, $content = null) {
        $args = array(
            "title"                             => "",
            "title_color"                       => "",
            "title_tag"                         => "h5",
            "title_size"                        => "",
            "percent"                           => "100",
            "percentage_text_size"              => "",
            "percent_color"                     => "",
            "bar_color"                         => "",
            "bar_border_color"                  => "",
            "background_color"                  => "",
            "border_radius"     	            => "",
            "text"                              => ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        //init variables
        $html               = "";
        $title_styles       = "";
        $bar_styles         = "";
        $percentage_styles  = "";
        $bar_holder_styles  = "";

        //generate styles
        if($title_color != "") {
            $title_styles .= "color:".$title_color.";";
        }

        if($title_size != "") {
            $title_styles .= "font-size:".$title_size."px;";
        }

        //generate bar holder gradient styles
        if($background_color != "") {
            $bar_holder_styles .= "background-color: " . $background_color . ";";
        }

		if($border_radius != "") {
			$bar_holder_styles .= "border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;";
		}

        //generate bar gradient styles
        if($bar_color != "") {
            $bar_styles .= "background-color: " . $bar_color . ";";
        }

        if($bar_border_color != "") {
            $bar_styles .= "border: 1px solid ".$bar_border_color.";";
        }

        if($percentage_text_size != "") {
            $percentage_styles .= "font-size: ".$percentage_text_size."px;";

        }

        if($percent_color != "") {
            $percentage_styles .= "color: ".$percent_color.";";
        }

        $html .= "<div class='q_progress_bars_vertical'>";
        $html .= "<div class='progress_content_outer' style='".$bar_holder_styles."'>";
        $html .= "<div data-percentage='$percent' class='progress_content' style='".$bar_styles."'></div>";
        $html .= "</div>"; //close progress_content_outer
        $html .= "<{$title_tag} class='progress_title' style='".$title_styles."'>$title</{$title_tag}>";
        $html .= "<span class='progress_number' style='".$percentage_styles."'>";
        $html .= "<span>$percent</span>%";
        $html .= "</span>"; //close progress_number
        $html .= "<span class='progress_text'>".$text."</span>"; //close progress_number
        $html .= "</div>"; //close progress_bars_vertical

        return $html;
    }

}
add_shortcode('progress_bar_vertical', 'progress_bar_vertical');

/* Progress bars icon shortcode */

if (!function_exists('progress_bar_icon')) {
    function progress_bar_icon($atts, $content = null) {
        extract(shortcode_atts(array("icons_number" => "","active_number" => "","type"=>"","icon" => "","size" => "","custom_size" => "","icon_color"=>"","icon_active_color"=>"","background_color"=>"","background_active_color"=>"","border_color"=>"","border_active_color"=>""), $atts));
        $html =  "<div class='q_progress_bars_icons_holder'><div class='q_progress_bars_icons'><div class='q_progress_bars_icons_inner $type ";
        if($custom_size != ""){
            $html .= "custom_size";
        } else {
            $html .= "$size";
        }
        $html .= " clearfix' data-number='".$active_number."'";
        if($custom_size != ""){
            $html .= " data-size='".$custom_size."'";
        }
        $html .= ">";
        $i = 0;
        while ($i < $icons_number) {
            $html .= "<div class='bar'><span class='bar_noactive fa-stack ";
            if($size != ""){
                if($size == "tiny"){
                    $html .= "fa-lg";
                } else if($size == "small"){
                    $html .= "fa-2x";
                } else if($size == "medium"){
                    $html .= "fa-3x";
                } else if($size == "large"){
                    $html .= "fa-4x";
                } else if($size == "very_large"){
                    $html .= "fa-5x";
                }
            }
            $html .= "'";
            if($type == "circle" || $type == "square"){
                if($background_active_color != "" || $border_active_color != ""){
                    $html .= " style='";
                    if($background_active_color != ""){
                        $html .= "background-color: ".$background_active_color.";";
                    }
                    if($border_active_color != ""){
                        $html .= " border-color: ".$border_active_color.";";
                    }
                    $html .= "'";
                }
            }
            $html .= ">";

            $html .= "<i class='fa fa-stack-1x ".$icon."'";
            if($icon_active_color != ""){
                $html .= " style='color: ".$icon_active_color.";'";
            }
            $html .= "></i></span><span class='bar_active fa-stack ";
            if($size != ""){
                if($size == "tiny"){
                    $html .= "fa-lg";
                } else if($size == "small"){
                    $html .= "fa-2x";
                } else if($size == "medium"){
                    $html .= "fa-3x";
                } else if($size == "large"){
                    $html .= "fa-4x";
                } else if($size == "very_large"){
                    $html .= "fa-5x";
                }
            }
            $html .= "'";
            if($type == "circle" || $type == "square"){
                if($background_color != "" || $border_color != ""){
                    $html .= " style='";
                    if($background_color != ""){
                        $html .= "background-color: ".$background_color.";";
                    }
                    if($border_color != ""){
                        $html .= " border-color: ".$border_color.";";
                    }
                    $html .= "'";
                }
            }
            $html .= ">";

            $html .= "<i class='fa ".$icon." fa-stack-1x'";
            if($icon_color != ""){
                $html .= " style='color: ".$icon_color.";'";
            }
            $html .= "></i></span></div>";
            $i++;
        }
        $html .= "</div></div></div>";
        return $html;
    }
}
add_shortcode('progress_bar_icon', 'progress_bar_icon');

/* Social Icons shortcode */

if (!function_exists('social_icons')) {
    function social_icons($atts, $content = null) {
        global $qodeIconCollections;

        $args = array(
            "type"                              => "",
            "link"                              => "",
            "target"                            => "",
            "use_custom_size"                   => "",
            "custom_size"                       => "",
            "custom_shape_size"                 => "",
            "size"                              => "",
            "border_radius"                     => "",
            "icon_color"                        => "",
            "icon_hover_color"                  => "",
            "background_color"                  => "",
            "background_hover_color"            => "",
            "background_color_transparency"     => "",
            "border_width"                      => "",
            "border_color"                      => "",
            "border_hover_color"                => "",
            "icon_margin"                       => ""
        );

        $args = array_merge($args, $qodeIconCollections->getShortcodeParams());

        extract(shortcode_atts($args, $atts));

        $html               = "";
        $fa_stack_styles    = "";
        $icon_styles        = "";
        $data_attr          = "";

//        $background_color = $background_color != "" ? $background_color : "#e3e3e3";

        if(!empty($background_color) && !empty($background_color_transparency) && ($background_color_transparency >= 0 && $background_color_transparency <= 1)) {
            $rgb = qode_hex2rgb($background_color);

            $background_color = 'rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].', '.$background_color_transparency.')';
        }

        if(!empty($background_color)) {
            $fa_stack_styles .= "background-color: {$background_color};";
        }

        if($type == 'square_social' && $border_radius !== '') {
            $fa_stack_styles .= 'border-radius: '.$border_radius.'px;';
            $fa_stack_styles .= '-webkit-border-radius: '.$border_radius.'px;';
            $fa_stack_styles .= '-moz-border-radius: '.$border_radius.'px;';
        }

        if($border_color != "") {
            $fa_stack_styles .= "border-color: ".$border_color.";";
        }

        if($border_width != "") {
            $fa_stack_styles .= "border-width: ".$border_width."px;";
        }

        if($icon_color != ""){
            $icon_styles .= "color: ".$icon_color.";";
        }

        if($icon_margin != "") {
            if($type == 'circle_social' || $type == 'square_social') {
                $fa_stack_styles .= "margin: ".$icon_margin.";";
            } else {
                $icon_styles .= "margin: ".$icon_margin.";";
            }

        }

        if($background_hover_color != "") {
            $data_attr .= "data-hover-background-color=".$background_hover_color." ";
        }

        if($border_hover_color != "") {
            $data_attr .= "data-hover-border-color=".$border_hover_color." ";
        }

        if($icon_hover_color != "") {
            $data_attr .= "data-hover-color=".$icon_hover_color;
        }

        if($use_custom_size == 'yes') {
            if($custom_size !== '') {
                $icon_styles .= 'font-size: '.$custom_size."px;";
            }

            if($custom_shape_size !== '') {
                $fa_stack_styles .= 'font-size: '.$custom_shape_size."px;";
            } elseif($custom_size !== '' && $custom_shape_size == '') {
                $fa_stack_styles .= 'font-size: '.$custom_size."px;";
            }
        }

        $html .= "<span class='q_social_icon_holder $type' $data_attr>";

        if($link != ""){
            $html .= "<a href='".$link."' target='".$target."'>";
        }

        //have to set default because of already created shortcodes
        $icon_pack = $icon_pack == '' ? 'font_awesome' : $icon_pack;

        if($type == "normal_social"){
            $html .= $qodeIconCollections->getIconHTML(
                ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                $icon_pack,
                array('icon_attributes' => array('style' => $icon_styles, 'class' => $size.' simple_social')));
        } else {
            $html .= "<span class='fa-stack ".$size."' style='".$fa_stack_styles."'>";

            $html .= $qodeIconCollections->getIconHTML(
                ${$qodeIconCollections->getIconCollectionParamNameByKey($icon_pack)},
                $icon_pack,
                array('icon_attributes' => array('style' => $icon_styles, 'class' => '')));

            $html .= "</span>"; //close fa-stack
        }

        if($link != ""){
            $html .= "</a>";
        }

        $html .= "</span>"; //close q_social_icon_holder
        return $html;
    }
}
add_shortcode('social_icons', 'social_icons');

/* Social Share shortcode */

if (!function_exists('social_share')) {
    function social_share($atts, $content = null) {
        global $qode_options_proya;
        if(isset($qode_options_proya['twitter_via']) && !empty($qode_options_proya['twitter_via'])) {
            $twitter_via = " via " . $qode_options_proya['twitter_via'] . " ";
        } else {
            $twitter_via = 	"";
        }
        if(isset($_SERVER["https"])) {
            $count_char = 23;
        } else{
            $count_char = 22;
        }
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        $html = "";
        if(isset($qode_options_proya['enable_social_share']) && $qode_options_proya['enable_social_share'] == "yes") {
            $post_type = get_post_type();
            if(isset($qode_options_proya["post_types_names_$post_type"])) {
                if($qode_options_proya["post_types_names_$post_type"] == $post_type) {
                    if($post_type == "portfolio_page") {
                        $html .= '<div class="portfolio_share">';
                    } elseif($post_type == "post") {
                        $html .= '<div class="blog_share">';
                    } elseif($post_type == "page") {
                        $html .= '<div class="page_share">';
                    }
                    $html .= '<div class="social_share_holder">';
                    $html .= '<a href="javascript:void(0)" target="_self"><span class="social_share_icon"></span>';
                    $html .= '<span class="social_share_title">'.  __('Share','qode') .'</span>';
                    $html .= '</a>';
                    $html .= '<div class="social_share_dropdown"><div class="inner_arrow"></div><ul>';
                    if(isset($qode_options_proya['enable_facebook_share']) &&  $qode_options_proya['enable_facebook_share'] == "yes") {
                        $html .= '<li class="facebook_share">';
                        $html .= '<a href="#" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . urlencode(qode_addslashes(get_the_title())) . '&amp;p[summary]=' . urlencode(qode_addslashes(get_the_excerpt())) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;&p[images][0]=';
                        if(function_exists('the_post_thumbnail')) {
                            $html .=  wp_get_attachment_url(get_post_thumbnail_id());
                        }
                        $html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
                        if(!empty($qode_options_proya['facebook_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["facebook_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-facebook"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Facebook","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }

                    if($qode_options_proya['enable_twitter_share'] == "yes") {
                        $html .= '<li class="twitter_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength($count_char) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                        if(!empty($qode_options_proya['twitter_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["twitter_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-twitter"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Twitter", 'qode') . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if($qode_options_proya['enable_google_plus'] == "yes") {
                        $html .= '<li  class="google_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['google_plus_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['google_plus_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-google-plus"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Google+","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_linkedin']) && $qode_options_proya['enable_linkedin'] == "yes") {
                        $html .= '<li  class="linkedin_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['linkedin_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['linkedin_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-linkedin"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("LinkedIn","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_tumblr']) && $qode_options_proya['enable_tumblr'] == "yes") {
                        $html .= '<li  class="tumblr_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['tumblr_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['tumblr_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-tumblr"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Tumblr","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_pinterest']) && $qode_options_proya['enable_pinterest'] == "yes") {
                        $html .= '<li  class="pinterest_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . qode_addslashes(get_the_title()) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['pinterest_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['pinterest_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-pinterest"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Pinterest","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_vk']) && $qode_options_proya['enable_vk'] == "yes") {
                        $html .= '<li  class="vk_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['vk_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['vk_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-vk"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("VK","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";

                    if($post_type == "portfolio_page" || $post_type == "post" || $post_type == "page") {
                        $html .= '</div>';
                    }
                }
            }
        }
        return $html;
    }
}
add_shortcode('social_share', 'social_share');

/* Social Share shortcode */

if (!function_exists('social_share_list')) {
    function social_share_list($atts, $content = null) {
        global $qode_options_proya;
        if(isset($qode_options_proya['twitter_via']) && !empty($qode_options_proya['twitter_via'])) {
            $twitter_via = " via " . $qode_options_proya['twitter_via'] . " ";
        } else {
            $twitter_via = 	"";
        }
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        $html = "";
        if(isset($qode_options_proya['enable_social_share']) && $qode_options_proya['enable_social_share'] == "yes") {
            $post_type = get_post_type();

            if(isset($qode_options_proya["post_types_names_$post_type"])) {
                if($qode_options_proya["post_types_names_$post_type"] == $post_type) {
                    $html .= '<div class="social_share_list_holder">';
                    $html .= "<span>".__('Share on: ', 'qode')."</span>";
                    $html .= '<ul>';

                    if(isset($qode_options_proya['enable_facebook_share']) &&  $qode_options_proya['enable_facebook_share'] == "yes") {
                        $html .= '<li class="facebook_share">';
                        $html .= '<a title="'.__("Share on Facebook","qode").'" href="#" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . urlencode(qode_addslashes(get_the_title())) . '&amp;p[summary]=' . urlencode(qode_addslashes(strip_tags(get_the_excerpt()))) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;&p[images][0]=';
                        if(function_exists('the_post_thumbnail')) {
                            $html .=  wp_get_attachment_url(get_post_thumbnail_id());
                        }
                        $html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
                        if(!empty($qode_options_proya['facebook_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["facebook_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-facebook"></i>';
                        }
                        $html .= "</a>";
                        $html .= "</li>";
                    }

                    if($qode_options_proya['enable_twitter_share'] == "yes") {
                        $html .= '<li class="twitter_share">';
                        $html .= '<a href="#" title="'.__("Share on Twitter", 'qode').'" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength(mb_strlen(get_permalink())) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                        if(!empty($qode_options_proya['twitter_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["twitter_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-twitter"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if($qode_options_proya['enable_google_plus'] == "yes") {
                        $html .= '<li  class="google_share">';
                        $html .= '<a href="#" title="'.__("Share on Google+","qode").'" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['google_plus_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['google_plus_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-google-plus"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_linkedin']) && $qode_options_proya['enable_linkedin'] == "yes") {
                        $html .= '<li  class="linkedin_share">';
                        $html .= '<a href="#" class="'.__("Share on LinkedIn","qode").'" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['linkedin_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['linkedin_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-linkedin"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_tumblr']) && $qode_options_proya['enable_tumblr'] == "yes") {
                        $html .= '<li  class="tumblr_share">';
                        $html .= '<a href="#" title="'.__("Share on Tumblr","qode").'" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['tumblr_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['tumblr_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-tumblr"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_pinterest']) && $qode_options_proya['enable_pinterest'] == "yes") {
                        $html .= '<li  class="pinterest_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" title="'.__("Share on Pinterest","qode").'" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . qode_addslashes(get_the_title()) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['pinterest_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['pinterest_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-pinterest"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_vk']) && $qode_options_proya['enable_vk'] == "yes") {
                        $html .= '<li  class="vk_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" title="'.__("Share on VK","qode").'" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['vk_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['vk_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-vk"></i>';
                        }

                        $html .= "</a>";
                        $html .= "</li>";
                    }

                    $html .= '</ul>'; //close ul
                    $html .= '</div>'; //close div.social_share_list_holder
                }
            }
        }
        return $html;
    }

    add_shortcode('social_share_list', 'social_share_list');
}

/* Steps shortcode */

//if (!function_exists('steps')) {
//    /**
//     * @deprecated
//     * @param $atts
//     * @param null $content
//     * @return string
//     */
//    function steps($atts, $content = null) {
//        $args = array(
//            "number_of_steps"   => "4",
//            "background_color" => "",
//            "number_color" => "",
//            "title_color" => "",
//            "circle_wrapper_border_color" => "",
//
//            "title_1" => "",
//            "step_number_1" => "",
//            "step_description_1" => "",
//            "step_link_1" => "",
//            "step_link_target_1" => "_blank",
//
//            "title_2" => "",
//            "step_number_2" => "",
//            "step_description_2" => "",
//            "step_link_2" => "",
//            "step_link_target_2" => "_self",
//
//            "title_3" => "",
//            "step_number_3" => "",
//            "step_description_3" => "",
//            "step_link_3" => "",
//            "step_link_target_3" => "_self",
//
//            "title_4" => "",
//            "step_number_4" => "",
//            "step_description_4" => "",
//            "step_link_4" => "",
//            "step_link_target_4" => "_self"
//        );
//
//        extract(shortcode_atts($args, $atts));
//
//        $steps_array = array();
//
//        //for the number of steps
//        for ($i = 1; $i <= $number_of_steps; $i++) {
//            //generate object for each step that  holds that steps data
//            $step_object = new stdClass();
//
//            $step_object->title = ${"title_".$i};
//            $step_object->step_number = ${"step_number_".$i};
//            $step_object->step_description = ${"step_description_".$i};
//            $step_object->step_link = ${"step_link_".$i};
//            $step_object->step_link_target = ${"step_link_target_".$i};
//
//            //push object to steps array
//            $steps_array[] = $step_object;
//        }
//
//        //init variables
//        $html                   = "";
//        $number_styles          = "";
//        $title_styles           = "";
//        $circle_styles          = "";
//        $circle_wrapper_styles  = "";
//
//        if($number_color != "") {
//            $number_styles .= "color: ".$number_color.";";
//        }
//
//        if($title_color != "") {
//            $title_styles .= "color: ".$title_color.";";
//        }
//
//        if($background_color != "") {
//            $circle_styles .= "background-color: ".$background_color.";";
//        }
//
//        if($circle_wrapper_border_color != "") {
//            $circle_wrapper_styles .= "border-top-color: ".$circle_wrapper_border_color.";";
//        }
//
//        if(is_array($steps_array) && count($steps_array)) {
//            $html .= "<div class='q_steps_holder'>";
//            $html .= "<div class='steps_holder_inner'>";
//
//
//            for($i = 1; $i <= count($steps_array); $i++) {
//                $step = $steps_array[$i - 1];
//                $html .= "<div class='circle_small_holder step{$i}'>";
//                $html .= "<div class='circle_small_holder_inner'>";
//                $html .= "<div class='circle_small_wrapper' style='{$circle_wrapper_styles}'>";
//                $html .= "<div class='circle_small' style='{$circle_styles}'>";
//
//                if($step->step_link != "") {
//                    $html .= "<a href='{$step->step_link}' target='{$step->step_link_target}' class='circle_small_inner'>";
//
//                    if($step->step_number != "") {
//                        $html .= "<span style='{$number_styles}'>{$step->step_number}</span>";
//                    }
//
//                    $html .= "<p class='step_title' style='{$title_styles}'>{$step->title}</p>";
//                    $html .= "</a>"; //close circle_small_inner
//                } else {
//                    $html .= "<div class='circle_small_inner'>";
//
//                    if($step->step_number != "") {
//                        $html .= "<span style='{$number_styles}'>{$step->step_number}</span>";
//                    }
//
//                    $html .= "<p class='step_title' style='{$title_styles}'>{$step->title}</p>";
//                    $html .= "</div>"; //close circle_small_inner
//                }
//
//                $html .= "</div>"; //close circle_small
//                $html .= "</div>"; //close circle_small_wrapper
//
//                $html .= "</div>"; //close circle_small_holder_inner
//                $html .= "<p>{$step->step_description}</p>";
//                $html .= "</div>"; //close circle_small_holder
//            }
//
//            $html .= "</div>"; //close steps_holder_inner
//            $html .= "</div>"; //close steps_holder
//        }
//
//        return $html;
//
//    }
//}
//add_shortcode('steps', 'steps');


/* Team shortcode */

if (!function_exists('q_team')) {
    function q_team($atts, $content = null) {
        $args = array(
        	"type"						=> "",
            "team_image"				=> "",
            "team_name"					=> "",
            "name_color"				=> "",
            "team_position"				=> "",
            "position_color"			=> "",
            "team_description"			=> "",
            "background_color"			=> "",
            "overlay_color"				=> "",
            "box_border"				=> "",
            "box_border_width"			=> "",
            "box_border_color"			=> "",
            "show_separator"			=> "",
            "separator_color"			=> "",
            "icons_color"				=> "",
            "team_social_icon_1"		=> "",
            "team_social_icon_1_link"	=> "",
            "team_social_icon_1_target"	=> "",
            "team_social_icon_2"		=> "",
            "team_social_icon_2_link"	=> "",
            "team_social_icon_2_target"	=> "",
            "team_social_icon_3"		=> "",
            "team_social_icon_3_link"	=> "",
            "team_social_icon_3_target"	=> "",
            "team_social_icon_4"		=> "",
            "team_social_icon_4_link"	=> "",
            "team_social_icon_4_target"	=> "",
            "team_social_icon_5"		=> "",
            "team_social_icon_5_link"	=> "",
            "team_social_icon_5_target"	=> "",
            "title_tag"					=> "h3"
        );

        extract(shortcode_atts($args, $atts));
        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];
        if(is_numeric($team_image)) {
            $team_image_src = wp_get_attachment_url( $team_image );
        } else {
            $team_image_src = $team_image;
        }

		$q_team_style = "";
		if($background_color != ""){
			$q_team_style .= " style='";

					$q_team_style .= 'background-color:' . $background_color . ';';

			$q_team_style .= "'";
		}

		$qteam_box_style = "";
		if($box_border == "yes"){

			$qteam_box_style .= "style=";

				$qteam_box_style .= "border-style:solid;";
				if($box_border_color != "" ){
					$qteam_box_style .= "border-color:" . $box_border_color . ";";
				}
				if($box_border_width != "" ){
					$qteam_box_style .= "border-width:" . $box_border_width . "px;";
				}

			$qteam_box_style .= "'";

		}

		$qteam_info_on_hover_box_style = "style= '";
		if ($type == "info_on_hover" && $overlay_color != "") {
			$qteam_info_on_hover_box_style .= "background-color:" . $overlay_color . ";";
		}
		$qteam_info_on_hover_box_style .= "'";

		$name_style = "style = '";
		if ($name_color !== "") {
			$name_style .= 'color: ' . $name_color . ';';
		}
		$name_style .= "'";

		$position_style = "style = '";
		if ($position_color !== "") {
			$position_style .= 'color: ' . $position_color . ';';
		}
		$position_style .= "'";

		$separator_style = "style = '";
		if ($separator_color !== "") {
			$separator_style .= 'background-color: ' . $separator_color . ';';
		}
		$separator_style .= "'";

		if ($type == "info_on_hover") {
			$html =  "<div class='q_team info_on_hover' ". $q_team_style .">";
	        $html .=  "<div class='q_team_inner'>";
	        if($team_image != "") {
	            $html .=  "<div class='q_team_image'>";
	            $html .= "<img src='$team_image_src' alt='' />";

    	        $html .=  "<div class='q_team_text' ". $qteam_info_on_hover_box_style .">";
    	        $html .=  "<div class='q_team_text_holder'>";
    	        $html .=  "<div class='q_team_text_holder_inner'>";
		        $html .=  "<div class='q_team_text_inner'>";
		        $html .=  "<div class='q_team_title_holder'>";
		        $html .=  "<$title_tag class='q_team_name' " . $name_style . ">";
		        $html .= $team_name;
		        $html .=  "</$title_tag>";
		        if($team_position != "") {
		            $html .= "<span " . $position_style . ">" . $team_position . "</span>";
		        }
		        $html .=  "</div>";
				if($show_separator != "no"){
		        	$html .=  "<div class='separator small center' " . $separator_style . "></div>";
				}
		        $html .=  "</div>";
		        $html .=  "<div class='q_team_social_holder'>";
		        if($team_social_icon_1 != "") {
		            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_1 .'" size="fa-2x" link="' . $team_social_icon_1_link . '" target="' . $team_social_icon_1_target . '" icon_color="' . $icons_color . '"]');
		        }
		        if($team_social_icon_2 != "") {
		            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_2 .'" size="fa-2x" link="' . $team_social_icon_2_link . '" target="' . $team_social_icon_2_target . '" icon_color="' . $icons_color . '"]');
		        }
		        if($team_social_icon_3 != "") {
		            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_3 .'" size="fa-2x" link="' . $team_social_icon_3_link . '" target="' . $team_social_icon_3_target . '" icon_color="' . $icons_color . '"]');
		        }
		        if($team_social_icon_4 != "") {
		            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_4 .'" size="fa-2x" link="' . $team_social_icon_4_link . '" target="' . $team_social_icon_4_target . '" icon_color="' . $icons_color . '"]');
		        }
		        if($team_social_icon_5 != "") {
		            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_5 .'" size="fa-2x" link="' . $team_social_icon_5_link . '" target="' . $team_social_icon_5_target . '" icon_color="' . $icons_color . '"]');
		        }

		        $html .=  "</div>";
		        $html .=  "</div>";
		        $html .=  "</div>";
		        $html .=  "</div>";
		        $html .=  "</div>";

	            $html .=  "</div>";
	        }

	            if($team_description != "") {
	                $html .= "<div class='q_team_description_wrapper' ".$qteam_box_style.">";
	                $html .= "<div class='q_team_description'>";
	                $html .= "<div class='q_team_description_inner'>";
	                $html .= "<p>".$team_description."</p>";
	                $html .= "</div>"; // close q_team_description_inner
	                $html .= "</div>"; // close q_team_description
	                $html .= "</div>"; // close q_team_description_wrapper
	            }

	        $html .=  "</div>";
		}
		else {
	        $html =  "<div class='q_team'". $q_team_style .">";
	        $html .=  "<div class='q_team_inner'>";
	        if($team_image != "") {
	            $html .=  "<div class='q_team_image'>";
	            $html .= "<img src='$team_image_src' alt='' />";

	            if($team_description != "") {
	                $html .= "<div class='q_team_description_wrapper'>";
	                $html .= "<div class='q_team_description'>";
	                $html .= "<div class='q_team_description_inner'>";
	                $html .= "<p>".$team_description."</p>";
	                $html .= "</div>"; // close q_team_description_inner
	                $html .= "</div>"; // close q_team_description
	                $html .= "</div>"; // close q_team_description_wrapper
	            }

	            $html .=  "</div>";
	        }
	        $html .=  "<div class='q_team_text' ". $qteam_box_style .">";
	        $html .=  "<div class='q_team_text_inner'>";
	        $html .=  "<div class='q_team_title_holder'>";
	        $html .=  "<$title_tag class='q_team_name' " . $name_style . ">";
	        $html .= $team_name;
	        $html .=  "</$title_tag>";
	        if($team_position != "") {
	            $html .= "<span " . $position_style . ">" . $team_position . "</span>";
	        }
	        $html .=  "</div>";
			if($show_separator != "no"){
	        	$html .=  "<div class='separator small center' " . $separator_style . "></div>";
			}
	        $html .=  "</div>";
	        $html .=  "<div class='q_team_social_holder'>";
	        if($team_social_icon_1 != "") {
	            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_1 .'" size="fa-2x" link="' . $team_social_icon_1_link . '" target="' . $team_social_icon_1_target . '" icon_color="' . $icons_color . '"]');
	        }
	        if($team_social_icon_2 != "") {
	            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_2 .'" size="fa-2x" link="' . $team_social_icon_2_link . '" target="' . $team_social_icon_2_target . '" icon_color="' . $icons_color . '"]');
	        }
	        if($team_social_icon_3 != "") {
	            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_3 .'" size="fa-2x" link="' . $team_social_icon_3_link . '" target="' . $team_social_icon_3_target . '" icon_color="' . $icons_color . '"]');
	        }
	        if($team_social_icon_4 != "") {
	            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_4 .'" size="fa-2x" link="' . $team_social_icon_4_link . '" target="' . $team_social_icon_4_target . '" icon_color="' . $icons_color . '"]');
	        }
	        if($team_social_icon_5 != "") {
	            $html .=  do_shortcode('[social_icons type="normal_social" icon="'. $team_social_icon_5 .'" size="fa-2x" link="' . $team_social_icon_5_link . '" target="' . $team_social_icon_5_target . '" icon_color="' . $icons_color . '"]');
	        }

	        $html .=  "</div>";
	        $html .=  "</div>";
	        $html .=  "</div>";
	        $html .=  "</div>";
	    }
        return $html;
    }
}
add_shortcode('q_team', 'q_team');


/* Testimonials shortcode */

if (!function_exists('testimonials')) {

    function testimonials($atts, $content = null) {
        $deafult_args = array(
            "number"					=> "-1",
			"order_by"					=> "date",
			"order"						=> "DESC",
            "category"					=> "",
            "author_image"				=> "",
            "text_color"				=> "",
            "text_font_size"			=> "",
            "author_text_font_weight"	=> "",
            "author_text_color"			=> "",
			"author_text_font_size"		=> "",
            "show_navigation"			=> "",
            "navigation_style"			=> "",
            "auto_rotate_slides"		=> "",
            "animation_type"			=> "",
            "animation_speed"			=> ""
        );

        extract(shortcode_atts($deafult_args, $atts));

        $html                           = "";
        $testimonial_text_inner_styles  = "";
        $testimonial_p_style			= "";
        $navigation_button_radius		= "";
        $testimonial_name_styles        = "";

		if($text_font_size != "" || $text_color != ""){
			$testimonial_p_style = " style='";
			if($text_font_size != ""){
				$testimonial_p_style .= "font-size:". $text_font_size . "px;";
			}
			if($text_color != ""){
				$testimonial_p_style .= "color:". $text_color . ";";
			}
			$testimonial_p_style .= "'";
		}

        if($text_color != "") {
            $testimonial_text_inner_styles  .= "color: ".$text_color.";";
            $testimonial_name_styles        .= "color: ".$text_color.";";
        }

		if($author_text_font_weight != '') {
			$testimonial_name_styles .= 'font-weight: '.$author_text_font_weight.';';
		}

        if($author_text_color != "") {
            $testimonial_name_styles .= "color: ".$author_text_color.";";
        }

		if($author_text_font_size != "") {
			$testimonial_name_styles .= "font-size: ".$author_text_font_size."px;";
		}

        $args = array(
            'post_type' => 'testimonials',
            'orderby' => $order_by,
            'order' => $order,
            'posts_per_page' => $number
        );

        if ($category != "") {
            $args['testimonials_category'] = $category;
        }

		$animation_type_data = 'fade';
		switch($animation_type) {
			case 'fade':
			case 'fade_option' :
				$animation_type_data = 'fade';
				break;
			case 'slide':
			case 'slide_option':
				$animation_type_data = 'slide';
				break;
			default:
				$animation_type_data = 'fade';
		}

        $html .= "<div class='testimonials_holder clearfix ".$navigation_style."'>";
        $html .= '<div class="testimonials testimonials_carousel" data-show-navigation="'.$show_navigation.'" data-animation-type="'.$animation_type_data.'" data-animation-speed="'.$animation_speed.'" data-auto-rotate-slides="'.$auto_rotate_slides.'">';
        $html .= '<ul class="slides">';

        query_posts($args);
        if (have_posts()) :
            while (have_posts()) : the_post();
                $author = get_post_meta(get_the_ID(), "qode_testimonial-author", true);
                $website = get_post_meta(get_the_ID(), "qode_testimonial_website", true);
                $company_position = get_post_meta(get_the_ID(), "qode_testimonial-company_position", true);
                $text = get_post_meta(get_the_ID(), "qode_testimonial-text", true);
				$testimonial_author_image = wp_get_attachment_image_src(get_post_thumbnail_id(), "full");

                $html .= '<li id="testimonials' . get_the_ID() . '" class="testimonial_content">';
                $html .= '<div class="testimonial_content_inner"';

                $html .= '>';

				if($author_image == "yes"){
					$html .= '<div class="testimonial_image_holder">';
					$html .= '<img src="'. $testimonial_author_image[0] .'" />';
					$html .= '</div>';
				}
                $html .= '<div class="testimonial_text_holder">';
                $html .= '<div class="testimonial_text_inner" style="'.$testimonial_text_inner_styles.'">';
                $html .= '<p'. $testimonial_p_style .'>' . trim($text) . '</p>';

                $html .= '<p class="testimonial_author" style="'.$testimonial_name_styles.'">' . $author;

                if($website != "") {
                    $html .= '<span class="author_company_divider"> - </span><span class="author_company">' . $website.'</span>';
                }

                $html .= '</p>';
                $html .= '</div>'; //close testimonial_text_inner
                $html .= '</div>'; //close testimonial_text_holder

                $html .= '</div>'; //close testimonial_content_inner
                $html .= '</li>'; //close testimonials
            endwhile;
        else:
            $html .= __('Sorry, no posts matched your criteria.', 'qode');
        endif;

        wp_reset_query();
        $html .= '</ul>';//close slides
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
add_shortcode('testimonials', 'testimonials');

/* Unordered List shortcode */

if (!function_exists('unordered_list')) {
    function unordered_list($atts, $content = null) {
        $args = array(
            "style"         => "",
            "animate"       => "",
            'number_type'   => "",
            "font_weight"   => ""
        );

        extract(shortcode_atts($args, $atts));

        $list_item_classes = "";

        if($style != "") {
            $list_item_classes .= "{$style}";
        }

        if($number_type != "") {
            $list_item_classes .= " {$number_type}";
        }

        if($font_weight != "") {
            $list_item_classes .= " {$font_weight}";
        }

        $html =  "<div class='q_list $list_item_classes";
        if($animate == "yes"){
            $html .= " animate_list'>" . $content . "</div>";
        } else {
            $html .= "'>" . $content . "</div>";
        }
        return $html;
    }
}
add_shortcode('unordered_list', 'unordered_list');

/* Service table shortcode */

if (!function_exists('service_table')) {
    function service_table($atts, $content = null) {
        global $qode_options_proya;
        $args = array(
            "title"                    	=> "",
            "title_tag"                	=> "h3",
            "title_color"              	=> "",
            "title_background_type"    	=> "",
            "title_background_color"   	=> "",
            "background_image"         	=> "",
            "background_image_height"  	=> "",
            "icon"                     	=> "",
            "icon_size"                	=> "",
            "custom_size"              	=> "",
			"icon_color"				=> "",
			"border"					=> "",
            "border_width"              => "",
            "border_color"              => "",
            "content_background_color" 	=> ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        //init variables
        $html = "";
        $title_holder_style = "";
        $title_style = "";
        $title_classes = "";
        $icon_style = "";
        $content_style = "";
        $service_table_holder_style = "";
        $service_table_style = "";
        $background_image_src = "";

        if($title_background_type == "background_color_type"){
            if($title_background_color != ""){
                $title_holder_style .= "background-color: ".$title_background_color.";";
            }

        } else {
            if(is_numeric($background_image)) {
                $background_image_src = wp_get_attachment_url( $background_image );
            } else {
                $background_image_src = $background_image;
            }

            if(!empty($qode_options_proya['first_color'])){
                $service_table_style = $qode_options_proya['first_color'];
            } else {
                $service_table_style = "#00c6ff";
            }

            if($background_image != ""){
                $title_holder_style .= "background-image: url(".$background_image_src.");";
            }

            if($background_image_height != ""){
                $title_holder_style .= "height: ".$background_image_height."px;";
            }
        }
		if($border == "yes"){
			$service_table_holder_style .= " style='border-style:solid;";
			if($border_width != ""){
				$service_table_holder_style .= "border-width:". $border_width . "px;";
			}
			if($border_color != ""){
				$service_table_holder_style .= "border-color:". $border_color . ";";
			}
			$service_table_holder_style .="'";
		}
        if($title_color != ""){
            $title_style .= "color: ".$title_color.";";
        }

        $title_classes .= $title_background_type;

        if($custom_size != ""){
            $icon_style .= "font-size: ".$custom_size."px;";
        }

        if($icon_color != ""){
            $icon_style .= "color: ".$icon_color. " !important;"; //important because of the first color importance
        }

        if($content_background_color != ""){
            $content_style .= "background-color: ".$content_background_color.";";
        }

        $html .= "<div class='service_table_holder'". $service_table_holder_style ."><ul class='service_table_inner'>";

        $html .= "<li class='service_table_title_holder ".$title_classes."' style='".$title_holder_style."'>";

        $html .= "<div class='service_table_title_inner'><div class='service_table_title_inner2'>";

        if($title != ""){
            $html .= "<".$title_tag." class='service_title' style='".$title_style."'>".$title."</".$title_tag.">";
        }

        if($icon != ""){
            $html .= "<i class='fa ".$icon." ".$icon_size."' style='".$icon_style."'></i>";
        }

        $html .= "</div></div>";

        $html .= "</li>";

        $html .= "<li class='service_table_content' style='".$content_style."'>";

        $html .= do_shortcode($content);

        $html .= "</li>";

        $html .= "</ul></div>";

        return $html;
    }
}
add_shortcode('service_table', 'service_table');

/* Qode Slider shortcode */

if (!function_exists('qode_slider')) {
    function qode_slider( $atts, $content = null ) {
        global $qode_options_proya;
        extract(shortcode_atts(array("slider"=>"", "height"=>"", "responsive_height"=>"", "auto_start"=>"", "animation_type"=>"", "slide_animation"=>"6000", "show_navigation_arrows"=>"", "anchor" => ""), $atts));
        $html = "";

        if ($slider != "") {
            $args = array(
                'post_type'=> 'slides',
                'slides_category' => $slider,
                'orderby' => "menu_order",
                'order' => "ASC",
                'posts_per_page' => -1
            );

            $slider_id = get_term_by('slug',$slider,'slides_category')->term_id;
            $slider_meta = get_option( "taxonomy_term_".$slider_id );
            $slider_header_effect =  $slider_meta['header_effect'];
            if($slider_header_effect == 'yes'){
                $header_effect_class = 'header_effect';
            }else{
                $header_effect_class = '';
            }

            $slider_css_position_class = '';
			
			if(isset($qode_options_proya['header_bottom_appearance']) && $qode_options_proya['header_bottom_appearance'] == "fixed_top_header"){
				$slider_parallax = 'no';
			}else{
				$slider_parallax = 'yes';
				if(isset($slider_meta['slider_parallax_effect'])){
					$slider_parallax = $slider_meta['slider_parallax_effect'];
				}
			}
			


            if ($slider_parallax == 'no' || (isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes' && ((isset($qode_options_proya['paspartu_on_top']) && $qode_options_proya['paspartu_on_top'] == 'yes') || (isset($qode_options_proya['paspartu_on_bottom_slider']) && $qode_options_proya['paspartu_on_bottom_slider'] == 'yes')))) {
                $data_parallax_effect = 'data-parallax="no"';
                $slider_css_position_class = 'relative_position';
            } else {
                $data_parallax_effect = 'data-parallax="yes"';
            }

            $slider_thumbs =  'no';
            if($slider_thumbs == 'yes'){
                $slider_thumbs_class = 'slider_thumbs';
            }else{
                $slider_thumbs_class = '';
            }
			
			$slider_advanced_responsiveness_class = '';
			$responsive_coefficients_graphic_data = '';
			$responsive_coefficients_title_data = '';
			$responsive_coefficients_subtitle_data = '';
			$responsive_coefficients_text_data = '';
			$responsive_coefficients_button_data = '';
			if(isset($slider_meta['slider_advanced_responsiveness']) && $slider_meta['slider_advanced_responsiveness'] == 'yes'){
				$slider_advanced_responsiveness_class .= 'advanced_responsiveness';

				if (isset($slider_meta['breakpoint1_graphic']) && $slider_meta['breakpoint1_graphic'] != '') {
					$breakpoint1_graphic = esc_attr($slider_meta['breakpoint1_graphic']);
				} else {
					$breakpoint1_graphic = 1;
				}
				if (isset($slider_meta['breakpoint2_graphic']) && $slider_meta['breakpoint2_graphic'] != '') {
					$breakpoint2_graphic = esc_attr($slider_meta['breakpoint2_graphic']);
				} else {
					$breakpoint2_graphic = 1;
				}
				if (isset($slider_meta['breakpoint3_graphic']) && $slider_meta['breakpoint3_graphic'] != '') {
					$breakpoint3_graphic = esc_attr($slider_meta['breakpoint3_graphic']);
				} else {
					$breakpoint3_graphic = 0.8;
				}
				if (isset($slider_meta['breakpoint4_graphic']) && $slider_meta['breakpoint4_graphic'] != '') {
					$breakpoint4_graphic = esc_attr($slider_meta['breakpoint4_graphic']);
				} else {
					$breakpoint4_graphic = 0.7;
				}
				if (isset($slider_meta['breakpoint5_graphic']) && $slider_meta['breakpoint5_graphic'] != '') {
					$breakpoint5_graphic = esc_attr($slider_meta['breakpoint5_graphic']);
				} else {
					$breakpoint5_graphic = 0.6;
				}
				if (isset($slider_meta['breakpoint6_graphic']) && $slider_meta['breakpoint6_graphic'] != '') {
					$breakpoint6_graphic = esc_attr($slider_meta['breakpoint6_graphic']);
				} else {
					$breakpoint6_graphic = 0.5;
				}
				if (isset($slider_meta['breakpoint7_graphic']) && $slider_meta['breakpoint7_graphic'] != '') {
					$breakpoint7_graphic = esc_attr($slider_meta['breakpoint7_graphic']);
				} else {
					$breakpoint7_graphic = 0.4;
				}

				if (isset($slider_meta['breakpoint1_title']) && $slider_meta['breakpoint1_title'] != '') {
					$breakpoint1_title = esc_attr($slider_meta['breakpoint1_title']);
				} else {
					$breakpoint1_title = 1;
				}
				if (isset($slider_meta['breakpoint2_title']) && $slider_meta['breakpoint2_title'] != '') {
					$breakpoint2_title = esc_attr($slider_meta['breakpoint2_title']);
				} else {
					$breakpoint2_title = 1;
				}
				if (isset($slider_meta['breakpoint3_title']) && $slider_meta['breakpoint3_title'] != '') {
					$breakpoint3_title = esc_attr($slider_meta['breakpoint3_title']);
				} else {
					$breakpoint3_title = 0.8;
				}
				if (isset($slider_meta['breakpoint4_title']) && $slider_meta['breakpoint4_title'] != '') {
					$breakpoint4_title = esc_attr($slider_meta['breakpoint4_title']);
				} else {
					$breakpoint4_title = 0.7;
				}
				if (isset($slider_meta['breakpoint5_title']) && $slider_meta['breakpoint5_title'] != '') {
					$breakpoint5_title = esc_attr($slider_meta['breakpoint5_title']);
				} else {
					$breakpoint5_title = 0.6;
				}
				if (isset($slider_meta['breakpoint6_title']) && $slider_meta['breakpoint6_title'] != '') {
					$breakpoint6_title = esc_attr($slider_meta['breakpoint6_title']);
				} else {
					$breakpoint6_title = 0.5;
				}
				if (isset($slider_meta['breakpoint7_title']) && $slider_meta['breakpoint7_title'] != '') {
					$breakpoint7_title = esc_attr($slider_meta['breakpoint7_title']);
				} else {
					$breakpoint7_title = 0.4;
				}

				if (isset($slider_meta['breakpoint1_subtitle']) && $slider_meta['breakpoint1_subtitle'] != '') {
					$breakpoint1_subtitle = esc_attr($slider_meta['breakpoint1_subtitle']);
				} else {
					$breakpoint1_subtitle = 1;
				}
				if (isset($slider_meta['breakpoint2_subtitle']) && $slider_meta['breakpoint2_subtitle'] != '') {
					$breakpoint2_subtitle = esc_attr($slider_meta['breakpoint2_subtitle']);
				} else {
					$breakpoint2_subtitle = 1;
				}
				if (isset($slider_meta['breakpoint3_subtitle']) && $slider_meta['breakpoint3_subtitle'] != '') {
					$breakpoint3_subtitle = esc_attr($slider_meta['breakpoint3_subtitle']);
				} else {
					$breakpoint3_subtitle = 0.8;
				}
				if (isset($slider_meta['breakpoint4_subtitle']) && $slider_meta['breakpoint4_subtitle'] != '') {
					$breakpoint4_subtitle = esc_attr($slider_meta['breakpoint4_subtitle']);
				} else {
					$breakpoint4_subtitle = 0.7;
				}
				if (isset($slider_meta['breakpoint5_subtitle']) && $slider_meta['breakpoint5_subtitle'] != '') {
					$breakpoint5_subtitle = esc_attr($slider_meta['breakpoint5_subtitle']);
				} else {
					$breakpoint5_subtitle = 0.6;
				}
				if (isset($slider_meta['breakpoint6_subtitle']) && $slider_meta['breakpoint6_subtitle'] != '') {
					$breakpoint6_subtitle = esc_attr($slider_meta['breakpoint6_subtitle']);
				} else {
					$breakpoint6_subtitle = 0.5;
				}
				if (isset($slider_meta['breakpoint7_subtitle']) && $slider_meta['breakpoint7_subtitle'] != '') {
					$breakpoint7_subtitle = esc_attr($slider_meta['breakpoint7_subtitle']);
				} else {
					$breakpoint7_subtitle = 0.4;
				}

				if (isset($slider_meta['breakpoint1_text']) && $slider_meta['breakpoint1_text'] != '') {
					$breakpoint1_text = esc_attr($slider_meta['breakpoint1_text']);
				} else {
					$breakpoint1_text = 1;
				}
				if (isset($slider_meta['breakpoint2_text']) && $slider_meta['breakpoint2_text'] != '') {
					$breakpoint2_text = esc_attr($slider_meta['breakpoint2_text']);
				} else {
					$breakpoint2_text = 1;
				}
				if (isset($slider_meta['breakpoint3_text']) && $slider_meta['breakpoint3_text'] != '') {
					$breakpoint3_text = esc_attr($slider_meta['breakpoint3_text']);
				} else {
					$breakpoint3_text = 0.8;
				}
				if (isset($slider_meta['breakpoint4_text']) && $slider_meta['breakpoint4_text'] != '') {
					$breakpoint4_text = esc_attr($slider_meta['breakpoint4_text']);
				} else {
					$breakpoint4_text = 0.7;
				}
				if (isset($slider_meta['breakpoint5_text']) && $slider_meta['breakpoint5_text'] != '') {
					$breakpoint5_text = esc_attr($slider_meta['breakpoint5_text']);
				} else {
					$breakpoint5_text = 0.6;
				}
				if (isset($slider_meta['breakpoint6_text']) && $slider_meta['breakpoint6_text'] != '') {
					$breakpoint6_text = esc_attr($slider_meta['breakpoint6_text']);
				} else {
					$breakpoint6_text = 0.5;
				}
				if (isset($slider_meta['breakpoint7_text']) && $slider_meta['breakpoint7_text'] != '') {
					$breakpoint7_text = esc_attr($slider_meta['breakpoint7_text']);
				} else {
					$breakpoint7_text = 0.4;
				}

				if (isset($slider_meta['breakpoint1_button']) && $slider_meta['breakpoint1_button'] != '') {
					$breakpoint1_button = esc_attr($slider_meta['breakpoint1_button']);
				} else {
					$breakpoint1_button = 1;
				}
				if (isset($slider_meta['breakpoint2_button']) && $slider_meta['breakpoint2_button'] != '') {
					$breakpoint2_button = esc_attr($slider_meta['breakpoint2_button']);
				} else {
					$breakpoint2_button = 1;
				}
				if (isset($slider_meta['breakpoint3_button']) && $slider_meta['breakpoint3_button'] != '') {
					$breakpoint3_button = esc_attr($slider_meta['breakpoint3_button']);
				} else {
					$breakpoint3_button = 0.8;
				}
				if (isset($slider_meta['breakpoint4_button']) && $slider_meta['breakpoint4_button'] != '') {
					$breakpoint4_button = esc_attr($slider_meta['breakpoint4_button']);
				} else {
					$breakpoint4_button = 0.7;
				}
				if (isset($slider_meta['breakpoint5_button']) && $slider_meta['breakpoint5_button'] != '') {
					$breakpoint5_button = esc_attr($slider_meta['breakpoint5_button']);
				} else {
					$breakpoint5_button = 0.6;
				}
				if (isset($slider_meta['breakpoint6_button']) && $slider_meta['breakpoint6_button'] != '') {
					$breakpoint6_button = esc_attr($slider_meta['breakpoint6_button']);
				} else {
					$breakpoint6_button = 0.5;
				}
				if (isset($slider_meta['breakpoint7_button']) && $slider_meta['breakpoint7_button'] != '') {
					$breakpoint7_button = esc_attr($slider_meta['breakpoint7_button']);
				} else {
					$breakpoint7_button = 0.4;
				}

				$responsive_coefficients_graphic_data = 'data-q_responsive_graphic_coefficients = "' . esc_attr($breakpoint1_graphic . ',' . $breakpoint2_graphic . ',' . $breakpoint3_graphic . ',' . $breakpoint4_graphic . ',' . $breakpoint5_graphic . ',' . $breakpoint6_graphic . ',' . $breakpoint7_graphic) . '"';
				$responsive_coefficients_title_data = 'data-q_responsive_title_coefficients = "' . esc_attr($breakpoint1_title . ',' . $breakpoint2_title . ',' . $breakpoint3_title . ',' . $breakpoint4_title . ',' . $breakpoint5_title . ',' . $breakpoint6_title . ',' . $breakpoint7_title) . '"';
				$responsive_coefficients_subtitle_data = 'data-q_responsive_subtitle_coefficients = "' . esc_attr($breakpoint1_subtitle . ',' . $breakpoint2_subtitle . ',' . $breakpoint3_subtitle . ',' . $breakpoint4_subtitle . ',' . $breakpoint5_subtitle . ',' . $breakpoint6_subtitle . ',' . $breakpoint7_subtitle) . '"';
				$responsive_coefficients_text_data = 'data-q_responsive_text_coefficients = "' . esc_attr($breakpoint1_text . ',' . $breakpoint2_text . ',' . $breakpoint3_text . ',' . $breakpoint4_text . ',' . $breakpoint5_text . ',' . $breakpoint6_text . ',' . $breakpoint7_text) . '"';
				$responsive_coefficients_button_data = 'data-q_responsive_button_coefficients = "' . esc_attr($breakpoint1_button . ',' . $breakpoint2_button . ',' . $breakpoint3_button . ',' . $breakpoint4_button . ',' . $breakpoint5_button . ',' . $breakpoint6_button . ',' . $breakpoint7_button) . '"';

			}
			
            if($height == "" || $height == "0"){
                $full_screen_class = "full_screen";
                $responsive_height_class = "";
                $slide_height = "";
                $data_height = "";
            }else{
                $full_screen_class = "";
                if($responsive_height == "yes"){
                    $responsive_height_class = "responsive_height";
                }else{
                    $responsive_height_class = "";
                }
                $slide_height = "height: ".$height."px;";
                $data_height = "data-height='".$height."'";
            }

            $anchor_data = '';
            if($anchor != "") {
                $anchor_data .= 'data-q_id = "#'.$anchor.'"';
            }

            $slider_transparency_class = "header_not_transparent";
            if(isset($qode_options_proya['header_background_transparency_initial']) && $qode_options_proya['header_background_transparency_initial'] != "1" && $qode_options_proya['header_background_transparency_initial'] != ""){
                $slider_transparency_class = "";
            }

            $auto = "true";
            if($auto_start != ""){
                $auto = $auto_start;
            }

            if($auto == "true"){
                $auto_start_class = "q_auto_start";
            } else {
                $auto_start_class = "";
            }

            if($slide_animation != ""){
                $slide_animation = 'data-slide_animation="'.$slide_animation.'"';
            } else {
                $slide_animation = 'data-slide_animation=""';
            }

            if($animation_type == 'fade'){
                $animation_type_class = 'fade';
            }else{
                $animation_type_class = '';
            }

            /**************** Count positioning of navigation arrows and preloader depending on header transparency and layout - START ****************/

            global $wp_query;

            $page_id = $wp_query->get_queried_object_id();
            $header_height_padding = 0;
			
			$paspartu = false;
			if(isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes'){
				$paspartu = true;
			}
			
			$paspartu_on_top = false;
			if(isset($qode_options_proya['paspartu_on_top']) && $qode_options_proya['paspartu_on_top'] == 'yes'){
				$paspartu_on_top = true;
			}
			
			$paspartu_on_bottom_slider = false;
			if(isset($qode_options_proya['paspartu_on_bottom_slider']) && $qode_options_proya['paspartu_on_bottom_slider'] == 'yes'){
				$paspartu_on_bottom_slider = true;
			}
			
            if((get_post_meta($page_id, "qode_header_color_transparency_per_page", true) == "" || get_post_meta($page_id, "qode_header_color_transparency_per_page", true) == "1") && ($qode_options_proya['header_background_transparency_initial'] == "" || $qode_options_proya['header_background_transparency_initial'] == "1") && ($paspartu == false || $paspartu_on_top == false)){
                if (!empty($qode_options_proya['header_height'])) {
                    $header_height = $qode_options_proya['header_height'];
                } else {
                    $header_height = 100;
                }
                if($qode_options_proya['header_bottom_appearance'] == 'stick menu_bottom'){
                    $menu_bottom = '46';
                    if(is_active_sidebar('header_fixed_right')){
                        $menu_bottom = $menu_bottom + 22;
                    }
                } else {
                    $menu_bottom = 0;
                }

                $header_top = 0;
                if(isset($qode_options_proya['header_top_area']) && $qode_options_proya['header_top_area'] == "yes"){
                    $header_top = 34;
                }
                $header_height_padding = $header_height + $menu_bottom + $header_top;
                if (isset($qode_options_proya['center_logo_image']) && $qode_options_proya['center_logo_image'] == "yes") {
                    if(isset($qode_options_proya['logo_image'])){
                        $logo_width = 0;
                        $logo_height = 0;
                        if (!empty($qode_options_proya['logo_image'])) {
                            $logo_url_obj = parse_url($qode_options_proya['logo_image']);
                            list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$logo_url_obj['path']);
                        }
                    }
                    $header_height_padding = $logo_height + 30 + $menu_bottom + $header_top; // 30 is top and bottom margin of centered logo
                }
            }
            if($header_height_padding != 0){
                $navigation_margin_top = 'style="margin-top:'. ($header_height_padding/2 - 30).'px;"'; // 30 is top and bottom margin of centered logo
                $loader_margin_top = 'style="margin-top:'. ($header_height_padding/2).'px;"';
            }
            else {
                $navigation_margin_top = '';
                $loader_margin_top = '';
            }

            /**************** Count positioning of navigation arrows and preloader depending on header transparency and layout - END ****************/

			/**************** Custom cursor - START ****************/
			$custom_cursor = "";
			if(isset($qode_options_proya['qs_enable_navigation_custom_cursor']) && ($qode_options_proya['qs_enable_navigation_custom_cursor']=="yes")){
				$custom_cursor = "has_custom_cursor";
			}
			/**************** Custom cursor - END ****************/

            if(($paspartu == true && ($paspartu_on_top == true || $paspartu_on_bottom_slider == true)) || $slider_parallax == "no"){
                $data_parallax_transform = '';
            }else{
                $data_parallax_transform = 'data-start="transform: translateY(0px);" data-1440="transform: translateY(-500px);"';
            }

			$html .= '<div id="qode-'.$slider.'" '.$anchor_data.' ' . $responsive_coefficients_graphic_data . ' ' . $responsive_coefficients_title_data . ' ' . $responsive_coefficients_subtitle_data . ' ' . $responsive_coefficients_text_data . ' ' . $responsive_coefficients_button_data . ' class="carousel slide '.$animation_type_class.' '.$full_screen_class.' '.$responsive_height_class.' '.$auto_start_class.' '.$header_effect_class.' '.$slider_thumbs_class.' '.$slider_transparency_class.' ' . $custom_cursor . ' '.$slider_advanced_responsiveness_class.'" '.$slide_animation.' '.$data_height.' '.$data_parallax_effect.' style="'.$slide_height.'"><div class="qode_slider_preloader"><div class="ajax_loader" '.$loader_margin_top.'><div class="ajax_loader_1">'.qode_loading_spinners(true).'</div></div></div>';
            $html .= '<div class="carousel-inner '.$slider_css_position_class.'" '.$data_parallax_transform.'>';
            query_posts( $args );


            $found_slides =  $wp_query->post_count;

            if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();
                $active_class = '';
                if($postCount == 0){
                    $active_class = 'active';
                }else{
                    $active_class = 'inactive';
                }

                $slide_type = get_post_meta(get_the_ID(), "qode_slide-background-type", true);

                $image = get_post_meta(get_the_ID(), "qode_slide-image", true);
				$image_overlay_pattern = get_post_meta(get_the_ID(), "qode_slide-overlay-image", true);
                $thumbnail = get_post_meta(get_the_ID(), "qode_slide-thumbnail", true);
                $thumbnail_attributes = qode_get_attachment_meta_from_url($thumbnail, array('width','height'));
                $thumbnail_attributes_width = '';
                $thumbnail_attributes_height = '';
                if($thumbnail_attributes == true){
                    $thumbnail_attributes_width = $thumbnail_attributes['width'];
                    $thumbnail_attributes_height = $thumbnail_attributes['height'];
                }
                $thumbnail_animation = get_post_meta(get_the_ID(), "qode_slide-thumbnail-animation", true);

                $thumbnail_link = "";
                if(get_post_meta(get_the_ID(), "qode_slide-thumbnail-link", true) != ""){
                    $thumbnail_link = get_post_meta(get_the_ID(), "qode_slide-thumbnail-link", true);
                }
				$svg_link = "";
				if (get_post_meta(get_the_ID(), "qode_slide_svg_link", true) != "") {
					$svg_link = esc_url(get_post_meta(get_the_ID(), "qode_slide_svg_link", true));
				}

                $video_webm = get_post_meta(get_the_ID(), "qode_slide-video-webm", true);
                $video_mp4 = get_post_meta(get_the_ID(), "qode_slide-video-mp4", true);
                $video_ogv = get_post_meta(get_the_ID(), "qode_slide-video-ogv", true);
                $video_image = get_post_meta(get_the_ID(), "qode_slide-video-image", true);
                $video_overlay = get_post_meta(get_the_ID(), "qode_slide-video-overlay", true);
                $video_overlay_image = get_post_meta(get_the_ID(), "qode_slide-video-overlay-image", true);

                $content_animation = '';
                $content_animation .= get_post_meta(get_the_ID(), "qode_slide-content-animation", true);
                if(get_post_meta(get_the_ID(), 'qode_slide-subtitle', true) != '') {
                    if(get_post_meta(get_the_ID(), 'qode_slide-subtitle-position', true) == "bellow_title"){
                        $content_animation .= ' subtitle_bellow_title';
                    }else{
                        $content_animation .= ' subtitle_above_title';
                    }
                }else{
                    $content_animation .= ' no_subtitle';
                }
                if(get_post_meta(get_the_ID(), "qode_slide-separator-after-title", true) == 'yes') {
                    $content_animation .= ' has_separator';
                }else{
                    $content_animation .= ' no_separator';
                }

                $title_color = "";
                if(get_post_meta(get_the_ID(), "qode_slide-title-color", true) != ""){
                    $title_color .= "color: ". get_post_meta(get_the_ID(), "qode_slide-title-color", true) . ";";
                }
                $title_font_size = "";
				$title_classes   = 'q_slide_title ';
                if(get_post_meta(get_the_ID(), "qode_slide-title-font-size", true) != ""){
                    $title_font_size .= "font-size: ". get_post_meta(get_the_ID(), "qode_slide-title-font-size", true) . "px;";

					if((int)get_post_meta(get_the_ID(), "qode_slide-title-font-size", true) > 150) {
						$title_classes .= 'large';
					}
                }

                $title_line_height = "";
                if(get_post_meta(get_the_ID(), "qode_slide-title-line-height", true) != ""){
                    $title_line_height .= "line-height: ". get_post_meta(get_the_ID(), "qode_slide-title-line-height", true) . "px;";
                }
                $title_font_family = "";
                if((get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) !== "-1") && (get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) !== "")){
                    $title_font_family .= "font-family: '". str_replace('+', ' ', get_post_meta(get_the_ID(), "qode_slide-title-font-family", true)) . "';";
                }
                $title_font_style = "";
                if(get_post_meta(get_the_ID(), "qode_slide-title-font-style", true) != ""){
                    $title_font_style .= "font-style: ". get_post_meta(get_the_ID(), "qode_slide-title-font-style", true) . ";";
                }
                $title_font_weight = "";
                if(get_post_meta(get_the_ID(), "qode_slide-title-font-weight", true) != ""){
                    $title_font_weight .= "font-weight: ". get_post_meta(get_the_ID(), "qode_slide-title-font-weight", true) . ";";
                }

				$title_letter_spacing = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-letter-spacing", true) != ""){
					$title_letter_spacing .= "letter-spacing: ". get_post_meta(get_the_ID(), "qode_slide-title-letter-spacing", true) . "px;";
				}

				$title_text_transform = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-text-transform", true) != ""){
					$title_text_transform .= "text-transform: ". get_post_meta(get_the_ID(), "qode_slide-title-text-transform", true) . ";";
				}

                $button_style = "";
                $slide_text_styles_string   = '';
                $slide_text_styles 			= array();

                $slide_text_shadow  	    = get_post_meta(get_the_ID(), 'qode_slide-hide-shadow', true);
                $slide_text_color    	    = get_post_meta(get_the_ID(), 'qode_slide-text-color', true);
                $slide_text_font_size  	    = get_post_meta(get_the_ID(), 'qode_slide-text-font-size', true);
                $slide_text_line_height     = get_post_meta(get_the_ID(), 'qode_slide-text-line-height', true);
                $slide_text_font_family     = get_post_meta(get_the_ID(), 'qode_slide-text-font-family', true);
                $slide_text_font_style      = get_post_meta(get_the_ID(), 'qode_slide-text-font-style', true);
                $slide_text_font_weight     = get_post_meta(get_the_ID(), 'qode_slide-text-font-weight', true);
                $slide_text_text_transform  = get_post_meta(get_the_ID(), 'qode_slide-text-text-transform', true);
                $slide_text_padding_top     = get_post_meta(get_the_ID(), 'qode_slide_text_padding_top', true);
                $slide_text_padding_right   = get_post_meta(get_the_ID(), 'qode_slide_text_padding_right', true);
                $slide_text_padding_bottom  = get_post_meta(get_the_ID(), 'qode_slide_text_padding_bottom', true);
                $slide_text_padding_left    = get_post_meta(get_the_ID(), 'qode_slide_text_padding_left', true);

                if($slide_text_shadow == 'yes') {
                    $slide_text_styles[] = "text-shadow: none;";
                }

                if($slide_text_color !== '') {
                    $slide_text_styles[] = 'color: '.$slide_text_color;
                    $button_style = " style='border-color:". get_post_meta(get_the_ID(), "qode_slide-text-color", true) . ";color:". get_post_meta(get_the_ID(), "qode_slide-text-color", true) . ";'";
                }

                if($slide_text_font_size !== '') {
                    $slide_text_styles[] = 'font-size: '.$slide_text_font_size.'px';
                }

                if($slide_text_line_height !== '') {
                    $slide_text_styles[] = 'line-height: '.$slide_text_line_height.'px';
                }

                if(($slide_text_font_family !== '-1') && ($slide_text_font_family !== '')) {
                    $slide_text_styles[] = 'font-family: '. str_replace('+', ' ', $slide_text_font_family);
                }

                if($slide_text_font_style !== '') {
                    $slide_text_styles[] = 'font-style: '.$slide_text_font_style;
                }

                if($slide_text_font_weight !== '') {
                    $slide_text_styles[] = 'font-weight: '.$slide_text_font_weight;
                }

                if($slide_text_text_transform !== '') {
                    $slide_text_styles[] = 'text-transform: '.$slide_text_text_transform;
                }

                if($slide_text_padding_top !== '') {
                    $slide_text_styles[] = 'padding-top: '.$slide_text_padding_top.'px';
                }

                if($slide_text_padding_right !== '') {
                    $slide_text_styles[] = 'padding-right: '.$slide_text_padding_right.'px';
                }

                if($slide_text_padding_bottom !== '') {
                    $slide_text_styles[] = 'padding-bottom: '.$slide_text_padding_bottom.'px';
                }

                if($slide_text_padding_left !== '') {
                    $slide_text_styles[] = 'padding-left: '.$slide_text_padding_left.'px';
                }

                if(is_array($slide_text_styles) && count($slide_text_styles)) {
                    $slide_text_styles_string = 'style="'.implode(';', $slide_text_styles).'"';
                }

                $graphic_alignment = get_post_meta(get_the_ID(), "qode_slide-graphic-alignment", true);
                $content_alignment = get_post_meta(get_the_ID(), "qode_slide-content-alignment", true);

                $separate_text_graphic = get_post_meta(get_the_ID(), "qode_slide-separate-text-graphic", true);
				
				$animate_image_class = "";
				$animate_image_data = "";
				if (get_post_meta(get_the_ID(), "qode_enable_image_animation", true) == "yes") {
					$animate_image_class .= "animate_image ";
					$animate_image_class .= get_post_meta(get_the_ID(), "qode_enable_image_animation_type", true);
					$animate_image_data .= "data-animate_image='".get_post_meta(get_the_ID(), "qode_enable_image_animation_type", true)."'";
				}
				
                if(get_post_meta(get_the_ID(), "qode_slide-content-width", true) != ""){
                    $content_width = "width:".get_post_meta(get_the_ID(), "qode_slide-content-width", true)."%;";
                }else{
                    $content_width = "width:50%;";
                }
                if(get_post_meta(get_the_ID(), "qode_slide-content-left", true) != ""){
                    $content_xaxis= "left:".get_post_meta(get_the_ID(), "qode_slide-content-left", true)."%;";
                }else{
                    if(get_post_meta(get_the_ID(), "qode_slide-content-right", true) != ""){
                        $content_xaxis = "right:".get_post_meta(get_the_ID(), "qode_slide-content-right", true)."%;";
                    }else{
                        $content_xaxis = "left: 25%;";
                    }
                }
                if(get_post_meta(get_the_ID(), "qode_slide-content-top", true) != ""){
                    $content_yaxis_start = "top:".get_post_meta(get_the_ID(), "qode_slide-content-top", true)."%;";
                    $content_yaxis_end = "top:".(get_post_meta(get_the_ID(), "qode_slide-content-top", true)-10)."%;";
                }else{
                    if(get_post_meta(get_the_ID(), "qode_slide-content-bottom", true) != ""){
                        $content_yaxis_start = "bottom:".get_post_meta(get_the_ID(), "qode_slide-content-bottom", true)."%;";
                        $content_yaxis_end = "bottom:".(get_post_meta(get_the_ID(), "qode_slide-content-bottom", true)+10)."%;";
                    }else{
                        $content_yaxis_start = "top: 20%;";
                        $content_yaxis_end = "top: 10%;";
                    }
                }

                if(get_post_meta(get_the_ID(), "qode_slide-graphic-width", true) != ""){
                    $graphic_width = "width:".get_post_meta(get_the_ID(), "qode_slide-graphic-width", true)."%;";
                }else{
                    $graphic_width = "width:50%;";
                }
                if(get_post_meta(get_the_ID(), "qode_slide-graphic-left", true) != ""){
                    $graphic_xaxis= "left:".get_post_meta(get_the_ID(), "qode_slide-graphic-left", true)."%;";
                }else{
                    if(get_post_meta(get_the_ID(), "qode_slide-graphic-right", true) != ""){
                        $graphic_xaxis = "right:".get_post_meta(get_the_ID(), "qode_slide-graphic-right", true)."%;";
                    }else{
                        $graphic_xaxis = "left: 25%;";
                    }
                }
                if(get_post_meta(get_the_ID(), "qode_slide-graphic-top", true) != ""){
                    $graphic_yaxis_start = "top:".get_post_meta(get_the_ID(), "qode_slide-graphic-top", true)."%;";
                    $graphic_yaxis_end = "top:".(get_post_meta(get_the_ID(), "qode_slide-graphic-top", true)-10)."%;";
                }else{
                    if(get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true) != ""){
                        $graphic_yaxis_start = "bottom:".get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true)."%;";
                        $graphic_yaxis_end = "bottom:".(get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true)+10)."%;";
                    }else{
                        $graphic_yaxis_start = "top: 20%;";
                        $graphic_yaxis_end = "top: 10%;";
                    }
                }

				//SCROLL ANIMATIONS
				$slide_data_start = '';
				$slide_data_end = '';
				$slide_title_data = '';
				$slide_subtitle_data = '';
				$slide_graphics_data = '';
				$slide_text_data = '';
				$slide_button_1_data = '';
				$slide_button_2_data = '';
				$slide_separator_bottom_data = '';
				$slide_svg_data = '';

				$qode_slide_general_animation_var = 'yes';
				if (get_post_meta(get_the_ID(), "qode_slide_general_animation", true) === "no") {
					$qode_slide_general_animation_var = "no";
				}

				if ($qode_slide_general_animation_var === "yes") {

					//Default values for data start and data end animation
					$qode_slide_data_start = '0';
					$qode_slide_data_end = '300';
					$qode_slide_data_start_custom_style = ' opacity: 1;';
					$qode_slide_data_end_custom_style = ' opacity: 0;';


					if (get_post_meta(get_the_ID(), "qode_slide_data_start", true) != "") {
						$qode_slide_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_start_custom_style", true) != "") {
						$qode_slide_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_end", true) != "") {
						$qode_slide_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_end_custom_style", true) != "") {
						$qode_slide_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_end_custom_style", true));
					}

					$slide_data_start = ' data-' . $qode_slide_data_start . '="' . $qode_slide_data_start_custom_style . ' ' . $content_width . ' ' . $content_xaxis . ' ' . $content_yaxis_start . '"';
					$slide_data_end = ' data-' . $qode_slide_data_end . '="' . $qode_slide_data_end_custom_style . ' ' . $content_xaxis . ' ' . $content_yaxis_end . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_title_animation_scroll", true) == "yes") {

					//Title options
					$slide_title_data_start = '0';
					$slide_title_data_start_custom_style = ' opacity: 1;';
					$slide_title_data_end = '300';
					$slide_title_data_end_custom_style = ' opacity:0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_title_start", true) != "") {
						$slide_title_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_title_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_title_start_custom_style", true) != "") {
						$slide_title_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_title_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_title_end", true) != "") {
						$slide_title_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_title_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_title_end_custom_style", true) != "") {
						$slide_title_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_title_end_custom_style", true));
					}

					$slide_title_data = 'data-' . $slide_title_data_start . '="' . $slide_title_data_start_custom_style . '" data-' . $slide_title_data_end . '="' . $slide_title_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_subtitle_animation_scroll", true) == "yes") {

					//Subtitle options
					$slide_subtitle_data_start = '0';
					$slide_subtitle_data_start_custom_style = ' opacity: 1;';
					$slide_subtitle_data_end = '300';
					$slide_subtitle_data_end_custom_style = ' opacity:0;';


					if (get_post_meta(get_the_ID(), "qode_slide_data_subtitle_start", true) != "") {
						$slide_subtitle_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_subtitle_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_subtitle_start_custom_style", true) != "") {
						$slide_subtitle_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_subtitle_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_subtitle_end", true) != "") {
						$slide_subtitle_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_subtitle_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_subtitle_end_custom_style", true) != "") {
						$slide_subtitle_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_subtitle_end_custom_style", true));
					}

					$slide_subtitle_data = 'data-' . $slide_subtitle_data_start . '="' . $slide_subtitle_data_start_custom_style . '" data-' . $slide_subtitle_data_end . '="' . $slide_subtitle_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_graphic_animation_scroll", true) == "yes") {

					//Graphics options
					$slide_graphics_data_start = '0';
					$slide_graphics_data_start_custom_style = ' opacity: 1;';
					$slide_graphics_data_end = '300';
					$slide_graphics_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_graphics_start", true) != "") {
						$slide_graphics_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_graphics_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_graphics_start_custom_style", true) != "") {
						$slide_graphics_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_graphics_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_graphics_end", true) != "") {
						$slide_graphics_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_graphics_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_graphics_end_custom_style", true) != "") {
						$slide_graphics_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_graphics_end_custom_style", true));
					}

					$slide_graphics_data = 'data-' . $slide_graphics_data_start . '="' . $slide_graphics_data_start_custom_style . '" data-' . $slide_graphics_data_end . '="' . $slide_graphics_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_text_animation_scroll", true) == "yes") {

					//Text options
					$slide_text_data_start = '0';
					$slide_text_data_start_custom_style = ' opacity: 1;';
					$slide_text_data_end = '300';
					$slide_text_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_text_start", true) != "") {
						$slide_text_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_text_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_text_start_custom_style", true) != "") {
						$slide_text_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_text_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_text_end", true) != "") {
						$slide_text_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_text_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_text_end_custom_style", true) != "") {
						$slide_text_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_text_end_custom_style", true));
					}

					$slide_text_data = 'data-' . $slide_text_data_start . '="' . $slide_text_data_start_custom_style . '" data-' . $slide_text_data_end . '="' . $slide_text_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_button1_animation_scroll", true) == "yes") {

					//Button 1 options
					$slide_button_1_data_start = '0';
					$slide_button_1_data_start_custom_style = ' opacity: 1;';
					$slide_button_1_data_end = '300';
					$slide_button_1_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_button_1_start", true) != "") {
						$slide_button_1_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_1_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_1_start_custom_style", true) != "") {
						$slide_button_1_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_1_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_1_end", true) != "") {
						$slide_button_1_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_1_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_1_end_custom_style", true) != "") {
						$slide_button_1_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_1_end_custom_style", true));
					}

					$slide_button_1_data = 'data-' . $slide_button_1_data_start . '="' . $slide_button_1_data_start_custom_style . '" data-' . $slide_button_1_data_end . '="' . $slide_button_1_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_button2_animation_scroll", true) == "yes") {

					//Button 2 options
					$slide_button_2_data_start = '0';
					$slide_button_2_data_start_custom_style = ' opacity: 1;';
					$slide_button_2_data_end = '300';
					$slide_button_2_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_button_2_start", true) != "") {
						$slide_button_2_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_2_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_2_start_custom_style", true) != "") {
						$slide_button_2_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_2_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_2_end", true) != "") {
						$slide_button_2_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_2_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_button_2_end_custom_style", true) != "") {
						$slide_button_2_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_button_2_end_custom_style", true));
					}

					$slide_button_2_data = 'data-' . $slide_button_2_data_start . '="' . $slide_button_2_data_start_custom_style . '" data-' . $slide_button_2_data_end . '="' . $slide_button_2_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_separator_bottom_animation_scroll", true) == "yes") {

					//Separator bottom options
					$slide_separator_bottom_data_start = '0';
					$slide_separator_bottom_data_start_custom_style = ' opacity: 1;';
					$slide_separator_bottom_data_end = '300';
					$slide_separator_bottom_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_start", true) != "") {
						$slide_separator_bottom_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_start_custom_style", true) != "") {
						$slide_separator_bottom_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_end", true) != "") {
						$slide_separator_bottom_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_end_custom_style", true) != "") {
						$slide_separator_bottom_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_separator_bottom_end_custom_style", true));
					}

					$slide_separator_bottom_data = 'data-' . $slide_separator_bottom_data_start . '="' . $slide_separator_bottom_data_start_custom_style . '" data-' . $slide_separator_bottom_data_end . '="' . $slide_separator_bottom_data_end_custom_style . '"';

				}

				if (get_post_meta(get_the_ID(), "qode_slide_svg_animation_scroll", true) == "yes") {

					//SVG options
					$slide_svg_data_start = '0';
					$slide_svg_data_start_custom_style = ' opacity: 1;';
					$slide_svg_data_end = '300';
					$slide_svg_data_end_custom_style = ' opacity: 0;';

					if (get_post_meta(get_the_ID(), "qode_slide_data_svg_start", true) != "") {
						$slide_svg_data_start = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_svg_start", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_svg_start_custom_style", true) != "") {
						$slide_svg_data_start_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_svg_start_custom_style", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_svg_end", true) != "") {
						$slide_svg_data_end = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_svg_end", true));
					}
					if (get_post_meta(get_the_ID(), "qode_slide_data_svg_end_custom_style", true) != "") {
						$slide_svg_data_end_custom_style = esc_attr(get_post_meta(get_the_ID(), "qode_slide_data_svg_end_custom_style", true));
					}

					$slide_svg_data = 'data-' . $slide_svg_data_start . '="' . $slide_svg_data_start_custom_style . '" data-' . $slide_svg_data_end . '="' . $slide_svg_data_end_custom_style . '"';

				}

				//SVG
				$svg = '';
				$svg_frame_rate = '';
				if (get_post_meta(get_the_ID(), "qode_slide_svg_source", true) != "") {
					$svg = get_post_meta(get_the_ID(), "qode_slide_svg_source", true);
				}
				$svg_drawing = "no";
				if (get_post_meta(get_the_ID(), "qode_slide_svg_drawing", true) == "yes") {
					$svg_drawing = get_post_meta(get_the_ID(), "qode_slide_svg_drawing", true);

					$svg_frame_rate = '100';
					if (get_post_meta(get_the_ID(), "qode_slide_svg_frame_rate", true) !== "") {
						$svg_frame_rate = esc_attr(get_post_meta(get_the_ID(), "qode_slide_svg_frame_rate", true));
					}
				}

                $header_style = "";
                if(get_post_meta(get_the_ID(), "qode_slide-header-style", true) != ""){
                    $header_style = get_post_meta(get_the_ID(), "qode_slide-header-style", true);
                }

                $navigation_color = "";
                if(get_post_meta(get_the_ID(), "qode_slide-navigation-color", true) != ""){
                    $navigation_color = 'data-navigation-color="'.get_post_meta(get_the_ID(), "qode_slide-navigation-color", true).'"';
                }

                $title = get_the_title();

                $html .= '<div class="item '.$header_style.' '.$animate_image_class.'" '.$navigation_color.' '.$animate_image_data.' style="'.$slide_height.'">';
                if($slide_type == 'video'){

                    $html .= '<div class="video"><div class="mobile-video-image" style="background-image: url('.$video_image.')"></div><div class="video-overlay';
                    if($video_overlay == "yes"){
                        $html .= ' active';
                    }
                    $html .= '"';
                    if($video_overlay_image != ""){
                        $html .= ' style="background-image:url('.$video_overlay_image.');"';
                    }
                    $html .= '>';
                    if($video_overlay_image != ""){
                        $html .= '<img src="'.$video_overlay_image.'" alt="" />';
                    }else{
                        $html .= '<img src="'.get_template_directory_uri().'/css/img/pixel-video.png" alt="" />';
                    }
                    $html .= '</div><div class="video-wrap">
									
									<video class="video" width="1920" height="800" poster="'.$video_image.'" controls="controls" preload="auto" loop autoplay muted>';
                    if(!empty($video_webm)) { $html .= '<source type="video/webm" src="'.$video_webm.'">'; }
                    if(!empty($video_mp4)) { $html .= '<source type="video/mp4" src="'.$video_mp4.'">'; }
                    if(!empty($video_ogv)) { $html .= '<source type="video/ogg" src="'. $video_ogv.'">'; }
                    $html .='<object width="320" height="240" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/js/flashmediaelement.swf">
													<param name="movie" value="'.get_template_directory_uri().'/js/flashmediaelement.swf" />
													<param name="flashvars" value="controls=true&file='.$video_mp4.'" />
													<img src="'.$video_image.'" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" />
											</object>
									</video>		
							</div></div>';
                }else{
                    $html .= '<div class="image" style="background-image:url('.$image.');">';
                    if($slider_thumbs == 'no'){
                        $html .= '<img src="'.$image.'" alt="'.$title.'">';
                    }

					if($image_overlay_pattern !== ""){
						$html .= '<div class="image_pattern"';
						$html .= 'style="background-image:url('.$image_overlay_pattern.');"';
						$html .= '></div>';
					}

                    $html .= '</div>';
                }

                $html_thumb = "";
                if($thumbnail != ""){
					$html_thumb .= '<div '.$slide_graphics_data.'>';
                    $html_thumb .= '<div class="thumb '.$thumbnail_animation.'">';
                        if($thumbnail_link != ""){
                            $html_thumb .= '<a href="'.$thumbnail_link.'" target="_self">';
                        }

                            $html_thumb .= '<img data-width="'.esc_attr($thumbnail_attributes_width).'" data-height="'.esc_attr($thumbnail_attributes_height).'" src="'.$thumbnail.'" alt="'.$title.'">';

                        if($thumbnail_link != ""){
                            $html_thumb .= '</a>';
                        }
                    $html_thumb .= '</div></div>';
                }

				//SVG
				if ( $svg != "" ) {
					$html_thumb .= '<div '.$slide_svg_data.'>';
					$html_thumb .= '<div class="qode_slide-svg-holder" data-svg-drawing="'.$svg_drawing.'" data-svg-frames="'.$svg_frame_rate.'">';

					if ($svg_link != "") {
						$html_thumb .= '<a href="' . $svg_link . '" target="_self">';
					}

					$html_thumb .= $svg;

					if ($svg_link != "") {
						$html_thumb .= '</a>';
					}

					$html_thumb .= '</div></div>';
				}

                $text_shadow = "";
                if(get_post_meta(get_the_ID(), "qode_slide-hide-shadow", true) == true){
                    $text_shadow = "text-shadow: none;";
                }

                $html_text = "";
                $html_text .= '<div class="text '.$content_animation.'">';

				//generate slide subtitle section
				if(get_post_meta(get_the_ID(), 'qode_slide-subtitle', true) != '') {
					//init variables
					$slide_subtitle_classes			= array('q_slide_subtitle');
					$slide_subtitle_styles_string   = '';
					$slide_subtitle_styles 			= array();
					$slide_subtitle_color  			= get_post_meta(get_the_ID(), 'qode_slide-subtitle-color', true);
					$slide_subtitle_font_size  		= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-size', true);
					$slide_subtitle_line_height  	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-line-height', true);
					$slide_subtitle_font_family  	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-family', true);
					$slide_subtitle_font_style   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-style', true);
					$slide_subtitle_font_weight   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-weight', true);
					$slide_subtitle_text_transform  = get_post_meta(get_the_ID(), 'qode_slide-subtitle-text-transform', true);
					$slide_subtitle_letter_spacing 	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-letter-spacing', true);
					$slide_subtitle_position	   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-position', true);
					$slide_subtitle_bg_color		= get_post_meta(get_the_ID(), 'qode_slide-subtitle-background-color', true);
					$slide_subtitle_bg_transparency = get_post_meta(get_the_ID(), 'qode_slide-subtitle-bg-color-transparency', true);
                    $slide_subtitle_margin_bottom = get_post_meta(get_the_ID(), 'qode_slide_subtitle_margin_bottom', true);
                    $slide_subtitle_padding_top = get_post_meta(get_the_ID(), 'qode_slide_subtitle_padding_top', true);
                    $slide_subtitle_padding_right = get_post_meta(get_the_ID(), 'qode_slide_subtitle_padding_right', true);
                    $slide_subtitle_padding_bottom = get_post_meta(get_the_ID(), 'qode_slide_subtitle_padding_bottom', true);
                    $slide_subtitle_padding_left = get_post_meta(get_the_ID(), 'qode_slide_subtitle_padding_left', true);

					if($slide_subtitle_color !== '') {
						$slide_subtitle_styles[] = 'color: '.$slide_subtitle_color;
					}

					if($slide_subtitle_font_size !== '') {
						$slide_subtitle_styles[] = 'font-size: '.$slide_subtitle_font_size.'px';
					}

					if($slide_subtitle_line_height !== '') {
						$slide_subtitle_styles[] = 'line-height: '.$slide_subtitle_line_height.'px';
					}

					if(($slide_subtitle_font_family !== '-1') && ($slide_subtitle_font_family !== '')) {
						$slide_subtitle_styles[] = 'font-family: '. str_replace('+', ' ', $slide_subtitle_font_family);
					}

					if($slide_subtitle_font_style !== '') {
						$slide_subtitle_styles[] = 'font-style: '.$slide_subtitle_font_style;
					}

					if($slide_subtitle_font_weight !== '') {
						$slide_subtitle_styles[] = 'font-weight: '.$slide_subtitle_font_weight;
					}
					
					if($slide_subtitle_text_transform !== '') {
						$slide_subtitle_styles[] = 'text-transform: '.$slide_subtitle_text_transform;
					}

					if($slide_subtitle_letter_spacing !== '') {
						$slide_subtitle_styles[] = 'letter-spacing: '.$slide_subtitle_letter_spacing.'px';
					}

					if($text_shadow !== '') {
						$slide_subtitle_styles[] = $text_shadow;
					}

					if(count($slide_subtitle_styles)) {
						$slide_subtitle_styles_string = 'style="'.implode(';', $slide_subtitle_styles).'"';
					}

					$slide_subtitle_span_styles = array();
					$slide_subtitle_span_styles_string = '';
					if($slide_subtitle_bg_color) {
						$slide_subtitle_classes[] = 'with_background_color';
						$slide_subtitle_init_transparency = 1;
						if($slide_subtitle_bg_transparency !== '') {
							$slide_subtitle_init_transparency = $slide_subtitle_bg_transparency;
						}

						$slide_subtitle_span_styles[] = 'background-color: '.qode_rgba_color($slide_subtitle_bg_color, $slide_subtitle_init_transparency);
					}

                    if($slide_subtitle_margin_bottom !== '') {
                        $slide_subtitle_span_styles[] = 'margin-bottom: '.$slide_subtitle_margin_bottom.'px';
                    }

                    if($slide_subtitle_padding_top !== '') {
                        $slide_subtitle_span_styles[] = 'padding-top: '.$slide_subtitle_padding_top.'px';
                    }

                    if($slide_subtitle_padding_right !== '') {
                        $slide_subtitle_span_styles[] = 'padding-right: '.$slide_subtitle_padding_right.'px';
                    }

                    if($slide_subtitle_padding_bottom !== '') {
                        $slide_subtitle_span_styles[] = 'padding-bottom: '.$slide_subtitle_padding_bottom.'px';
                    }

                    if($slide_subtitle_padding_left !== '') {
                        $slide_subtitle_span_styles[] = 'padding-left: '.$slide_subtitle_padding_left.'px';
                    }

					if(is_array($slide_subtitle_span_styles) && count($slide_subtitle_span_styles)) {
						$slide_subtitle_span_styles_string = 'style="'.implode(';', $slide_subtitle_span_styles).'"';
					}

					if($slide_subtitle_position != "bellow_title") {
						$html_text .= '<div '.$slide_subtitle_data.'>';
						$html_text .= '<h4 class="'.implode(' ', $slide_subtitle_classes).'" '.$slide_subtitle_styles_string.'><span '.$slide_subtitle_span_styles_string.'>'.get_post_meta(get_the_ID(), 'qode_slide-subtitle', true).'</span></h4>';
						$html_text .= '</div>';
					}
				}

				if(get_post_meta(get_the_ID(), "qode_slide-hide-title", true) != true){

					$slide_title_border_styles_string  = '';
					//is border around title option checked?
					if(get_post_meta(get_the_ID(), "qode_slide-border-around-title", true) == 'yes') {
						//add class for aditional styling
						$title_classes .= ' with_title_border';

						//init variables
						$slide_title_border_styles 			= array();
						$slide_title_border_color  			= get_post_meta(get_the_ID(), 'qode_slide-border-around-title-color', true);
						$slide_title_border_transparency 	= get_post_meta(get_the_ID(), 'qode_slide-border-around-title-transparency', true);

						//is title border color not set and header style is set?
						if($slide_title_border_color === '' && $header_style !== '') {
							//set predefined border color based on header style
							switch($header_style) {
								case 'light':
									$slide_title_border_color = '#fff';
									break;
								case 'dark':
									$slide_title_border_color = '#000';
									break;
								default:
									break;
							}
						}

						//set border property
						$slide_title_border_styles[] = 'border: 3px solid';

						//is border color set?
						if($slide_title_border_color !== '') {
							//is border transparency set?
							if($slide_title_border_transparency !== '') {
								//set border color property with semi transparent color
								$slide_title_border_styles[] = 'border-color: '.qode_rgba_color($slide_title_border_color, $slide_title_border_transparency);
							} else {
								//set border color property
								$slide_title_border_styles[] = 'border-color: '.$slide_title_border_color;
							}

						}

						//implode styles in a string so it can be used in style attribute
						$slide_title_border_styles_string .= implode(';', $slide_title_border_styles).';';
					}

					$slide_title_bg_color	   		= get_post_meta(get_the_ID(), "qode_slide-title-background-color", true);
					$slide_title_bg_transparency 	= get_post_meta(get_the_ID(), "qode_slide-title-bg-color-transparency", true);

					$slide_title_background_styles = array();
					$slide_title_background_styles_string = '';
					if($slide_title_bg_color) {
						$title_classes .= ' with_background_color';
						$slide_title_init_transparency = 1;
						if($slide_title_bg_transparency !== '') {
							$slide_title_init_transparency = $slide_title_bg_transparency;
						}

						$slide_title_background_styles[] = 'background-color: '.qode_rgba_color($slide_title_bg_color, $slide_title_init_transparency);
					}

					if(is_array($slide_title_background_styles) && count($slide_title_background_styles)) {
						$slide_title_background_styles_string = implode(';', $slide_title_background_styles);
					}

					$html_text .= '<div '.$slide_title_data.'>';
                    $html_text .= '<h2 class="'.$title_classes.'" style="'.$title_color.$title_font_size.$title_line_height.$title_font_family.$title_font_style.$title_font_weight.$text_shadow.$title_letter_spacing.$title_text_transform.'"><span style="'.$slide_title_border_styles_string.$slide_title_background_styles_string.'">'.get_the_title().'</span></h2>';
					$html_text .= '</div>';
                }
				if(get_post_meta(get_the_ID(), 'qode_slide-subtitle', true) != '' && $slide_subtitle_position == "bellow_title") {
					$html_text .= '<div '.$slide_subtitle_data.'>';
					$html_text .= '<h4 class="q_slide_subtitle" '.$slide_subtitle_styles_string.'><span '.$slide_subtitle_span_styles_string.'>'.get_post_meta(get_the_ID(), 'qode_slide-subtitle', true).'</span></h4>';
					$html_text .= '</div>';
				}
				//is separator after title option selected for current slide?
				if(get_post_meta(get_the_ID(), "qode_slide-separator-after-title", true) == 'yes') {

					//init variables
					$slide_separator_styles 		= '';
					$slide_separator_color  		= get_post_meta(get_the_ID(), "qode_slide-separator-color", true);
					$slide_separator_transparency  	= get_post_meta(get_the_ID(), "qode_slide-separator-transparency", true);
					$slide_separator_width			= get_post_meta(get_the_ID(), "qode_slide-separator-width", true);

					//is separator color chosen?
					if($slide_separator_color !== '') {
						//is separator transparenct set?
						if($slide_separator_transparency !== '') {
							//get rgba color value
							$slide_separator_rgba_color = qode_rgba_color($slide_separator_color, $slide_separator_transparency);

							//set color style
							$slide_separator_styles .= 'background-color: '.$slide_separator_rgba_color.';';
						} else {
							//set color without transparency
							$slide_separator_styles .= 'background-color: '.$slide_separator_color.';';
						}
					}

					//is separator width set?
					if($slide_separator_width !== '') {
						//set separator width
						$slide_separator_styles .= 'width: '.$slide_separator_width.'%;';
					}

					//append separator html
					$html_text .= '<div style="'.$slide_separator_styles.'" class="separator small" ' . $slide_separator_bottom_data . '></div>';
				}

//				if(get_post_meta(get_the_ID(), "qode_slide-text-field-type", true) == "text" && get_post_meta(get_the_ID(), "qode_slide-text", true) != ""){
                $html_text .= '<p class="q_slide_text" '.$slide_text_styles_string.' '.$slide_text_data.'><span>'.get_post_meta(get_the_ID(), "qode_slide-text", true).'</span></p>';
//               }
//                if(get_post_meta(get_the_ID(), "qode_slide-text-field-type", true) == "content"){
//                    $slide_content = get_the_content();
//                    $filtered_content = apply_filters( 'the_content', $slide_content);
//                    $html_text .= do_shortcode($filtered_content);
//                }
                if(get_post_meta(get_the_ID(), "qode_slide-button-label", true) != "" && get_post_meta(get_the_ID(), "qode_slide-button-link", true) != ""){
                    $html_text .= '<a class="qbutton green" href="'.get_post_meta(get_the_ID(), "qode_slide-button-link", true).'" ' . $slide_button_1_data . '>'.get_post_meta(get_the_ID(), "qode_slide-button-label", true).'</a>';
                }
                if(get_post_meta(get_the_ID(), "qode_slide-button-label2", true) != "" && get_post_meta(get_the_ID(), "qode_slide-button-link2", true) != ""){
                    $html_text .= '<a class="qbutton white"' . $button_style . ' href="'.get_post_meta(get_the_ID(), "qode_slide-button-link2", true).'"  ' . $slide_button_2_data . '>'.get_post_meta(get_the_ID(), "qode_slide-button-label2", true).'</a>';
                }

                if(get_post_meta(get_the_ID(), "qode_slide-anchor-button", true) !== '') {
                    $slide_anchor_style = array();
                    if($slide_text_color !== '') {
                        $slide_anchor_style[] = $slide_text_color;
                    }

                    if($slide_anchor_style !== '') {
                        $slide_anchor_style = 'style="'. implode(';', $slide_anchor_style).'"';
                    }

                    $html_text .= '<div class="slide_anchor_holder"><a '.$slide_anchor_style.' class="slide_anchor_button anchor" href="'.get_post_meta(get_the_ID(), "qode_slide-anchor-button", true).'"><i class="fa fa-angle-down"></i></a></div>';
                }

                $html_text .= '</div>';
                $html .= '<div class="slider_content_outer">';

                if($separate_text_graphic != 'yes'){
					$html .= '<div class="slider_content ' . $content_alignment . '" style="' . $content_width . $content_xaxis . $content_yaxis_start . '" '.$slide_data_start.' '.$slide_data_end.'>';
//                    $html .= '<div class="slider_content '.$content_alignment.'" style="'.$content_width.$content_xaxis.$content_yaxis_start.'" data-start="'.$content_width.' opacity:1; '.$content_xaxis.' '.$content_yaxis_start.'" data-300="opacity: 0; '.$content_xaxis.' '.$content_yaxis_end.'">';
                    $html .= $html_thumb;
                    $html .= $html_text;
                    $html .= '</div>';
                }else{
                    $html .= '<div class="slider_content '.$graphic_alignment.'" style="'.$graphic_width.$graphic_xaxis.$graphic_yaxis_start.'">';
                    $html .= $html_thumb;
                    $html .= '</div>';
					$html .= '<div class="slider_content ' . $content_alignment . '" style="' . $content_width . $content_xaxis . $content_yaxis_start . '" '.$slide_data_start.' '.$slide_data_end.'>';
//                    $html .= '<div class="slider_content '.$content_alignment.'" style="'.$content_width.$content_xaxis.$content_yaxis_start.'" data-start="'.$content_width.' opacity:1; '.$content_xaxis.' '.$content_yaxis_start.'" data-300="opacity: 0; '.$content_xaxis.' '.$content_yaxis_end.'">';
                    $html .= $html_text;
                    $html .= '</div>';
                }

                $html .= '</div>';
                $html .= '</div>';

                $postCount++;
            endwhile;
            else:
                $html .= __('Sorry, no slides matched your criteria.','qode');
            endif;
            wp_reset_query();

            $html .= '</div>';
            if($found_slides > 1){
                $html .= '<ol class="carousel-indicators" data-start="opacity: 1;" data-300="opacity:0;">';
                query_posts( $args );
                if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();

                    $html .= '<li data-target="#qode-'.$slider.'" data-slide-to="'.$postCount.'"';
                    if($postCount == 0){
                        $html .= ' class="active"';
                    }
                    $html .= '></li>';

                    $postCount++;
                endwhile;
                else:
                    $html .= __('Sorry, no posts matched your criteria.','qode');
                endif;

                wp_reset_query();
                $html .= '</ol>';
				if($show_navigation_arrows != "no"){
					$html .= '<a class="left carousel-control" href="#qode-'.$slider.'" data-slide="prev" data-start="opacity: 0.35;" data-300="opacity:0;"><span class="prev_nav" '.$navigation_margin_top.'><i class="fa fa-angle-left"></i></span><span class="thumb_holder" '.$navigation_margin_top.'><span class="thumb_top clearfix"><span class="arrow_left"><i class="fa fa-angle-left"></i></span><span class="numbers"><span class="prev"></span> / '.$postCount.'</span></span><span class="img_outer"><span class="img"></span></span></span></a>';
					$html .= '<a class="right carousel-control" href="#qode-'.$slider.'" data-slide="next" data-start="opacity: 0.35;" data-300="opacity:0;"><span class="next_nav" '.$navigation_margin_top.'><i class="fa fa-angle-right"></i></span><span class="thumb_holder" '.$navigation_margin_top.'><span class="thumb_top clearfix"><span class="numbers"> <span class="next"></span> / '.$postCount.'</span><span class="arrow_right"><i class="fa fa-angle-right"></i></span></span><span class="img_outer"><span class="img"></span></span></span></a>';
				}
            }
            $html .= '</div>';
        }


        return $html;
    }
}
add_shortcode('qode_slider', 'qode_slider');

/* Qode Carousel shortcode */

if (!function_exists('qode_carousel')) {
    function qode_carousel( $atts, $content = null ) {
        $args = array(
            "carousel" => "",
            "orderby"  => "date",
            "order"    => "ASC",
            "show_in_two_rows" => ""
        );
        extract(shortcode_atts($args, $atts));

        $html = "";
		$carousel_holder_classes = "";
        if ($carousel != "") {

			if($show_in_two_rows == 'yes') {
				$carousel_holder_classes = ' two_rows';
			}

            $html .= "<div class='qode_carousels_holder clearfix" . $carousel_holder_classes  ."'><div class='qode_carousels'><ul class='slides'>";

            $q = array('post_type'=> 'carousels', 'carousels_category' => $carousel, 'orderby' => $orderby, 'order' => $order, 'posts_per_page' => '-1');

            query_posts($q);

            if ( have_posts() ) : $postCount = 1; while ( have_posts() ) : the_post();

                if(get_post_meta(get_the_ID(), "qode_carousel-image", true) != ""){
                    $image = get_post_meta(get_the_ID(), "qode_carousel-image", true);
                } else {
                    $image = "";
                }

                if(get_post_meta(get_the_ID(), "qode_carousel-hover-image", true) != ""){
                    $hover_image = get_post_meta(get_the_ID(), "qode_carousel-hover-image", true);
                    $has_hover_image = "has_hover_image";
                } else {
                    $hover_image = "";
                    $has_hover_image = "";
                }

                if(get_post_meta(get_the_ID(), "qode_carousel-item-link", true) != ""){
                    $link = get_post_meta(get_the_ID(), "qode_carousel-item-link", true);
                } else {
                    $link = "";
                }

                if(get_post_meta(get_the_ID(), "qode_carousel-item-target", true) != ""){
                    $target = get_post_meta(get_the_ID(), "qode_carousel-item-target", true);
                } else {
                    $target = "_self";
                }

                $title = get_the_title();

				//is current item not on even position in array and two rows option is chosen?
				if($postCount % 2 !== 0 && $show_in_two_rows == 'yes') {
					$html .= "<li class='item'>";
				} elseif($show_in_two_rows == '') {
					$html .= "<li class='item'>";
				}
				$html .= '<div class="carousel_item_holder">';
                if($link != ""){
                    $html .= "<a href='".$link."' target='".$target."'>";
                }

                $first_image = qode_get_attachment_id_from_url($image);

                if($image != ""){
                    $html .= "<span class='first_image_holder ".$has_hover_image."'>";

                    if(is_int($first_image)) {
                        $html .= wp_get_attachment_image($first_image, 'full');
                    } else {
                        $html .= '<img src="'.$image.'" alt="carousel image" />';
                    }


                    $html .= "</span>";
                }

                $second_image = qode_get_attachment_id_from_url($hover_image);

                if($hover_image != ""){
                    $html .= "<span class='second_image_holder ".$has_hover_image."'>";

                    if(is_int($second_image)) {
                        $html .= wp_get_attachment_image($second_image, 'full');
                    } else {
                        $html .= '<img src="'.$hover_image.'" alt="carousel image" />';
                    }


                    $html .= "</span>";
                }

                if($link != ""){
                    $html .= "</a>";
                }

				$html .= '</div>';

				//is current item on even position in array and two rows option is chosen?
				if($postCount % 2 == 0 && $show_in_two_rows == 'yes') {
					$html .= "</li>";
				} elseif($show_in_two_rows == '') {
					$html .= "</li>";
				}

                $postCount++;

            endwhile;

            else:
                $html .= __('Sorry, no posts matched your criteria.','qode');
            endif;

            wp_reset_query();

            $html .= "</ul>";
            $html .= "</div></div>";

        }

        return $html;
    }
}
add_shortcode('qode_carousel', 'qode_carousel');

/* Qode Image Slider with no space shortcode */

if (!function_exists('image_slider_no_space')) {
    function image_slider_no_space($atts, $content = null) {
        global $qode_options;
        $args = array(
            "images"    				=> "",
            "height"    				=> "",
            "on_click"  				=> "",
            "custom_links" 				=> "",
            "custom_links_target" 		=> "",
            "navigation_style"			=> "",
            "highlight_active_image" 	=> "",
            "highlight_inactive_color"	=> "",
            "highlight_inactive_opacity"=> ""
        );

        extract(shortcode_atts($args, $atts));

        //init variables
        $html = "";
        $image_gallery_holder_styles 	= '';
        $image_gallery_holder_classes 	= '';
        $image_gallery_item_styles   	= '';
        $custom_links_array			 	= array();
        $using_custom_links			 	= false;
        $highlight_inactive_color_style = "";
        $highlight_inactive_opacity_style = "";

        //is height for the slider set?
        if($height !== '') {
            $image_gallery_holder_styles .= 'height: '.$height.'px;';
            $image_gallery_item_styles .= 'height: '.$height.'px;';
        }

        //are we using custom links and is custom links field filled?
        if($on_click == 'use_custom_links' && $custom_links !== '') {
            //create custom links array
            $custom_links_array = explode(',', strip_tags($custom_links));
        }

        if($navigation_style !== '') {
            $image_gallery_holder_classes = $navigation_style;
        }

        if($highlight_active_image == 'yes') {
            $image_gallery_holder_classes .= ' highlight_active';
			if ($highlight_inactive_color !== '') {
				$highlight_inactive_color_style = 'style = "background-color: ' . $highlight_inactive_color . '"';
			}

			if ($highlight_inactive_opacity !== '') {
				$highlight_inactive_opacity_style = 'style = "opacity: ' . $highlight_inactive_opacity . '"';
			}
        }

        $html .= "<div class='qode_image_gallery_no_space ".$image_gallery_holder_classes."'><div class='qode_image_gallery_holder' style='".$image_gallery_holder_styles."'><ul " . $highlight_inactive_color_style . " >";



        if($images != '' ) {
            $images_gallery_array = explode(',',$images);
        }

        //are we using prettyphoto?
        if($on_click == 'prettyphoto') {
            //generate random rel attribute
            $pretty_photo_rel = 'prettyPhoto[rel-'.rand().']';
        }


        //are we using custom links and is target for those elements chosen?
        if($on_click == 'use_custom_links' && in_array($custom_links_target, array('_self', '_blank'))) {
            //generate target attribute
            $custom_links_target = 'target="'.$custom_links_target.'"';
        }

        if(isset($images_gallery_array) && count($images_gallery_array) != 0) {
            $i = 0;
            foreach($images_gallery_array as $gimg_id) {
                $current_item_custom_link = '';

                $gimage_src = wp_get_attachment_image_src($gimg_id,'full',true);
                $gimage_alt = get_post_meta($gimg_id, '_wp_attachment_image_alt', true);

                $image_src    = $gimage_src[0];
                $image_width  = $gimage_src[1];
                $image_height = $gimage_src[2];

                //is height set for the slider?
                if($height !== '') {
                    //get image proportion that will be used to calculate image width
                    $proportion = $height / $image_height;

                    //get proper image widht based on slider height and proportion
                    $image_width = ceil($image_width * $proportion);
                }

                $html .= '<li ' . $highlight_inactive_opacity_style . ' ><div style="'.$image_gallery_item_styles.' width:'.$image_width.'px;">';

                //is on click event chosen?
                if($on_click !== '') {
                    switch($on_click) {
                        case 'prettyphoto':
                            $html .= '<a class="prettyphoto" rel="'.$pretty_photo_rel.'" href="'.$image_src.'">';
                            break;
                        case 'use_custom_links':
                            //does current image has custom link set?
                            if(isset($custom_links_array[$i])) {
                                //get custom link for current image
                                $current_item_custom_link = $custom_links_array[$i];

                                if($current_item_custom_link !== '') {
                                    $html .= '<a '.$custom_links_target.' href="'.$current_item_custom_link.'">';
                                }
                            }
                            break;
                        case 'new_tab':
                            $html .= '<a href="'.$image_src.'" target="_blank">';
                            break;
                        default:
                            break;
                    }
                }

                $html .= '<img src="'.$image_src.'" alt="'.$gimage_alt.'" />';

                //are we using prettyphoto or new tab click event or is custom link for current image set?
                if(in_array($on_click, array('prettyphoto', 'new_tab')) || ($on_click == 'use_custom_links' && $current_item_custom_link !== '')) {
                    //if so close opened link
                    $html .= '</a>';
                }

                $html .= '</div></li>';

                $i++;
            }
        }

        $html .= "</ul>";
        $html .= '</div>';
        $html .= '<div class="controls">';
        $html .= '<a class="prev-slide" href="#"><span><i class="fa fa-angle-left"></i></span></a>';
        $html .= '<a class="next-slide" href="#"><span><i class="fa fa-angle-right"></i></span></a>';
        $html .= '</div></div>';

        return $html;
    }

    add_shortcode('image_slider_no_space', 'image_slider_no_space');
}

/* Countdown shortcode */

if (!function_exists('countdown')) {
    function countdown($atts, $content = null) {
        extract(shortcode_atts(array("year" => "", "month" => "", "day" => "", "hour" => "", "minute" => "", "month_label" => "", "day_label" => "", "hour_label" => "", "minute_label" => "", "second_label" => "", "month_singular_label" => "", "day_singular_label" => "", "hour_singular_label" => "", "minute_singular_label" => "", "second_singular_label" => "", "color" => "", "digit_font_size" => "", "label_font_size" => "", "font_weight" => "", "show_separator" => ""), $atts));
        $id = mt_rand(1000, 9999);
        $month_label_value = "Months";
        if($month_label != ""){
            $month_label_value = $month_label;
        }
        $day_label_value = "Days";
        if($day_label != ""){
            $day_label_value = $day_label;
        }
        $hour_label_value = "Hours";
        if($hour_label != ""){
            $hour_label_value = $hour_label;
        }
        $minute_label_value = "Minutes";
        if($minute_label != ""){
            $minute_label_value = $minute_label;
        }
        $second_label_value = "Seconds";
        if($second_label != ""){
            $second_label_value = $second_label;
        }

        $month_singular_label_value = "Month";
        if ($month_singular_label != "") {
            $month_singular_label_value = $month_singular_label;
        }
        $day_singular_label_value = "Day";
        if ($day_singular_label != "") {
            $day_singular_label_value = $day_singular_label;
        }
        $hour_singular_label_value = "Hour";
        if ($hour_singular_label != "") {
            $hour_singular_label_value = $hour_singular_label;
        }
        $minute_singular_label_value = "Minute";
        if ($minute_singular_label != "") {
            $minute_singular_label_value = $minute_singular_label;
        }
        $second_singular_label_value = "Second";
        if ($second_singular_label != "") {
            $second_singular_label_value = $second_singular_label;
        }

        $counter_style = "";
        if($color != "" || $font_weight != ''){
            $counter_style = "style='";
            if($color != ""){
                $counter_style .="color:".$color.";";
            }
            if($font_weight != ""){
                $counter_style .="font-weight:".$font_weight.";";
            }
            $counter_style .="'";
        }

        $html = "<div class='countdown ".$show_separator."' id='countdown".$id."' ".$counter_style."></div>";

        $html .= "<script>
		var \$j = jQuery.noConflict();
		\$j(document).ready(function() {
	        \$j('#countdown".$id."').countdown({
	            until: new Date( ".$year.", ".$month." - 1, ".$day.", ".$hour.", ".$minute.", 44),
	            labels: ['Years', '".$month_label_value."', 'Weeks', '".$day_label_value."', '".$hour_label_value."', '".$minute_label_value."', '".$second_label_value."'],
	            labels1: ['Year', '" . $month_singular_label_value . "', 'Week', '" . $day_singular_label_value . "', '" . $hour_singular_label_value . "', '" . $minute_singular_label_value . "', '" . $second_singular_label_value . "'],
                format: 'ODHMS',
	            timezone: ".get_option('gmt_offset').",
	            padZeroes: true,
	            ";
	            if($digit_font_size != "" || $digit_font_size != "" || $color != "") {
                    $html .= "
                    onTick: setCountdownStyle". $id ."";
                }
            $html .= "
            });";
            if($digit_font_size != "" || $digit_font_size != "" || $color != "") {
                $html .= "function setCountdownStyle". $id ."(){";
                    if($digit_font_size != "") {
                        $html .= "
                        \$j('#countdown" . $id . " .countdown-amount').css('font-size','" . $digit_font_size . "px').css('line-height','" . $digit_font_size . "px');
                        ";
                    }
                    if($label_font_size != "") {
                        $html .= "
                        \$j('#countdown" . $id . " .countdown-period').css('font-size','" . $label_font_size . "px');
                        ";
                    }
                    if($color != "") {
                        $html .= "
                        \$j('#countdown" . $id . " .countdown_separator').css('background-color','" . $color . "');
                        ";
                    }
                $html .= "}";
            }

        $html .= "
        });
	    </script>";
        return $html;
    }
}
add_shortcode('countdown', 'countdown');

/* Separator with Icon shortcode */

if (!function_exists('separator_with_icon')) {
    function separator_with_icon($atts, $content = null) {
        $args = array(
            "icon"      => "fa-codepen",
            "color"     => "",
            "opacity"   => "",
        );

        extract(shortcode_atts($args, $atts));

        $separator_style = "";

        if($color != "" || $opacity != '') {
            $separator_style .= "style='";

            if($color != "") {
                $separator_style .= "color:" . $color . ";";
            }

            if($opacity != "") {
                $separator_style .= "opacity:" . $opacity . ";";
            }

            $separator_style .= "'";
        }

        $html = '<span class="separator_with_icon" '.$separator_style.'><i class="fa '.$icon.'"></i></span>';
        return $html;
    }
}
add_shortcode('separator_with_icon', 'separator_with_icon');


// Blog Slider shortcode

if (!function_exists('blog_slider')) {

    function blog_slider($atts, $content = null) {

        global $qode_options;

        $args = array(
        	"type" => "",
        	"info_position" => "",
            "hover_box_color" => "",
            "order_by" => "date",
            "order" => "ASC",
            "number" => "-1",
            "blogs_shown" => "",
            "category" => "",
            "selected_projects" => "",
            "day_color" => "",
            "day_font_size" => "",
            "month_color" => "",
            "month_font_size" => "",
            "post_info_position" => "",
            "show_date" => "yes",
            "date_color" => "",
            "show_categories" => "yes",
            "category_color" => "",
            "show_author" => "yes",
            "author_color" => "",
            "title_tag" => "h3",
            "title_color" => "",
            "image_size" => "full",
            "image_width" => "",
            "image_height" => "",
            "enable_navigation" => "",
            "add_class" => "",
            "show_read_more" => "default",
            "show_excerpt" => "no",
            "excerpt_length" => "",
            "excerpt_color" => "",
            "show_comments" => "no",
            "comments_color" => ""
        );
        extract(shortcode_atts($args, $atts));


        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = "";
        $data_attribute = "";

        if ($blogs_shown !== "" && $type != "simple") {
            $data_attribute .= " data-blogs_shown='" . $blogs_shown. "'";
        }

        $title_color_style = ''; 

        if ($title_color != "") {
            $title_color_style .= 'style="';
            $title_color_style .= 'color: ' . $title_color . ';';
            $title_color_style .= '"';
        }

        $category_style = '';
        if ($category_color != '') {
            $category_style = 'style="color: ' . $category_color . ';"';
        }

        $hover_box_style = "";
        
        if ($hover_box_color != '') {
            $hover_box_style = 'style="background-color:' . $hover_box_color . ';"';
        }
        $day_style = "";
        if ($day_color !== "") {
            $day_style .= 'color: ' . $day_color . ';';
        }
        if ($day_font_size !== "") {
            $day_style .= 'font-size: ' . $day_font_size . 'px;';
        }

        $day_style = 'style= "'.$day_style.'"';

        $month_style = "";
        if ($month_color !== "") {
            $month_style .= 'color: ' . $month_color . ';';
        }
        if ($month_font_size !== "") {
            $month_style .= 'font-size: ' . $month_font_size . 'px;';
        }

        $month_style = 'style= "'.$month_style.'"';

        $date_style = "";
        if ($date_color !== "") {
            $date_style .= 'color: ' . $date_color . ';';
        }

        $date_style = 'style= "'.$date_style.'"';

        $author_style = "";
        if ($author_color !== "") {
            $author_style .= 'color: ' . $author_color . ';';
        }

        $author_style = 'style= "'.$author_style.'"';
        
        $comments_style = "";
        if ($comments_color !== "") {
            $comments_style .= 'color: ' . $comments_color . ';';
        }

        $comments_style = 'style= "'.$comments_style.'"';

        $excerpt_style = "";
        if ($excerpt_color !== "") {
            $excerpt_style .= 'color: ' . $excerpt_color . ';';
        }

        $excerpt_style = 'style= "'.$excerpt_style.'"';

        //get proper image size
        switch ($image_size) {
            case 'landscape':
                $thumb_size = 'portfolio-landscape';
                break;
            case 'portrait':
                $thumb_size = 'portfolio-portrait';
                break;
            default:
                $thumb_size = 'full';
                break;
        }

        $type_class = " blog_slider_carousel";
        if($type == "simple") {
        	$type_class = " simple_slider";
        }


        $html .= "<div class='blog_slider_holder clearfix " . $add_class . "'><div class='blog_slider" .  $type_class . "'" . $data_attribute . "><ul class='blog_slides'>";

        if ($category == "") {
            $q = array(
                'post_type' => 'post',
                'orderby' => $order_by,
                'order' => $order,
                'posts_per_page' => $number
            );
        } else {
            $q = array(
                'post_type' => 'post',
                'category_name' => $category,
                'orderby' => $order_by,
                'order' => $order,
                'posts_per_page' => $number
            );
        }

        $project_ids = null;
        if ($selected_projects != "") {
            $project_ids = explode(",", $selected_projects);
            $q['post__in'] = $project_ids;
        }

        query_posts($q);

        if (have_posts()) : $postCount = 0;
            while (have_posts()) : the_post();

            	if ($type == "" || $type == "carousel"){

	                $html .= "<li class='item'>";
	                $html .= '<div class="item_holder">';

	                $blog_info_class = "";
	                if ($info_position == "info_in_bottom_always"){
	                	$blog_info_class .= "info_bottom";
	                }

	                $html .= '<div class="blog_text_holder ' . $blog_info_class . '" ' . $hover_box_style . '>';
	                $html .= '<div class="blog_text_holder_outer">';

	                if($info_position == "info_in_bottom_always"){
						$html .= '<div class="blog_text_date_holder">';
						$html .= '<div class="blog_slider_date_holder">';
						$html .= '<span class="blog_slider_day" '.$day_style.' >' . get_the_time('d') . '</span><span class="blog_slider_month" '.$month_style.'>' . get_the_time('M') . '</span>';
						$html .= '</div>';
						$html .= '</div>';

		                $html .= '<div class="blog_text_holder_inner">';
		                $html .= '<' . $title_tag . ' class="blog_slider_title" ><a href="' . get_permalink() . '" ' . $title_color_style . '>' . get_the_title() . '</a></' . $title_tag . '>';

		                if ($show_categories == 'yes') {
		                    $html .= '<div class="blog_slider_categories">';


		                    // get categories for specific article
		                    $category_html = "";
		                    $k = 1;
		                    $cat = get_the_category();

		                    foreach ($cat as $cats) {
		                        $category_html = "$cats->name";
		                        if (count($cat) != $k) {
		                            $category_html .= ' / ';
		                        }
		                        $html .= '<a class="blog_project_category" ' . $category_style . ' href="' . get_category_link($cats->term_id) . '">' . $category_html . ' </a> ';
		                        $k++;
		                    }

		                    $html.= '</div>';
		                }
						if ($show_comments == "yes") {
							$comments_count = get_comments_number();
							switch ($comments_count) {
								case 0:
									$comments_count_text = __('No comment', 'qode');
									break;
								case 1:
									$comments_count_text = $comments_count . ' ' . __('Comment', 'qode');
									break;
								default:
									$comments_count_text = $comments_count . ' ' . __('Comments', 'qode');
								break;
							}

							$html .= '<a class="blog_slider_post_comments" '.$comments_style.' href="' . get_comments_link() . '">';
							if ($show_categories == 'yes'){
								$html .= ' / ';
							}
							$html .= $comments_count_text;
							$html .= '</a>';//close post_comments
						}
		                $html .= '</div>';
	                }
	                else {
		                $html .= '<div class="blog_text_holder_inner">';

		                if ($show_date == 'yes') {
		                    $html .= '<span class="blog_slider_date_holder" ' . $date_style . '>';
		                    $html .= get_the_time('F d, Y');
		                    $html .= '</span>';
		                }

		                
		                $html .= '<' . $title_tag . ' class="blog_slider_title" ><a href="' . get_permalink() . '" ' . $title_color_style . '>' . get_the_title() . '</a></' . $title_tag . '>';

		                if ($show_categories == 'yes') {
		                    $html .= '<div class="blog_slider_categories">';


		                    // get categories for specific article
		                    $category_html = "";
		                    $k = 1;
		                    $cat = get_the_category();

		                    foreach ($cat as $cats) {
		                        $category_html = "$cats->name";
		                        if (count($cat) != $k) {
		                            $category_html .= ' / ';
		                        }
		                        $html .= '<a class="blog_project_category" ' . $category_style . ' href="' . get_category_link($cats->term_id) . '">' . $category_html . ' </a> ';
		                        $k++;
		                    }

		                    $html.= '</div>';
		                }

		                $html .= '</div>'; // blog_text_holder_inner
		            }
	                $html .= '</div>';  // blog_text_holder_outer
	                $html .= '</div>'; // blog_text_holder

	                $html .= '<div class="blog_image_holder">';
	                $html .= '<span class="image">';
					if ($image_size !== 'custom' || $image_width == '' || $image_height == ''){
						$html.= get_the_post_thumbnail(get_the_ID(), $thumb_size);
					}
					else{
						$html.= qode_generate_thumbnail(get_post_thumbnail_id(get_the_ID()),null,$image_width,$image_height);
					}
	                $html .= '</span>';
	                $html .= '</div>'; // close blog_image_holder
	                $html .= '</div>'; // close item_holder
	                $html .= "</li>";
	            }
	            else if ($type == "simple") {
					$html .= '<li class="item">';
					$html .= '<div class = "blog_post_holder">';
					$html .= '<div class = "blog_image_holder">';
					$html .= '<span class = "image">';
					if ($image_size !== 'custom' || $image_width == '' || $image_height == ''){
						$html.= get_the_post_thumbnail(get_the_ID(), $thumb_size);
					}
					else{
						$html.= qode_generate_thumbnail(get_post_thumbnail_id(get_the_ID()),null,$image_width,$image_height);
					}
					$html .= '</span>';
					$html .= '</div>';//close blog_image_holder div
					$html .= '<div class = "blog_text_wrapper">';
					$html .= '<div class = "blog_text_holder_outer">';
					$html .= '<div class = "blog_text_holder_inner">';
					$html .= '<div class = "blog_text_holder_inner2"'. $hover_box_style.'>';
					if ($post_info_position !== "above_title") {
						$html .= '<'.$title_tag.'  class= "blog_slider_simple_title" ><a href="' . get_permalink() . '" ' . $title_color_style . '>' . get_the_title() . '</a></'.$title_tag.'>';
					}
					if($show_categories == "yes" || $show_author == "yes" || $show_date == "yes"){
						$html .= '<div class="blog_slider_simple_info">';
						if($show_categories == "yes"){
							$html .= '<div class = "post_info_item category" >';
								// get categories for specific article
									  $cat_html = "";
									  $k = 1;
									  $cat = get_the_category();

									  foreach ($cat as $cats) {
										  $cat_html = "$cats->name";
										  if (count($cat) != $k) {
											  $cat_html .= ' / ';
										  }
										  $html .= '<a class="blog_simple_slider_category" ' . $category_style . 'href="' . get_category_link($cats->term_id) . '">' . $cat_html . ' </a> ';
										  $k++;
									  }
							$html .= '</div>'; //close post_info_item category div
						}
						if($show_author == "yes"){
							$html .= '<div class = "post_info_item author" >';
							$html .= '<a href="' . get_author_posts_url(get_the_author_meta("ID")) . '" ' . $author_style . ' >' . get_the_author_meta("display_name") . '</a>';				
							$html .= '</div>'; //close post_info_item author div
						}
						if($show_date == "yes"){
							$html .= '<div class = "post_info_item date"><span '. $date_style . '>' . get_the_time(get_option('date_format')) .'</span></div>';
						}
						$html .= '</div>'; //close blog_slider_simple_info div
					}
					if ($post_info_position == "above_title") {
						$html .= '<'.$title_tag.'  class= "blog_slider_simple_title" ><a href="' . get_permalink() . '" ' . $title_color_style . '>' . get_the_title() . '</a></'.$title_tag.'>';
					}
					if ($show_excerpt == "yes") {
						$excerpt = ($excerpt_length > 0) ? substr(get_the_excerpt(), 0, intval($excerpt_length)).'...' : get_the_excerpt();
						$html .= '<p class = "blog_slider_simple_excerpt" '.$excerpt_style.'>'.$excerpt.'</p>';
					}
					if($show_read_more == "yes"){
						$html .= '<div class = "read_more_wrapper">';
						$html .= '<a href="'.get_the_permalink().'" target="_self" class="qbutton small read_more_button">'.__("Read More", "qode").'</a>';				
						$html .= '</div>'; //close read_more_wrapper div
					}				
					$html .= '</div>'; //close blog_text_holder_inner2 div
					$html .= '</div>'; //close blog_text_holder_inner div		
					$html .= '</div>'; //close blog_text_holder_outer div
					$html .= '</div>'; //close blog_text_wrapper div
					$html .= '</div>'; //close blog_post_holder div
					$html .= '</li>'; //close li	
	            }
                $postCount++;

            endwhile;

        else:
            $html .= __('Sorry, no posts matched your criteria.', 'qode');
        endif;

        wp_reset_query();

        $html .= "</ul>";
        if ($enable_navigation) {
            $html .= '<ul class="caroufredsel-direction-nav"><li><a id="caroufredsel-prev" class="caroufredsel-prev" href="#"><div><i class="fa fa-angle-left"></i></div></a></li><li><a class="caroufredsel-next" id="caroufredsel-next" href="#"><div><i class="fa fa-angle-right"></i></div></a></li></ul>';
        }
        $html .= "</div></div>";

        return $html;
    }

    add_shortcode('blog_slider', 'blog_slider');
}


/* Masonry Gallery shortcode*/

if (!function_exists('qode_masonry_gallery')) {

	function qode_masonry_gallery($atts, $content = null){
		global $wp_query, $qodeIconCollections;
        $default_args = array(
            'category' => '',
            'number' => -1,
            'order' => 'DESC',
			'parallax_item_speed' => '0.3',
			'parallax_item_offset' => '0',
        );

        extract(shortcode_atts($default_args, $atts));
		
		$parallax_item_speed = esc_attr($parallax_item_speed);
		$parallax_item_offset = esc_attr($parallax_item_offset);
		
        $html = '';

        /* Query for items */
        $query_args = array(
            'post_type' => 'masonry_gallery',
            'orderby' => 'date',
            'order' => $order,
            'posts_per_page' => $number
        );

        if ($category != "") {
            $query_args['masonry_gallery_category'] = $category;
        }
        $query = new $wp_query( $query_args );
		
		switch ($number) {
			case 1:
				$item_number_class = 'one_column';
			break;
			case 2:
				$item_number_class = 'two_columns';
			break;
			case 3:
				$item_number_class = 'three_columns';
			break;
			default:
				$item_number_class = '';
			break;	
		}

        $html .= '<div class="masonry_gallery_holder ' . $item_number_class . ' " data-parallax_item_speed="'.$parallax_item_speed.'" data-parallax_item_offset="'.$parallax_item_offset.'"><div class="grid-sizer"></div>';

        if ($query->have_posts()) :
            while ( $query->have_posts() ) : $query->the_post();

                $item_type = '';
                $item_class = '';
                $item_text = '';
                $item_link = '';
                $item_button_label = '';
                $item_icon = '';
                $item_link_target = '_self';
				$item_parallax_class = "";

                if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_type', true) !== '') {
                    $item_type = get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_type', true);
                }
                if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_size', true) !== '') {
                    $item_class = get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_size', true);
                }
                if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_text', true) !== '') {
                    $item_text = get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_text', true);
                }
                if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_link', true) !== '') {
                    $item_link = get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_link', true);
                }
                if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_link_target', true) !== '') {
                    $item_link_target = get_post_meta(get_the_ID(), 'qode_masonry_gallery_item_link_target', true);
                }
				$masonry_parallax = get_post_meta(get_the_ID(), "qode_masonry_item_parallax", true);
				
				if($masonry_parallax == "yes"){
					$item_parallax_class = " parallax_item";
				}

                switch ($item_class) {
                    case 'square_big':
                        $gallery_thumb_size = 'portfolio_masonry_large';
                        break;
                    case 'square_small':
                        $gallery_thumb_size = 'portfolio-square';
                        break;
                    case 'rectangle_portrait':
                        $gallery_thumb_size = 'portfolio_masonry_tall';
                        break;
                    case 'rectangle_landscape':
                        $gallery_thumb_size = 'portfolio_masonry_wide';
                        break;
                    default:
                        $gallery_thumb_size = 'portfolio-square';
                        break;
                }

                $html .= '<article class="masonry_gallery_item ' . esc_attr($item_class) . ' ' .esc_attr($item_type). ' '. esc_attr($item_parallax_class) .'">';

                switch($item_type) {
                    case 'with_button':

                        //With Button Type
                        if (get_post_meta(get_the_ID(), 'qode_masonry_gallery_button_label', true) !== '') {
                            $item_button_label = get_post_meta(get_the_ID(), 'qode_masonry_gallery_button_label', true);
                        }

                        if (has_post_thumbnail()) {
                            $html .= '<div class = "masonry_gallery_image_holder">';
                            $html .= get_the_post_thumbnail(get_the_ID(),$gallery_thumb_size);
                            $html .= '</div>';
                        }

                        $html .= '<div class="masonry_gallery_item_outer"><div class="masonry_gallery_item_inner"><div class="masonry_gallery_item_content">';

                        $html .= '<h3>' . get_the_title() . '</h3>';
                        $html .= '<p class="masonry_gallery_item_text">' . esc_html($item_text) . '</p>';

                        if ($item_link !== '' && $item_button_label !== '') {
                            $html .= '<a href="'.esc_url($item_link).'" class="qbutton masonry_gallery_item_button" target="'.esc_attr($item_link_target).'">'.$item_button_label.'</a>';
                        }

                        $html .= '</div></div></div>';

                        break;

                    case 'with_icon':

                        //With Icon Type
                        if ($item_link !== '') {
                            $html .= '<a href="'.esc_url($item_link).'" target="'.esc_attr($item_link_target).'">';
                        }

                        if (has_post_thumbnail()) {
                            $html .= '<div class = "masonry_gallery_image_holder">';
                            $html .= get_the_post_thumbnail(get_the_ID(),$gallery_thumb_size);
                            $html .= '</div>';
                        }

                        $html .= '<div class="masonry_gallery_item_outer"><div class="masonry_gallery_item_inner"><div class="masonry_gallery_item_content">';

                        $item_icon_pack = get_post_meta(get_the_ID(), "qode_masonry_gallery_item_with_icon_icon_pack", true) != '' ? get_post_meta(get_the_ID(), "qode_masonry_gallery_item_with_icon_icon_pack", true) : 'font_awesome';

                        //Get icon pack
                        if ($item_icon_pack != ''){
                            $icon_collection_obj = $qodeIconCollections->getIconCollection($item_icon_pack);

                            //Get icon pack param
                            $item_icon_param = $icon_collection_obj->param;

                            //Get icon
                            if (get_post_meta(get_the_ID(), "qode_masonry_gallery_item_with_icon_".$item_icon_param, true) !== '') {
                                $item_icon = get_post_meta(get_the_ID(), "qode_masonry_gallery_item_with_icon_".$item_icon_param, true);
                            }

                            //Render icon
                            if (method_exists($icon_collection_obj, 'render') && $item_icon !== '') {
                                $html .= '<div class="masonry_gallery_item_icon">'.$icon_collection_obj->render($item_icon).'</div>';
                            }

                        }

                        $html .= '<h3>' . get_the_title() . '</h3>';
                        $html .= '<p class="masonry_gallery_item_text">' . esc_html($item_text) . '</p>';


                        $html .= '</div></div></div>';

                        if ($item_link !== '') {
                            $html .= '</a>';
                        }
                        break;

                    case 'standard':

                        //Only image
                        if ($item_link !== '') {
                            $html .= '<a href="'.esc_url($item_link).'" target="'.esc_attr($item_link_target).'">';
                        }

                        if (has_post_thumbnail()) {
                            $html .= '<div class = "masonry_gallery_image_holder">';
                            $html .= get_the_post_thumbnail(get_the_ID(),$gallery_thumb_size);
                            $html .= '</div>';
                        }


                        $html .= '<div class="masonry_gallery_item_outer">';

                        $html .= '<div class="masonry_gallery_item_inner">';

                        $html .= '<div class="masonry_gallery_item_content">';

                        $html .= '<h3>' . get_the_title() . '</h3>';


                        $html .= '</div></div></div>';

                        if ($item_link !== '') {
                            $html .= '</a>';
                        }
                        break;
                }

                $html .= '</article>';

            endwhile;
        else:
            $html .= __('Sorry, no posts matched your criteria.', 'qode_core');
        endif;

        $html .= '</div>';

        return $html;
	}
	add_shortcode('qode_masonry_gallery', 'qode_masonry_gallery');
}



/* Google Map Shortcode */

if (!function_exists('qode_google_map')) {

	function qode_google_map($atts, $content = null) {
		extract(shortcode_atts(
			array(
				"address1" => "",
				"address2" => "",
				"address3" => "",
				"address4" => "",
				"address5" => "",
				"custom_map_style" => false,
				"color_overlay" => "#393939",
				"saturation" => "-100",
				"lightness" => "-60",
				"zoom" => "12",
				"pin" => "",
				"google_maps_scroll_wheel" => false,
				"map_height" => "600"
			), $atts));


		$html = "";
		$unique_id = rand(0, 100000);
		$holder_id = 'map_canvas_' . $unique_id;
		$map_pin = "";

		if ($pin != "") {
			$map_pin = wp_get_attachment_image_src($pin, 'full', true);
			$map_pin = $map_pin[0];
		} else {
			$map_pin = get_template_directory_uri() . "/img/pin.png";
		}


		$data_attribute = '';
		if ($address1 != "" || $address2 != "" || $address3 != "" || $address4 != "" || $address5 != "") {
			$data_attribute .= "data-addresses='[\"";
			$addresses_array = array();
			if ($address1 != "") {
				array_push($addresses_array, esc_attr($address1));
			}
			if ($address2 != "") {
				array_push($addresses_array, esc_attr($address2));
			}
			if ($address3 != "") {
				array_push($addresses_array, esc_attr($address3));
			}
			if ($address4 != "") {
				array_push($addresses_array, esc_attr($address4));
			}
			if ($address5 != "") {
				array_push($addresses_array, esc_attr($address5));
			}
			$data_attribute .= implode('","', $addresses_array);
			$data_attribute .="\"]'";
		}

		$data_attribute .= " data-custom-map-style='" . $custom_map_style . "'";
		$data_attribute .= " data-color-overlay='" . $color_overlay . "'";
		$data_attribute .= " data-saturation='" . $saturation . "'";
		$data_attribute .= " data-lightness='" . $lightness . "'";
		$data_attribute .= " data-zoom='" . $zoom . "'";
		$data_attribute .= " data-pin='" . $map_pin . "'";
		$data_attribute .= " data-unique-id='" . $unique_id . "'";
		$data_attribute .= " data-google-maps-scroll-wheel='" . $google_maps_scroll_wheel . "'";

		if ($map_height != "") {
			$data_attribute .= " data-map-height='" . $map_height . "'";
		}

		$html .= "<div class='google_map_shortcode_holder' style='height:" . $map_height . "px;'><div class='qode_google_map' style='height:" . $map_height . "px;' id='" . $holder_id . "' " . $data_attribute . "></div>";

		if ($google_maps_scroll_wheel == "false") {
			$html .= "<div class='google_map_shortcode_overlay'></div>";
		}
		$html .= "</div>";
		return $html;
	}

	add_shortcode('qode_google_map', 'qode_google_map');
}

/* Qode Parallax Layers */

if (!function_exists('qode_parallax_layers')) {

    function qode_parallax_layers($atts, $content = null) {
        global $qode_options;
        $args = array(
            "images" => "",
			"full_screen" => "",
            "height" => "500"
        );

        extract(shortcode_atts($args, $atts));

        $images = esc_attr($images);
		$height = esc_attr($height);

        //init variables
        $html = "";
        $parallax_layers_data_styles = '';
		$parallax_layers_holder_styles = '';
        $parallax_layers_holder_classes = '';

        //is full screen height for the slider set?
        if ($full_screen == 'yes') {
            $parallax_layers_holder_classes .= ' full_screen_height';
        }

        //is height for the slider set?
        if ($height !== '' && $full_screen == 'no') {
            $parallax_layers_holder_styles .= 'height: ' . $height . 'px;';
			$parallax_layers_data_styles = 'data-height="' . $height . '"';
        }

        $html .= "<div class='qode_parallax_layers " . $parallax_layers_holder_classes . "' style='" . $parallax_layers_holder_styles . "' ".$parallax_layers_data_styles."><div class='qode_parallax_layers_holder preload_parallax_layers'>";

        if ($images != '') {
            $parallax_images_array = explode(',', $images);
        }

        if (isset($parallax_images_array) && count($parallax_images_array) != 0) {
            
            foreach ($parallax_images_array as $pimg_id) {

                $pimage_src = wp_get_attachment_image_src($pimg_id, 'full', true);
                $pimage_alt = get_post_meta($pimg_id, '_wp_attachment_image_alt', true);
                $pimage_src = $pimage_src[0];
                          
                $html .= '<div class="image" style="background-image:url(' . $pimage_src . ');" ></div>';
            }
        }
		
		if($content != ""){
			$html .= '<div class="paralax_layers_content_holder"><div class="paralax_layers_content"><div class="paralax_layers_content_inner"><div class="container_inner">'.do_shortcode($content).'</div></div></div></div>';
		}

        $html .= '</div></div>';

        return $html;
    }

    add_shortcode('qode_parallax_layers', 'qode_parallax_layers');
}