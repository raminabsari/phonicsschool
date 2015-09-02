<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// Theme Preview
// Renders localized version of theme preview that is merged into 
// jQuery selectmenu object for parent and child theme options
?>

<div id="ctc_theme_option_<?php echo $this->ctc()->sanitize_slug( $slug ); ?>" class="clearfix ctc-theme-option">
  <div class="ctc-theme-option-left"><img src="<?php echo $theme[ 'screenshot' ]; ?>" class="ctc-theme-option-image"/></div>
  <div class="ctc-theme-option-right">
    <h3 class="theme-name"><?php echo $theme[ 'Name' ]; ?></h3>
    <?php _e( 'Version: ', 'chld_thm_cfg' ); echo esc_attr( $theme[ 'Version' ] );?>
    <br/>
    <?php _e( 'By: ', 'chld_thm_cfg' ); echo esc_attr( $theme[ 'Author' ] );?>
    <br/><?php if ( !is_multisite() || $theme[ 'allowed' ] ): ?>
    <a href="<?php echo admin_url( '/customize.php?theme=' . $slug );?>" title="<?php _e( 'Preview', 'chld_thm_cfg' ); 
    if ( is_multisite() ) _e(' in default Site', 'chld_thm_cfg'); ?>" class="ctc-live-preview" target="_blank">
    <?php _e( 'Live Preview', 'chld_thm_cfg' ); ?>
    </a><?php else: ?>
    <a href="<?php echo network_admin_url( '/themes.php?theme=' . $slug );?>" title="<?php _e( 'Go to Themes', 'chld_thm_cfg' ); ?>" class="ctc-live-preview">
    <?php _e( 'Not Network Enabled', 'chld_thm_cfg' );?>
    </a><?php endif; ?></div>
</div>
