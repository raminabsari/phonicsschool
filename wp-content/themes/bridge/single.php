<?php

$id = get_the_ID();

$chosen_sidebar = get_post_meta(get_the_ID(), "qode_show-sidebar", true);
$default_array = array('default', '');

if(!in_array($chosen_sidebar, $default_array)){
	$sidebar = get_post_meta(get_the_ID(), "qode_show-sidebar", true);
}else{
	$sidebar = $qode_options_proya['blog_single_sidebar'];
}

$blog_hide_comments = "";
if (isset($qode_options_proya['blog_hide_comments']))
	$blog_hide_comments = $qode_options_proya['blog_hide_comments'];

if(get_post_meta($id, "qode_page_background_color", true) != ""){
	$background_color = get_post_meta($id, "qode_page_background_color", true);
}else{
	$background_color = "";
}

$content_style_spacing = "";
if(get_post_meta($id, "qode_margin_after_title", true) != ""){
	if(get_post_meta($id, "qode_margin_after_title_mobile", true) == 'yes'){
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px !important";
	}else{
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px";
	}
}

?>
<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
				<?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
					<script>
					var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
					</script>
				<?php } ?>
					<?php get_template_part( 'title' ); ?>
				<?php
				$revslider = get_post_meta($id, "qode_revolution-slider", true);
				if (!empty($revslider)){ ?>
					<div class="q_slider"><div class="q_slider_inner">
					<?php echo do_shortcode($revslider); ?>
					</div></div>
				<?php
				}
				?>
				<div class="container"<?php if($background_color != "") { echo " style='background-color:". $background_color ."'";} ?>>
                    <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
                        <div class="overlapping_content"><div class="overlapping_content_inner">
                    <?php } ?>
					<div class="container_inner default_template_holder" <?php qode_inline_style($content_style_spacing); ?>>
				
					<?php if(($sidebar == "default")||($sidebar == "")) : ?>
						<div class="blog_holder blog_single">
						<?php 
							get_template_part('templates/blog_single', 'loop');
						?>
						<?php
							if($blog_hide_comments != "yes"){
								comments_template('', true); 
							}else{
								echo "<br/><br/>";
							}
						?> 
						
					<?php elseif($sidebar == "1" || $sidebar == "2"): ?>
						<?php if($sidebar == "1") : ?>	
							<div class="two_columns_66_33 background_color_sidebar grid2 clearfix">
							<div class="column1">
						<?php elseif($sidebar == "2") : ?>	
							<div class="two_columns_75_25 background_color_sidebar grid2 clearfix">
								<div class="column1">
						<?php endif; ?>
					
									<div class="column_inner">
										<div class="blog_holder blog_single">	
											<?php 
												get_template_part('templates/blog_single', 'loop');
											?>
										</div>
										
										<?php
											if($blog_hide_comments != "yes"){
												comments_template('', true); 
											}else{
												echo "<br/><br/>";
											}
										?> 
									</div>
								</div>	
								<div class="column2"> 
									<?php get_sidebar(); ?>
								</div>
							</div>
						<?php elseif($sidebar == "3" || $sidebar == "4"): ?>
							<?php if($sidebar == "3") : ?>	
								<div class="two_columns_33_66 background_color_sidebar grid2 clearfix">
								<div class="column1"> 
									<?php get_sidebar(); ?>
								</div>
								<div class="column2">
							<?php elseif($sidebar == "4") : ?>	
								<div class="two_columns_25_75 background_color_sidebar grid2 clearfix">
									<div class="column1"> 
										<?php get_sidebar(); ?>
									</div>
									<div class="column2">
							<?php endif; ?>
							
										<div class="column_inner">
											<div class="blog_holder blog_single">	
												<?php 
													get_template_part('templates/blog_single', 'loop');
												?>
											</div>
											<?php
												if($blog_hide_comments != "yes"){
													comments_template('', true); 
												}else{
													echo "<br/><br/>";
												}
											?> 
										</div>
									</div>	
									
								</div>
						<?php endif; ?>
					</div>
				</div>
                <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
                    </div></div>
                <?php } ?>
			</div>						
<?php endwhile; ?>
<?php endif; ?>	


<?php get_footer(); ?>	