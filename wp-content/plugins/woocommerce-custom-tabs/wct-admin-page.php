<?php

class WCT_Admin_Page {
	
	function __construct() {
		
		add_action( 'admin_menu', array($this, 'wct_admin_menu') ) ;
		add_action( 'admin_init', array($this, 'wct_admin_init') );
		
	}		

	function wct_admin_menu () {
		
		//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
		add_options_page( __('WooCommerce Custom Tabs','wct'),__('WooCommerce Custom Tabs','wct')
			,'manage_options','woocommerce_custom_tabs', array($this, 'add_options_page_callback' ));
	}
	
	
	function wct_admin_init()
	{
		
		wp_enqueue_style( 'wct-admin', plugins_url('wct-admin.css', __FILE__) );
		
		$this->wct_set_defaults();
	
		//register_setting( $option_group, $option_name, $sanitize_callback );        
		register_setting(
			'wct_general_settings', // Option group / tab page
			'wct_general_settings', // Option name
			array($this, 'sanitize') // Sanitize
		);
	
		add_settings_section(
			'wct_general_section', // ID 
			__('General Settings','wct'), // Title //ML
			array($this,'print_section_info'), // Callback 
			'wct_general_settings' // Page / tab page
		);
		
		add_settings_field(
			'enable_product_category_dependent_tabs', // ID
			__('Enable product category dependent product tab pages (PRO)','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);

		add_settings_field(
			'disable_sub_category_display', // ID
			__('Disable sub category display (PRO)','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);

		add_settings_field(
			'hide_empty_tabs', // ID
			__('Hide empty tabs','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);
		
		add_settings_field(
			'common_tabname', // ID
			__('Name of your tab (especially for Lite version)','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);
	
		add_settings_field(
			'common_tab_priority', // ID
			__('Priority of your tab (especially for Lite version)','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);

		add_settings_field(
			'enable_multiple_tabs_admin_test', // ID
			__('Enable multiple tabpage administration test','wct'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wct_general_settings', // Page / tab page
			'wct_general_section' // Section           
		);
		}

	function wct_set_defaults() {

		$options = get_option( 'wct_general_settings' ); 
		
		$options = wp_parse_args( $options, array(
			'enable_product_category_dependent_tabs' => 0,
			'disable_sub_category_display' => 0,
			'common_tabname' => '',
			'common_tab_priority' => 30,
			'hide_empty_tabs' => 0,
			'enable_multiple_tabs_admin_test' => 1,
		) );
		update_option( 'wct_general_settings', $options );
		
	}
	
	function add_options_page_callback()
	{

		global $is_premium;
		
        //check if WooCommerce plugin is installed and active
    	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    		showAdminMessage(__('This plugin is based on WooCommerce plugin. Please install and activate <a href="http://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce - excelling eCommerce</a> first.','wri'), true);
			return;
		}
		
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('WooCommerce Custom Tabs by WebshopLogic','wct') ?></h2>    
			
			<div style="float:left; width: 70%">
			
				<form method="post" action="options.php"><!--form-->  
					
					<?php
		
					settings_fields( 'wct_general_settings' );					
					$options = get_option( 'wct_general_settings' ); //option_name
					
					?>
					<h3><?php _e('General Settings','wct') ?></h3>
					<?php 
					//echo __('Enter your settings below','wct') . ':' 
					?>
		
					<table class="form-table">
		
						<tr valign="top">
							<th scope="row"><?php echo __('Hide empty tabs','wct') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wct_general_settings[hide_empty_tabs]" value="0"/>
									<input type="checkbox" id="hide_empty_tabs" name="wct_general_settings[hide_empty_tabs]"
									value="1"' . checked( 1, esc_attr( $options['hide_empty_tabs']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('Set this option on to hide empty tabs automatically.','wct') . '</span>';	
								?>    
							</td>
						</tr>							

						<?php
						?>
						<tr valign="top">
							<th scope="row"><?php echo __('Enable product category dependent product tab pages (PRO)','wct') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wct_general_settings[enable_product_category_dependent_tabs]" value="0"/>
									<input type="checkbox" id="enable_product_category_dependent_tabs" name="wct_general_settings[enable_product_category_dependent_tabs]"
									value="1"' . checked( 1, esc_attr( $options['enable_product_category_dependent_tabs']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('There are two custom tab types. One of them is visible on all products, the other tab type is displayed only if you assign it to a product category, and the actually displayed product has this category. You can turn on/off this second type of functionality using this checkbox. The other settings can be made in Product Tabs menu.','wct') . '</span>';
								if (!$is_premium) 
									echo '<br /><span class="description">' . __('These functionality available in Pro version, and you can test administration of tab pages if you turn on testing of multiple tab administration option in lite version below.','wct') . '</span>';								?>    
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo __('Disable sub category display (PRO)','wct') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wct_general_settings[disable_sub_category_display]" value="0"/>
									<input type="checkbox" id="disable_sub_category_display" name="wct_general_settings[disable_sub_category_display]"
									value="1"' . checked( 1, esc_attr( $options['disable_sub_category_display']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('Do not display tabs assigned to a product sub category, if the main category is not set for the product.','wct') . '</span>';
								if (!$is_premium) 
									echo '<br /><span class="description">' . __('These functionality available in Pro version, and you can test administration of tab pages if you turn on testing of multiple tab administration option in lite version below.','wct') . '</span>';								?>    
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo __('Name of your tab (especially for Lite version)','wct') . ':' ?></th>
							<td>
								<?php
						        printf(
						            '<input type="text" id="common_tabname" name="wct_general_settings[common_tabname]" value="%s" />',
						            esc_attr( $options['common_tabname'])
						        );
						        echo '<br /><span class="description">' . __('You can use one special tab page independently of multiple tab page handling. In Lite version this tab will be displayed. This field contains the name of this tab page, leave this field empty if you want to hide this tab page.','wct') . '</span>';
							    ?>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo __('Priority of your tab (especially for Lite version)','wct') . ':' ?></th>
							<td>
								<?php
						        printf(
						            '<input type="number" id="common_tab_priority" name="wct_general_settings[common_tab_priority]" value="%s" />',
						            esc_attr( $options['common_tab_priority'])
						        );
						        echo '<br /><span class="description">' . __('Priority is affected on the order of tab pages.','wct') . '</span>';
							    ?>
							</td>
						</tr>

						<?php
						
						if ( !$is_premium ) {
						?>
	
							<tr valign="top">
								<th scope="row"><?php echo __('Enable evaluation of multiple tab administration in Lite version','wct') . ':' ?></th>
								<td>
									<?php
									printf(
										'<input type="hidden" name="wct_general_settings[enable_multiple_tabs_admin_test]" value="0"/>
										<input type="checkbox" id="enable_multiple_tabs_admin_test" name="wct_general_settings[enable_multiple_tabs_admin_test]"
										value="1"' . checked( 1, esc_attr( $options['enable_multiple_tabs_admin_test']), false ) . ' />'
									);
									echo '<br /><span class="description">' . __('Turning this option on, you can define multiple tabs, the admin pages has full functionality for evaluation purposes, but the configured tab pages will be displayed only in Pro version. ','wct') . '</span>';
									?>    
								</td>
							</tr>
			
						<?php
						}
						?>

					</table>
		
					<?php
					submit_button();
					?>
		
				</form><!--end form-->
	
			</div><!--emd float:left; width: 70% / 100% -->
		
			<?php
			if (!$is_premium) {
			?>
		
				<div class="wri_admin_left_sidebar" style="float:right; ">
					
					<style>
						a.wli_pro:link {color: black; text-decoration:none;}
						a.wli_pro:visited {color: black; text-decoration:none;}
						a.wli_pro:hover {color: black; text-decoration:underline;}
						a.wli_pro:active {color: black; text-decoration:none;}
					</style>
		
					<a href="http://webshoplogic.com/products/" class="wli_pro" target="_blank">
						<h2><?php _e('Try out WooCommerce Custom Tabs Pro', 'wct'); ?></h2>
					</a>							
					
					<a href="http://webshoplogic.com/products/" class="wli_pro" target="_blank">
						<img src="<?php echo plugins_url('images/WCT_product_box_RIGHT_PRO_v1_235x235.png', __FILE__)?>" alt="Upgrade to PRO">
					</a>
		
					<?php echo __('As a good shop owner, you may want to publish more information about your products. This plugin will help you displaying product details in separate tab pages. Even shortcodes may be used to display galleries, product support forums (e. g. bbpress) and statistics.','wct') . '<br><br>' ; ?>				
					<?php echo __('PRO features','wct') . ':' ; ?>
					<ul>
						<li><?php echo __('Multiple tabs handling','wct') ; ?></li>
						<li><?php echo __('Pre register tabs, this way you can make standardised tabs for a bunch of products','wct') ; ?></li>
						<li><?php echo __('Product category dependent tab pages','wct') ; ?></li>
						<li><?php echo __('Possibility of using shortcode','wct') ; ?></li>
					</ul>
				</div>
			<?php
			}
			?>
		</div>
		<?php
					
	}
	
	function sanitize( $input )
	{
	
		return $input;
	}

	//delete wct options
	//delete from em_options where option_name like 'wct_gen%' 			


}