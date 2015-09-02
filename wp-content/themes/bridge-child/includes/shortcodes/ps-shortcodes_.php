<?php

if (!function_exists('ps_hero_watermarks')) {

    function ps_hero_watermarks($atts, $content = null) {
        $args = array(
            "watermark_left_text" => "",
            "watermark_left_dark" => "",
            "watermark_left_light" => "",
            "watermark_right_text" => "",
            "watermark_right_dark" => "",
            "watermark_right_light" => ""
        );

        extract(shortcode_atts($args, $atts));

        $watermark_left_text = esc_attr($watermark_left_text);
        $watermark_left_dark = esc_attr($watermark_left_dark);
        $watermark_left_light = esc_attr($watermark_left_light);
        $watermark_right_text = esc_attr($watermark_right_text);
        $watermark_right_dark = esc_attr($watermark_right_dark);
        $watermark_right_light = esc_attr($watermark_right_light);

        $watermark_left_dark = wp_get_attachment_url($watermark_left_dark);
        $watermark_left_light = wp_get_attachment_url($watermark_left_light);
        $watermark_right_dark = wp_get_attachment_url($watermark_right_dark);
        $watermark_right_light = wp_get_attachment_url($watermark_right_light);

        if($content != ""){
            $content = do_shortcode($content);
        }

        ob_start(); ?>

        <div class="hero-watermarks-container grid_section">
          <div class="hero-watermarks-inner section_inner">
            <div class="hero-watermark left-watermark">
              <?php echo $watermark_left_text ?>
              <div class="logo dark" style="background-image: url('<?php echo $watermark_left_dark; ?>');"></div>
              <div class="logo light" style="background-image: url('<?php echo $watermark_left_light; ?>');"></div>
            </div>
            <div class="hero-watermark right-watermark">
              <?php echo $watermark_right_text ?>
              <div class="logo dark" style="background-image: url('<?php echo $watermark_right_dark; ?>');"></div>
              <div class="logo light" style="background-image: url('<?php echo $watermark_right_light; ?>');"></div>
            </div>
          </div>
        </div>

        <?php
        /* Get the buffered content into a var */
        $sc = ob_get_contents();

        /* Clean buffer */
        ob_end_clean();

        /* Return the content as usual */
        return $sc;
    }
}
add_shortcode('ps_hero_watermarks', 'ps_hero_watermarks');


/* PS Introduction Block */

if (!function_exists('ps_intro')) {

    function ps_intro($atts, $content = null) {
        $args = array(
            "scene_1_image_url" => "",
            "scene_2_image_url" => "",
            "intro_lockup_url" => "",
            "intro_watermark_left_text" => "",
            "intro_watermark_left_dark" => "",
            "intro_watermark_left_light" => "",
            "intro_watermark_right_text" => "",
            "intro_watermark_right_dark" => "",
            "intro_watermark_right_light" => "",
            "intro_heading" => "",
            "intro_tagline" => "",
            "intro_description" => ""
        );

        extract(shortcode_atts($args, $atts));

        $scene_1_image_url = esc_attr($scene_1_image_url);
        $scene_2_image_url = esc_attr($scene_2_image_url);
        $intro_lockup_url = esc_attr($intro_lockup_url);
        $intro_watermark_left_text = esc_attr($intro_watermark_left_text);
        $intro_watermark_left_dark = esc_attr($intro_watermark_left_dark);
        $intro_watermark_left_light = esc_attr($intro_watermark_left_light);
        $intro_watermark_right_text = esc_attr($intro_watermark_right_text);
        $intro_watermark_right_dark = esc_attr($intro_watermark_right_dark);
        $intro_watermark_right_light = esc_attr($intro_watermark_right_light);
        $intro_heading = esc_attr($intro_heading);
        $intro_tagline = esc_attr($intro_tagline);
        $intro_description = esc_attr($intro_description);

        $scene_1_image_url = wp_get_attachment_url($scene_1_image_url);
        $scene_2_image_url = wp_get_attachment_url($scene_2_image_url);
        $intro_lockup_url = wp_get_attachment_url($intro_lockup_url);

        $watermarks = do_shortcode('[ps_hero_watermarks watermark_left_text="'. $intro_watermark_left_text .'" watermark_left_dark="'. $intro_watermark_left_dark .'" watermark_left_light="'. $intro_watermark_left_light .'" watermark_right_text="'. $intro_watermark_right_text .'" watermark_right_dark="'. $intro_watermark_right_dark .'" watermark_right_light="'. $intro_watermark_right_light .'"]');

        if($content != ""){
            $content = do_shortcode($content);
        }

        ob_start(); ?>

        <article class="section-intro-container">
            <section class="section-intro-fallback">
              <div class="intro-fallback-image"></div>
              <div class="section-intro-fallback-inner">
                <div class="intro-overlay"></div>
                <div class="intro-heading-container">
                  <div class="intro-heading-inner">
                    <h1><?php echo $intro_heading; ?></h1>
                    <div class="intro-sub-heading">
                      <?php echo $content; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mobile-watermarks-container">
                  <?php echo $watermarks; ?>
              </div>
            </section>
            <section class="section-intro">
              <div class="intro-content">
                <div class="intro-content-inner">
                  <div class="intro-heading-container">
                    <div class="intro-heading-inner">
                      <h1><?php echo $intro_heading; ?></h1>
                      <div class="intro-sub-heading">
                        <?php echo $content; ?>
                      </div>
                    </div>
                  </div>
                  <div class="intro-product section-content">
                    <div class="intro-title">
                        <h1 class="intro-ps-logo"></h1>
                        <h4 class="intro-tagline"><?php echo $intro_tagline; ?></h4>
                        <p class="intro-description"><?php echo $intro_description; ?></p>
                        <div class="intro-links">
                            <div class="intro-link-left watch-episode-link">Watch an episode</div
                           ><a href="/store" target="_self" class="qbutton medium center intro-shop-link intro-link-right">Visit Shop</a>
                        </div>
                    </div>
                  </div>
                  <div class="intro-main-image">
                    <div class="intro-tv-container">
                      <div class="intro-scene-container">
                        <canvas class="intro-scene intro-scene-start" data-image-url="<?php echo $scene_1_image_url; ?>"></canvas>
                        <canvas class="intro-scene intro-scene-end" data-image-url="<?php echo $scene_2_image_url; ?>"></canvas>
                      </div>
                    </div>
                    <div class="intro-family"></div>
                  </div>
                  <div class="intro-overlay"></div>
                </div>
              </div>
            </section>

            <section class="section-intro-lockup">
              <div class="intro-product section-content">
                <div class="intro-title">
                    <h1 class="intro-ps-logo"></h1>
                    <h4 class="intro-tagline"><?php echo $intro_tagline; ?></h4>
                    <p class="intro-description"><?php echo $intro_description; ?></p>
                    <div class="intro-links">
                        <div class="intro-link-left watch-episode-link">Watch an episode</div
                       ><a href="/store" target="_self" class="qbutton medium center intro-shop-link intro-link-right">Visit Shop</a>
                    </div>
                </div>
                <div class="intro-fallback">
                  <figure class="intro-fallback-image" style="background-image: url('<?php echo $intro_lockup_url; ?>');"></figure>
                </div>
              </div>
            </section>

            <div class="pinned-watermarks-container">
                <?php echo $watermarks; ?>
            </div>

            <div class="scroll-cta-container intro-end-scroll-cta">
              <a href="#intro-summary-section" class="scroll-cta" id="intro-end-scroll-cta">Learn more&hellip;<span></span></a>
            </div>

            <div class="scroll-cta-container intro-start-scroll-cta">
              <a href="#" class="scroll-cta" id="intro-start-scroll-cta">Scroll<span></span></a>
            </div>
        </article>

        <script>
            jQuery(document).ready(function($) {
                initIntro();
            });
        </script>

        <?php
        /* Get the buffered content into a var */
        $sc = ob_get_contents();

        /* Clean buffer */
        ob_end_clean();

        /* Return the content as usual */
        return $sc;
    }
}
add_shortcode('ps_intro', 'ps_intro');


if (!function_exists('ps_shop_nav')) {

    function ps_shop_nav($atts, $content = null) {

        ob_start(); ?>

        <div class="ps-shop-nav hide-cycle">
            <div class="ps-shop-nav-inner">
                <div class="ps-shop-nav-scroll">
                    <ul class="ps-products-nav">
                    <?php
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 10,
                            'product_cat' => 'phonics-school-products',
                            'orderby' => 'date',
                            'order' => 'ASC'
                        );

                        $loop = new WP_Query( $args );

                        while ( $loop->have_posts() ) : $loop->the_post();
                            global $product;

                            // Use woocommerce_get_product_thumbnail() for featured image
                            echo '<li class="ps-product-nav-item" data-product-id="'.get_the_ID().'">';
                                echo '<a href="'.get_permalink().'">';
                                echo types_render_field( "product-icon-dark", array( "alt" => get_the_title() ) );
                                echo '<span class="ps-product-title">'.get_the_title().'</span></a>';
                            echo '</li>';

                        endwhile;

                        wp_reset_query();
                    ?>
                    </ul>
                </div>
                <div class="button-cycle button-left"></div>
                <div class="button-cycle button-right"></div>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                initShopNav();
            });
        </script>

        <?php
        /* Get the buffered content into a var */
        $sc = ob_get_contents();

        /* Clean buffer */
        ob_end_clean();

        /* Return the content as usual */
        return $sc;
    }
}
add_shortcode('ps_shop_nav', 'ps_shop_nav');


?>
