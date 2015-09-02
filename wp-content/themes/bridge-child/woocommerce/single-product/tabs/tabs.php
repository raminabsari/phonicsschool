<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 *
 * NOTE: Updated template merged manually with outdated template in Bridge master theme
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

    <div class="q_accordion_holder toggle boxed woocommerce-accordion">
        <?php foreach ( $tabs as $key => $tab ) : ?>

            <h6 class="title-holder clearfix <?php echo $key ?>_tab">
                <span class="tab-title"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></span>
            </h6>
            <div class="accordion_content">
                <div class="accordion_content_inner">
                    <?php call_user_func( $tab['callback'], $key, $tab ) ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

<?php endif; ?>
