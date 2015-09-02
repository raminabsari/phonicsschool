<?php
/**
 * GENERAL ARRAY OPTIONS
 */

$general = array(

	'general'  => array(

		array(
			'title' => __( 'General Options', 'yith-wacp' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-general-options'
		),

		array(
			'title' => __( 'Show "View Cart" Button', 'yith-wacp' ),
			'desc' => __( 'Choose to show "View Cart" button in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-go-cart'
		),

		array(
			'title' => __( 'Show "Continue Shopping" Button', 'yith-wacp' ),
			'desc' => __( 'Choose to show "Continue Shopping" button in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-continue-shopping'
		),

		array(
			'title' => __( 'Button Background', 'yith-wacp' ),
			'desc'  => __( 'Select the button background color', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#ebe9eb',
			'id'    => 'yith-wacp-button-background'
		),

		array(
			'title' => __( 'Button Background on Hover', 'yith-wacp' ),
			'desc'  => __( 'Select the button background color on mouse hover', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#dad8da',
			'id'    => 'yith-wacp-button-background-hover'
		),

		array(
			'title' => __( 'Button Text', 'yith-wacp' ),
			'desc'  => __( 'Select the color of the text of the button', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text'
		),

		array(
			'title' => __( 'Button Text on Hover', 'yith-wacp' ),
			'desc'  => __( 'Select the color of the text of the button on mouse hover', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text-hover'
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-general-options'
		)
	)
);

return apply_filters( 'yith_wacp_panel_general_options', $general );