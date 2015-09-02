<?php

$args = array(
	"background_color" => "",
	"background_image" => "",
	"item_padding" => "",
	"vertical_alignment" => "",
	"advanced_animations" => "no",
	"start_position" => "",
	"end_position" => "",
	"start_animation_style" => "",
	"end_animation_style" => "",
);

$html = "";
$qode_elements_item_style = "";
$qode_elements_item_inner_style = "";
$background_image_src = "";


extract(shortcode_atts($args, $atts));

$background_color = esc_attr($background_color);
$item_padding = esc_attr($item_padding);
$start_position = esc_attr($start_position);
$end_position = esc_attr($end_position);
$start_animation_style = esc_attr($start_animation_style);
$end_animation_style = esc_attr($end_animation_style);


if($background_color != "" || $background_image !== '' || $vertical_alignment !== ""){
	$qode_elements_item_style .= " style='";
}

if($background_color != ""){
	$qode_elements_item_style .= "background-color:" . $background_color . ";";
}

if($background_image != ""){
	if(is_numeric($background_image)) {
		$background_image_src = wp_get_attachment_url($background_image);
	} else {
		$background_image_src = $background_image;
	}
	$qode_elements_item_style .= "background-image: url(" . $background_image_src . ");";
	$qode_elements_item_style .= "background-position: center;";
}

if($vertical_alignment != ""){
	$qode_elements_item_style .= "vertical-align:" . $vertical_alignment . ";";
}

if($background_color != "" || $background_image !== '' || $vertical_alignment !== ""){
	$qode_elements_item_style .= "'";
}

if($item_padding != ""){
	$qode_elements_item_inner_style = " style='padding:". $item_padding ."'";
}

$html .= "<div class='q_elements_item' data-animation='$advanced_animations'";
if ($advanced_animations == 'yes') {
	$html .= " data-".$start_position."='$start_animation_style' data-".$end_position."='$end_animation_style'";
}
$html .= $qode_elements_item_style . "><div class='q_elements_item_inner'>";
$html .= "<div class='q_elements_item_content'". $qode_elements_item_inner_style .">";

$html .= do_shortcode($content);
$html .= '</div></div></div>';
echo $html;

