<?php
if ( !defined( 'ABSPATH' ) ) exit;
// Additional stylesheets
?><br/>
<?php
    foreach ( $this->get_files( $this->css->get_prop( 'child' ), 'backup' ) as $backup => $label ): ?>
          <label>
            <input class="ctc_checkbox" id="ctc_revert_<?php echo $backup; ?>" name="ctc_revert" type="radio" 
                value="<?php echo $backup; ?>" />
            <?php echo __( 'Restore backup from', 'chld_thm_cfg' ) . ' ' . $label; ?></label>
          <br/>
          <?php endforeach;