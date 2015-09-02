<?php


add_action('init', 'qode_admin_map_init');
function qode_admin_map_init() {
	global $qode_options_proya;
	global $qodeFramework;
	global $options_fontstyle;
	global $options_fontweight;
	global $options_texttransform;
	global $options_fontdecoration;
    global $qodeIconCollections;
	require_once("10.general/map.php");
	require_once("20.logo/map.php");
	require_once("30.header/map.php");
	require_once("40.footer/map.php");
	require_once("50.title/map.php");
	require_once("60.fonts/map.php");
	require_once("70.elements/map.php");
	require_once("74.sidebar/map.php");
	require_once("75.slider/map.php");
	require_once("80.blog/map.php");
	require_once("90.portfolio/map.php");
	require_once("95.verticalsplitslider/map.php");
	require_once("100.social/map.php");
	require_once("110.error404/map.php");
	require_once("120.contact/map.php");
	require_once("130.parallax/map.php");
	require_once("140.contentbottom/map.php");
	if(qode_visual_composer_installed() && version_compare(qode_get_vc_version(), '4.4.2') >= 0) {
		require_once("144.visualcomposer/map.php");
	} else {
		$qodeFramework->qodeOptions->addOption("enable_grid_elements","no");
	}

	if(qode_contact_form_7_installed()) {
		require_once("145.contactform7/map.php");
	}

    if(qode_is_woocommerce_installed()) {
        require_once("147.woocommerce/map.php");
    }

    require_once("146.maintenance/map.php");
	require_once("150.reset/map.php");
}