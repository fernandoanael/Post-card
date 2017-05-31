<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
 */
class PEAW_Random_Post_By_Category extends WP_Widget{
	public function __construct(){
		$base_id 			= "PEAW_Random_Post_By_Category";
		$widget_name 		= 'Post Preview Card:' . __(' Random Post By Category' , PEAW_TEXT_DOMAIN);
		$sidebar_options 	= [
			'classname' 					=> 'peaw_random_post_by_category',
			'description'					=> __('Preview random post by given category', PEAW_TEXT_DOMAIN),
			'customize_selective_refresh' 	=> true,
		];

		parent::__construct($base_id,$widget_name,$sidebar_options);
		$this->alt_option_name = "peaw_random_post_by_category";

	}

	public function widget($args,$instance){
		$peaw_widget = new Peaw_Widget;
		
		/* Check if Category is set, not null, really exists and has at least 1 post assigned to it.*/
		if(isset($instance['category']) && !is_null($instance['category']) && term_exists(get_cat_name($instance['category'])) && $this->peaw_is_cat_empty($instance['category']) == false){

			$category_id 		= $instance['category'];
			
			//Set 1 Random post per category 
			$posts_query_args 	= array(
				'posts_per_page'	=> 1,
				'category'			=> $category_id,
				'orderby'			=> 'rand', 
			);

			//Get the post and set the vars
			$peaw_posts = get_posts($posts_query_args);
			foreach ($peaw_posts as $post) {
				$post_id		= $post->ID;
				$peaw_widget->post_title 	= $post->post_title;
				$peaw_widget->publish_date 	= get_the_date('F j, Y', $post_id);

				/* Check if post has an excerpt, if not generate a post excerpt using the firsts 85 characters of content */
				if(empty($post->post_excerpt)){
					$call_text = strip_tags($post->post_content);
					if(strlen($call_text) > 85){
						$call_text = substr($call_text, 0, 85);
						$call_text = $call_text . '(...)';
					}					
				}else{
					$call_text		= strip_tags($post->post_excerpt);
				}

				$peaw_widget->call_text = $call_text;

				//Get the categorys assigned to this post
				$categories 	= get_the_category($post_id);
				$category_output= '';
				foreach ($categories as $category) {
				 	$cat_link = get_category_link($category->term_id);
				 	$category_output .= "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
				} 

				$peaw_widget->category_output = $category_output;

				 //Get the post link
				$peaw_widget->post_link = get_post_permalink($post_id);

				//If post has thumbnail set it, otherwise, get the default image
				if(has_post_thumbnail($post_id)){

					$image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
					$image = $image[0];

				}else{
					$image = PEAW_URI . 'public/images/image-not-found.png'; 
				}
				$peaw_widget->image = $image;

				/*Read more text*/
				$peaw_widget->read_more_text = !empty($instance['read_more_text']) ? $instance['read_more_text'] : 'Read More';

				/*Font-size*/
				$peaw_widget->font_size = !is_null($instance['font_size']) ? $instance['font_size'] : '';

				/*Passes the instance and args to the peaw_widget*/
				$peaw_widget->instance = $instance;
				$peaw_widget->args = $args;
			}
		
		/* If Category is not set or null or does not exist or doesn't have any post assigned we create an error message  */
		}else{
			//If User didnt set a valid category
			$peaw_widget->post_title 	= __('Something is wrong', PEAW_TEXT_DOMAIN);
			$peaw_widget->publish_date 	= __('Date not found', PEAW_TEXT_DOMAIN);
			$peaw_widget->call_text		= __('This can be caused by invalid category, category with no posts or category recently deleted.', PEAW_TEXT_DOMAIN);
			$peaw_widget->category_output = "<a class='peaw-category-link' href='#'>".
								__('No category', PEAW_TEXT_DOMAIN)."</a>";
			$peaw_widget->post_link = "#";
			$peaw_widget->image = PEAW_URI . 'public/images/image-not-found.png';
			$peaw_widget->read_more_text = 'Read More';
			$peaw_widget->font_size = '';
			$peaw_widget->instance = $instance;
			$peaw_widget->args = $args;
		}
		/*Render the widget*/
		Peaw_Layouts_Manager::peaw_layout_render($peaw_widget);
	}

	public function update($new_instance, $old_instance){
		//If no old instance ever existed we return an empty array
		$instance = isset($old_instance) ? $old_instance : array();

		//Check if category is set, not null, and is Integer
		if(isset($new_instance['category']) && !is_null($new_instance['category']) && is_int((int)$new_instance['category'])){
			$instance['category'] = $new_instance['category'];
		}else{
			//Passes a null value to the category and let the widget function take care of it after
			$instance['category'] = null;
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
		//Check if category is empty, if it is set null to it
		$category = !empty($instance['category']) ? esc_attr($instance['category']) : null ; 

		//Prepare the wp_dropdown_categories to work as I want
		$category_query_args = array(
			'hide_empty' 		=> true,
			'exclude'			=> '1',
			'exclude_tree'		=> '1',
			'show_option_none' 	=>' ',
			'name' 				=> esc_attr($this->get_field_name('category')),
			'id'				=> esc_attr($this->get_field_id('category')),
			'selected' 			=> absint($category),
			'class'				=> 'postform widefat random-post-categoty-selector',//error
		);

		$layout_selected = !empty($instance['layout_selected']) ? esc_attr($instance['layout_selected']) : null;
		$layouts_list = Peaw_Layouts_Manager::peaw_get_layouts_list();//This should be like this, it should be private function
		$full_type_selected = !empty($instance['full_type_selected']) ? esc_attr($instance['full_type_selected']) : null;
		$font_size = !empty($instance['font_size']) ? esc_attr($instance['font_size']) : null;
		$excerpt_length = !empty($instance['excerpt_length']) ? esc_attr($instance['excerpt_length']) : null;

		$read_more_text = ! empty( $instance['read_more_text'] ) ? esc_attr($instance['read_more_text']) : esc_html__( 'Read More', PEAW_TEXT_DOMAIN );
		?>
		
	    <p><label for="<?php echo esc_attr($this->get_field_id( 'category' )); ?>"><?php esc_attr_e( 'Select category', PEAW_TEXT_DOMAIN ); ?>:
	    </label></p>

	    <?php wp_dropdown_categories($category_query_args); ?>
	  	
	  	<p class="random-post-call-text-notice">Call text is the post excerpt and if it is empty, it will be the first 85 characters of the post content.</p>

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
					<option value="single_page" <?php echo $selected; ?>>Single Post Page</option>
				<?php 
				if($full_type_selected == 'modal'){
					$selected = 'selected';
				}else{
					$selected = '';
				}
				?>
					<option value="modal" <?php echo $selected; ?>>Modal Javascript</option>
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

	/* 
	 *	Checks if given category has at least 1 post assigned to it.
	 * 	Returns true if found no posts
	 * 	Returns false if found at least 1 post 
	 */
	public function peaw_is_cat_empty($category){
		$category_id = $category;
		$posts_query_args 	= array(
				'posts_per_page'	=> 1,
				'category'			=> $category_id,
				'orderby'			=> 'rand', 
			);
		$peaw_posts = get_posts($posts_query_args);

		if(empty($peaw_posts)){
			return true;
		}else{
			return false;
		}
	}

	public function is_option_selected($selected, $option){
		if($selected == $option){
			return "selected='selected'";
		}
	}
}