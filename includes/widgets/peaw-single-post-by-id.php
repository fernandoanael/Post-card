<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
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

	}
	
	public function widget($args, $instance){
		$peaw_widget = new Peaw_Widget;

		/*
		 *	Get the data from the Widget Form. 
		 *	post_id checks if there's an id set, otherwise set as an invalid_post_id status
		 *	call_text checks if it is set and not empty.
		 */
		$post_id 	= isset($instance['post_id']) ? $instance['post_id']: 'Invalid_Post_ID';
		$call_text 	= isset($instance['call_text']) && !empty($instance['call_text']) ? sanitize_text_field($instance['call_text']) : ''; 
		$excerpt_length = isset($instance['excerpt_length']) && !empty($instance['excerpt_length'])  ? $instance['excerpt_length'] : 85;
		

		/*
		 *	If post id not Invalid and there's a post with the given ID, let's set all the variables
		 */
		if($post_id !== 'Invalid_Post_ID' && get_post($post_id)){
			$peaw_post 	= get_post($post_id);
			$peaw_widget->post_title = $peaw_post->post_title;
			$peaw_widget->publish_date = get_the_date('F j, Y',$post_id);
			
			//get the category link for each post category.
			$categories = get_the_category($post_id);
			$peaw_widget->category_output = '';
			foreach ($categories as $category) {
				$cat_link = get_category_link( $category->term_id);
				$peaw_widget->category_output .=  "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
			}

			//get post link
			$peaw_widget->post_link = get_post_permalink($post_id);

			//Get Post featured image or default image
			if(has_post_thumbnail($post_id)){

				$peaw_widget->image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
				$peaw_widget->image = $peaw_widget->image[0];

			}else{
				$peaw_widget->image = PEAW_URI . 'public/images/image-not-found.png'; 
			}

			//If user don't want to use the call text, it will become the post excerpt
			if(empty($call_text)){
				$peaw_widget->call_text = strlen($peaw_post->post_content) > $excerpt_length ? substr(strip_tags($peaw_post->post_content), 0, $excerpt_length).'(...)' : strip_tags($peaw_post->post_content) ;
			}else{
				$peaw_widget->call_text = $call_text;
			}
		
		}else{
			//Create error message to display as a post card
			$peaw_widget->post_title = __('Something is wrong', PEAW_TEXT_DOMAIN);
			$peaw_widget->publish_date = __('date not found', PEAW_TEXT_DOMAIN);
			$peaw_widget->category_output = "<a class='peaw-category-link' href='#'>".__('Category not found', PEAW_TEXT_DOMAIN)."</a>";
			$peaw_widget->post_link = "#";
			$peaw_widget->call_text = __('This can be caused by Invalid ID. Go to the All Posts section in your admin page and Pick a valid ID', PEAW_TEXT_DOMAIN);
			$peaw_widget->image = PEAW_URI . 'public/images/image-not-found.png';
		}
		Peaw_Layouts_Manager::peaw_layout_render($args,$instance,$peaw_widget);
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

		/*Mutual Instance Begin*/
		if(!empty($new_instance['layout_selected'])){
			$instance['layout_selected'] = $new_instance['layout_selected'];
		}else{
			$instance['layout_selected'] = null;
		}

		if(!empty($new_instance['full_type_selected'])){
			$instance['full_type_selected'] = $new_instance['full_type_selected'];
		}else{
			$instance['full_type_selected'] = null;
		}

		if(!empty($new_instance['font_size']) && is_int((int)$new_instance['font_size'])){
			$instance['font_size'] = $new_instance['font_size'];
		}else{
			$instance['font_size'] = null;
		}

		if(!empty($new_instance['excerpt_length']) && is_int((int)$new_instance['excerpt_length'])){
			$instance['excerpt_length'] = $new_instance['excerpt_length'];
		}else{
			$instance['excerpt_length'] = null;
		}

		if(!empty($new_instance['read_more_text'])){
			$instance['read_more_text'] = sanitize_text_field($new_instance['read_more_text']);

		}else{
			$instance['read_more_text'] = '';
		}
		/* Mutual Form instance End */
		return $instance;
	}

	public function form($instance){
		$post_id 	= isset($instance['post_id']) ? esc_attr($instance['post_id'] ) : 0 ;
		$call_text = ! empty( $instance['call_text'] ) ? esc_attr($instance['call_text']) : esc_html__( 'New call_text', PEAW_TEXT_DOMAIN );
		$layout_selected = !empty($instance['layout_selected']) ? esc_attr($instance['layout_selected']) : null;
		$layouts_list = Peaw_Layouts_Manager::peaw_get_layouts_list();
		$full_type_selected = !empty($instance['full_type_selected']) ? esc_attr($instance['full_type_selected']) : null;
		$font_size = !empty($instance['font_size']) ? esc_attr($instance['font_size']) : null;
		$excerpt_length = !empty($instance['excerpt_length']) ? esc_attr($instance['excerpt_length']) : null;

		$read_more_text = ! empty( $instance['read_more_text'] ) ? esc_attr($instance['read_more_text']) : esc_html__( 'Read More', PEAW_TEXT_DOMAIN );

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
		<hr>
		<!-- Mutual Plugin Area Begin -->
		<p><label for="<?php echo esc_attr($this->get_field_id('layout_selected')); ?>">
			<?php esc_html_e('Select Layout', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<select id="<?php echo  esc_attr( $this->get_field_id( 'layout_selected' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'layout_selected' )); ?>">
			<?php 
			foreach ($layouts_list as $layout => $value) { 
				if($layout == $layout_selected){
					$selected = 'selected';
				}else{
					$selected = '';
				}
			?>
				<option value="<?php echo esc_attr($layout) ?>" selected="<?php echo $selected; ?>"><?php echo $layouts_list[$layout]['layout_name']; ?></option>
			<?php } ?>
		</select>

		<p><label for="<?php echo esc_attr($this->get_field_id('full_type_selected')); ?>">
			<?php esc_html_e('Select Full Display Type', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<select id="<?php echo  esc_attr( $this->get_field_id( 'full_type_selected' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'full_type_selected' )); ?>">
				<?php 
				if($full_type_selected == 'single_page'){
					$selected = 'selected';
				}else{
					$selected = '';
				}
				?>
					<option value="single_page" selected="<?php echo $selected; ?>">Single Post Page</option>
				<?php 
				if($full_type_selected == 'modal'){
					$selected = 'selected';
				}else{
					$selected = '';
				}
				?>
					<option value="modal" selected="<?php echo $selected; ?>">Modal Javascript</option>
		</select>

		

		<p><label for="<?php echo esc_attr($this->get_field_id('font_size')); ?>">
			<?php esc_html_e('Font Size', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" style="width: 50px;" id="<?php echo  esc_attr( $this->get_field_id( 'font_size' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'font_size' )); ?>" type="number" min="5" max="50" value="<?php echo esc_attr($font_size); ?>"> Pixels

		<p><label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>">
			<?php esc_html_e('Excerpt Length', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" style="width: 50px;" id="<?php echo  esc_attr( $this->get_field_id( 'excerpt_length' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'excerpt_length' )); ?>" type="number" min="30" max="120" value="<?php echo esc_attr($excerpt_length); ?>"> Letters

		<p><label for="<?php echo esc_attr($this->get_field_id( 'read_more_text' )); ?>">
			<?php esc_attr_e( 'Read More Button Text:', PEAW_TEXT_DOMAIN ); ?>		
		</label></p> 
		<input class="widefat" id="<?php echo  esc_attr( $this->get_field_id( 'read_more_text' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'read_more_text' )); ?>" type="text" value="<?php echo esc_attr($read_more_text); ?>">
		<!--Mutual Form End -->
	<?php
	}

}