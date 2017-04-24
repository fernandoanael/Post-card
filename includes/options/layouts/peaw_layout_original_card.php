<?php
class Peaw_Layout_Original_Card implements Peaw_Layouts_Base{

	public function __construct($args, $instance){
		$this->peaw_layout_render($args,$instance);
	}

	public function peaw_layout_render(array $args, array $instance){
		/* Enqueue registered Styles and Scripts here. This way style and Script are only enqueued if widget is on page */
		wp_enqueue_style('bootstrap-v4');
		wp_enqueue_style('peaw-post-preview-card');

		/*
		 *	Get the data from the Widget Form. 
		 *	post_id checks if there's an id set, otherwise set as an invalid_post_id status
		 *	call_text checks if it is set and not empty.
		 */
		$post_id 	= isset($instance['post_id']) ? $instance['post_id']: 'Invalid_Post_ID';
		$call_text 	= isset($instance['call_text']) && !empty($instance['call_text']) ? sanitize_text_field($instance['call_text']) : ''; 

		/*
		 *	If post id not Invalid and there's a post with the given ID, let's set all the variables
		 */
		if($post_id !== 'Invalid_Post_ID' && get_post($post_id)){
			$peaw_post 	= get_post($post_id);
			$post_title = $peaw_post->post_title;
			$publish_date = get_the_date('F j, Y',$post_id);
			
			//get the category link for each post category.
			$categories = get_the_category($post_id);
			$category_output = '';
			foreach ($categories as $category) {
				$cat_link = get_category_link( $category->term_id);
				$category_output .=  "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
			}

			//get post link
			$post_link = get_post_permalink($post_id);

			//Get Post featured image or default image
			if(has_post_thumbnail($post_id)){

				$img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
				$img = $img[0];

			}else{
				$img = PEAW_URI . 'public/images/image-not-found.png'; 
			}

			//If user don't want to use the call text, it will become the post excerpt
			if(empty($call_text)){
				$call_text = $peaw_post->post_content > 85 ? substr(strip_tags($peaw_post->post_content), 0, 85).'(...)' : strip_tags($peaw_post->post_content) ;
			}
		
		}else{
			//Create error message to display as a post card
			$post_title = __('Something is wrong', PEAW_TEXT_DOMAIN);
			$publish_date = __('date not found', PEAW_TEXT_DOMAIN);
			$category_output = "<a class='peaw-category-link' href='#'>".__('Category not found', PEAW_TEXT_DOMAIN)."</a>";
			$post_link = "#";
			$call_text = __('This can be caused by Invalid ID. Go to the All Posts section in your admin page and Pick a valid ID', PEAW_TEXT_DOMAIN);
			$img = PEAW_URI . 'public/images/image-not-found.png';
		}


		echo $args['before_widget'];	
		//Render widget
	?>
		<div class="card" style="width: 22rem;">

			<img src="<?php echo esc_attr($img); ?>" width="480" height="270">
			<div class="card-block">
		  		<p class="card-text">
		  		<span class="peaw-info-span">
		  			<i class="dashicons dashicons-clock"></i>
		  		</span>
		  		<?php echo esc_html($publish_date); ?> in <?php echo $category_output; ?>

		  	</p>

		    <h4 class="card-title"><?php echo esc_html($post_title); ?></h4>

		    <p class="peaw-call-text"><?php echo esc_html($call_text); ?></p>

		    <a href="<?php esc_attr($post_link); ?>" class=" peaw-read-more">
		    
		    	<?php esc_html_e('Read More', PEAW_TEXT_DOMAIN); ?>

		    	<span class="peaw-read-more-span">

		    		<i class="dashicons dashicons-arrow-right-alt2"></i>
		    		
		    	</span>

		    </a>

		  </div>

		</div>
	<?php
		echo $args['after_widget'];
	}
 
}