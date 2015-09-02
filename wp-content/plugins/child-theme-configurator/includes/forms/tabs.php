<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Tabs Bar

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'parent_child_options'; 
?>

<h2 class="nav-tab-wrapper clearfix">
<a id="parent_child_options" href="" 
                    class="nav-tab<?php echo 'parent_child_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Parent/Child', 'chld_thm_cfg' ); ?>
</a>
<?php if ( $enqueueset ): ?>
<!----><a id="query_selector_options" href="" 
                    class="nav-tab<?php echo 'query_selector_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Query/Selector', 'chld_thm_cfg' ); ?>
</a><!----><a id="rule_value_options" href="" 
                    class="nav-tab<?php echo 'rule_value_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Property/Value', 'chld_thm_cfg' ); ?>
</a><!----><a id="import_options" href="" 
                    class="nav-tab<?php echo 'import_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Web Fonts', 'chld_thm_cfg' ); ?>
</a><!----><a id="view_child_options" href="" 
                    class="nav-tab<?php echo 'view_child_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Child CSS', 'chld_thm_cfg' ); ?>
</a><!----><a id="view_parnt_options" href="" 
                    class="nav-tab<?php echo 'view_parnt_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Parent CSS', 'chld_thm_cfg' ); ?>
</a>
<?php 
    if ( '' == $hidechild ):  
    ?>
<a id="file_options" href="" class="nav-tab<?php echo 'file_options' == $active_tab ? ' nav-tab-active' : ''; ?>" <?php echo $hidechild; ?>>
<?php _e( 'Files', 'chld_thm_cfg' ); ?>
</a>
<?php 
    endif; 
do_action( 'chld_thm_cfg_tabs', $this->ctc(), $active_tab, $hidechild );
endif; ?>
  <i id="ctc_status_preview"></i>
</h2>