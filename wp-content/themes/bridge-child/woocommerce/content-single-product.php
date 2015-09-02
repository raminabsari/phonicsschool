<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php

// Only show if not on the shop home page
if (!is_page('store')) {
    echo do_shortcode('[ps_shop_nav]');
}

?>

<?php
    /**
     * woocommerce_before_single_product hook
     *
     * @hooked wc_print_notices - 10
     */
     do_action( 'woocommerce_before_single_product' );

     if ( post_password_required() ) {
        echo get_the_password_form();
        return;
     }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php $hide_cart = get_post_meta($post->ID, "hide-cart", true); ?>
<!-- Added 'container_inner' class here for responsiveness and disabled parent .container_inner responsiveness in shop.less -->
<!-- This means the ps-shop-nav can be full page width -->
<div class="product-wrapper container_inner<?php if($hide_cart){ echo ' hide-cart'; } ?>">

    <?php
        /**
         * woocommerce_before_single_product_summary hook
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product_summary' );
    ?>


    <div class="summary entry-summary">

            <div class="clearfix">

        <?php
            /**
             * woocommerce_single_product_summary hook
             *
             * @hooked woocommerce_template_single_title - 5
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             */
            do_action( 'woocommerce_single_product_summary' );
        ?>

    </div><!-- .summary -->

    <div class="images">
        <?php do_action( 'woocommerce_product_thumbnails' ); ?>
    </div>

    <?php
        /**
         * woocommerce_after_single_product_summary hook
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
    ?>

</div>

    <meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>