<?php
/**
 * Post Card
 *
 * @package     Post Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
class PEAW_Single_Post_By_ID extends WP_Widget{
	public function __construct(){
		$base_id 			= "PEAW_Single_Post_By_ID";
		$widget_name 		= 'Post Preview Card:' . __(' Single Post by ID' , PEAW_TEXT_DOMAIN);
		$sidebar_options 	= [
			'classname' 					=> 'peaw_single_post_by_id',
			'description'					=> __('Preview single post by given id.', PEAW_TEXT_DOMAIN),
			'customize_selective_refresh' 	=> true,
		];

		parent::__construct($base_id,$widget_name,$sidebar_options);
		$this->alt_option_name = "peaw_single_post_by_id";

		/* Register Styles and Scripts but don't Enqueue. */
		wp_register_style( 'bootstrap-v4', PEAW_URI . 'public/css/bootstrap.css' );
		wp_register_style( 'peaw-post-preview-card', PEAW_URI . 'public/css/post-preview-card.css' );
	}
	
	public function widget($args, $instance){
		/* Enqueue registered Styles and Scripts here. This way style and Script are only enqueued if widget is on page */
		wp_enqueue_style('bootstrap-v4');
		wp_enqueue_style('peaw-post-preview-card');

		/*
		 *	Get the data from the Widget Form. 
		 *	post_id checks if there's a id set, otherwise set as an invalid_post_id status
		 *	call_text checks it is set and not empty. If it is, set it to a empty string
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
		  			<i class="fa fa-clock-o"></i>
		  		</span>
		  		<?php echo esc_html($publish_date); ?> in <?php echo $category_output; ?>

		  	</p>

		    <h4 class="card-title"><?php echo esc_html($post_title); ?></h4>

		    <p class="peaw-call-text"><?php echo esc_html($call_text); ?></p>

		    <a href="<?php esc_attr($post_link); ?>" class=" peaw-read-more">
		    
		    	<?php esc_html_e('Read More', PEAW_TEXT_DOMAIN); ?>

		    	<span class="peaw-read-more-span">

		    		<i class="fa fa-arrow-right"></i>
		    		
		    	</span>

		    </a>

		  </div>

		</div>
	<?php
		echo $args['after_widget'];
	}


	public function update($new_instance, $old_instance){
		//If no old instance ever existed we return an empty array
		$instance = isset($old_instance) ? $old_instance : array();

		/* Checks if post_id is not empty and it is an integer */
		if(!empty($new_instance['post_id']) && is_int((int)$new_instance['post_id'])){
			$instance['post_id'] = $new_instance['post_id'];
		}else{
			// If not set or not integer, set as Invalid Post ID
			$instance['post_id'] = 'Invalid_Post_ID';
		}

		//Check if user inserted a call_text
		if(!empty($new_instance['call_text'])){
			$instance['call_text'] = sanitize_text_field($new_instance['call_text']);

		}else{
			$instance['call_text'] = '';
		}

		return $instance;
	}

	public function form($instance){
		$post_id 	= isset($instance['post_id']) ? esc_attr($instance['post_id'] ) : 0 ;
		$call_text = ! empty( $instance['call_text'] ) ? esc_attr($instance['call_text']) : esc_html__( 'New call_text', PEAW_TEXT_DOMAIN );
	?>
		<p><label for="<?php echo esc_attr($this->get_field_id('post_id')); ?>">
			<?php esc_html_e('Post ID', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_id')); ?>" type="number" name="<?php echo esc_attr($this->get_field_name('post_id')); ?>" value="<?php echo esc_attr($post_id); ?>" >

		<p><label for="<?php echo esc_attr($this->get_field_id( 'call_text' )); ?>">
			<?php esc_attr_e( 'call text:', PEAW_TEXT_DOMAIN ); ?>		
		</label></p> 
		<input class="widefat" id="<?php echo  esc_attr( $this->get_field_id( 'call_text' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'call_text' )); ?>" type="text" value="<?php echo esc_attr($call_text); ?>">
		<p class="random-post-call-text-notice"><?php esc_html_e('Optional: Call text is by default the Post\'s Excerpt', PEAW_TEXT_DOMAIN); ?></p>
	<?php
	}

}