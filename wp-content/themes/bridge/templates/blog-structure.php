<?php 
	global $wp_query;
	global $qode_options_proya;
    global $qode_template_name;
	$id = $wp_query->get_queried_object_id();

	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
	elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
	else { $paged = 1; }

	if(isset($qode_options_proya['blog_page_range']) && $qode_options_proya['blog_page_range'] != ""){
		$blog_page_range = $qode_options_proya['blog_page_range'];
	} else{
		$blog_page_range = $wp_query->max_num_pages;
	}
	
	$filter = "no";
	if(isset($qode_options_proya['blog_masonry_filter'])){
		$filter = $qode_options_proya['blog_masonry_filter'];
	}

    $blog_style = "1";
    if(isset($qode_options_proya['blog_style'])){
        $blog_style = $qode_options_proya['blog_style'];
    }

	$blog_list = "";
	if($qode_template_name != "") {
		if($qode_template_name == "blog-large-image.php"){
			$blog_list = "blog_large_image";
			$blog_list_class = "blog_large_image";
		}elseif($qode_template_name == "blog-masonry.php"){
			$blog_list = "blog_masonry";	
			$blog_list_class = "masonry";
		}elseif($qode_template_name == "blog-masonry-full-width.php"){
			$blog_list = "blog_masonry";	
			$blog_list_class = "masonry_full_width";				
		}elseif($qode_template_name == "blog-masonry-date-in-image.php"){
			$blog_list = "blog_masonry_date_in_image";
			$blog_list_class = "masonry blog_masonry_date_in_image";
		}elseif($qode_template_name == "blog-masonry-full-width-date-in-image.php"){
			$blog_list = "blog_masonry_date_in_image";
			$blog_list_class = "masonry_full_width blog_masonry_date_in_image";
		}elseif($qode_template_name == "blog-large-image-whole-post.php"){
			$blog_list = "blog_large_image_whole_post";	
			$blog_list_class = "blog_large_image";	
		}elseif($qode_template_name == "blog-small-image.php"){
			$blog_list = "blog_small_image";
			$blog_list_class = "blog_small_image";
		}elseif($qode_template_name == "blog-large-image-simple.php"){
			$blog_list = "blog_large_image_simple";
			$blog_list_class = "blog_large_image_simple";
		}elseif($qode_template_name == "blog-large-image-with-dividers.php"){
			$blog_list = "blog_large_image_with_dividers";
			$blog_list_class = "blog_large_image_with_dividers";
		}else{
			$blog_list = "blog_large_image";
			$blog_list_class = "blog_large_image";
		}
	} else{
		if($blog_style=="1"){
			$blog_list = "blog_large_image";
			$blog_list_class = "blog_large_image";
		}elseif($blog_style=="2"){
			$blog_list = "blog_masonry";	
			$blog_list_class = "masonry";	
		}elseif($blog_style=="5"){
			$blog_list = "blog_masonry";	
			$blog_list_class = "masonry_full_width";	
		}elseif($blog_style=="3"){
			$blog_list = "blog_large_image_whole_post";	
			$blog_list_class = "blog_large_image";	
		}elseif($blog_style=="4"){
			$blog_list = "blog_small_image";
			$blog_list_class = "blog_small_image";
		}elseif($blog_style=="6"){
			$blog_list = "blog_large_image_simple";
			$blog_list_class = "blog_large_image_simple";
		}elseif($blog_style=="7"){
			$blog_list = "blog_large_image_with_dividers";
			$blog_list_class = "blog_large_image_with_dividers";
		}elseif($blog_style=="8"){
			$blog_list = "blog_masonry_date_in_image";
			$blog_list_class = "masonry blog_masonry_date_in_image";
		}else {
			$blog_list = "blog_large_image";
			$blog_list_class = "blog_large_image";
		}
		
	}
	

    $pagination_masonry = "pagination";
    if(isset($qode_options_proya['pagination_masonry'])){
       $pagination_masonry = $qode_options_proya['pagination_masonry'];
		if($blog_list == "blog_masonry" || $blog_list == "blog_masonry_date_in_image") {
			$blog_list_class .= " masonry_" . $pagination_masonry;
		}
    }
?>
<?php 
	if(($blog_list == "blog_masonry" || $blog_list == "blog_masonry_date_in_image") && $filter == "yes") { 
		get_template_part('templates/masonry', 'filter');
		
	}
	 
	

?>
<div class="blog_holder <?php echo $blog_list_class; ?>">
	<?php
	if($blog_list == 'blog_masonry' || $blog_list == 'blog_masonry_date_in_image'){ ?>
		<div class="blog_holder_grid_sizer"></div>
		<div class="blog_holder_grid_gutter"></div>
	<?php }	?>
	<?php if(have_posts()) : while ( have_posts() ) : the_post(); ?>
		<?php 
			get_template_part('templates/'.$blog_list, 'loop');
		?>
	<?php endwhile; ?>
	<?php if($blog_list != "blog_masonry" && $blog_list != "blog_masonry_date_in_image") { ?>
		<?php if(isset($qode_options_proya['pagination']) && $qode_options_proya['pagination'] != "0") : ?>
			<?php pagination($wp_query->max_num_pages, $blog_page_range, $paged); ?>
		<?php endif; ?>
	<?php } ?>
	<?php else: //If no posts are present ?>
	<div class="entry">                        
			<p><?php _e('No posts were found.', 'qode'); ?></p>    
	</div>
	<?php endif; ?>
</div>
<?php if($blog_list == "blog_masonry" || $blog_list == "blog_masonry_date_in_image") { 
    if($pagination_masonry == "load_more") { 
		if (get_next_posts_link()) { ?>
			<div class="blog_load_more_button_holder">
				<div class="blog_load_more_button"><span rel="<?php echo $wp_query->max_num_pages; ?>"><?php echo get_next_posts_link(__('Show more', 'qode')); ?></span></div>
				<div class="blog_load_more_button_loading"><a href="javascript: void(0)" class="qbutton"><?php _e('Loading...', 'qode'); ?></a></div>
			</div>
		<?php } ?>
	 <?php } elseif($pagination_masonry == "infinite_scroll") { ?>
		<div class="blog_infinite_scroll_button"><span rel="<?php echo $wp_query->max_num_pages; ?>"><?php echo get_next_posts_link(__('Show more', 'qode')); ?></span></div>
	<?php }else { ?>
		<?php if($qode_options_proya['pagination'] != "0") : ?>
			<?php pagination($wp_query->max_num_pages, $blog_page_range, $paged); ?>
		<?php endif; ?>
	<?php } ?>
<?php } ?>