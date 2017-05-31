<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
 */
class PEAW_Multiple_Posts extends WP_Widget{

	

	public function __construct(){
		$base_id 			= "PEAW_Multiple_Posts";
		$widget_name 		= 'Post Preview Card:' . __(' Multiple Posts' , PEAW_TEXT_DOMAIN);
		$sidebar_options 	= [
			'classname' 					=> 'peaw_multiple_posts',
			'description'					=> __('Preview multiple posts with multiple widgets', PEAW_TEXT_DOMAIN),
			'customize_selective_refresh' 	=> true,
		];

		parent::__construct($base_id,$widget_name,$sidebar_options);
		$this->alt_option_name = "peaw_multiple_posts";

		/*Register the ajax loader javascript*/
		wp_register_script( 'peaw_multiple_posts_ajax_loader', PEAW_URI.'public/js/multiple-posts-ajax-loader.js', [], '', true );
	}

	public function widget($args, $instance){
		

		/*
		 *	Getting the default layout for each category
		 */
		$defaults_layout_list = Peaw_Layouts_Manager::peaw_get_settings_value('defaults_layout_list');


		/*
		 *	Check which layout to use
		 */
		if($instance['category_selected'] == 'all' ){
			$category = '';//This case each post has a layout
			if($instance['layout_selected'] !== null){
				$layout = $instance['layout_selected'];
			}else{
				$layout = '';
			}
		}else{
			$category = $instance['category_selected'];
			if($instance['layout_selected'] !== null){
				$layout = $instance['layout_selected'];
			}else{
				$layout = $defaults_layout_list[$category];
			}
		}

		//See the excerpt Length
		$excerpt_length = !is_null($instance['excerpt_length']) ? $instance['excerpt_length'] : 85;

		/*
		 *	Check if I'll need the ajax loader or not
		 */
		if(!is_null($instance['posts_first_shown'])){
			if($instance['posts_first_shown'] >= $instance['number_of_posts']){
				$number_of_posts = $instance['number_of_posts'] != 999 ? $instance['number_of_posts'] : '';
				$loader = false;
			}else{
				$number_of_posts = $instance['posts_first_shown'];
				$instance['number_of_posts'] = $instance['number_of_posts'] != 999 ? $instance['number_of_posts'] : wp_count_posts()->publish;
				$loader = true;
				
				//Making sure Jquery is enqueued and enqueue the ajax loader				
				wp_enqueue_script('jquery');
				wp_enqueue_script('peaw_multiple_posts_ajax_loader');

				$peawPHPInfo = [
					'pluginUri' 	=> PEAW_URI,
					'responserPHPUrl'=> admin_url('/admin-ajax.php'),
					'instance'		=> $instance,
					'args'			=> $args,
				];

				wp_localize_script('peaw_multiple_posts_ajax_loader', 'peawPHPInfo', $peawPHPInfo);
			}
		}else{
			$number_of_posts = $instance['number_of_posts'] != 999 ? $instance['number_of_posts'] : '';
		}


		/*
		 *	After all the checkUp get the posts
		 */
		$posts = get_posts(
			array(
				'posts_per_page'   => $number_of_posts,
				'category'         => $category
			)
		);

		/*
		 *	Time to render loop
		 */
		echo $args['before_widget'];
		?>
		<div class="row">
			<div class="col-xs-12 peaw-multiple-posts-container" id="peaw-multiple-posts-container">
				<?php
				
					$count = count($posts);
					$subCount = 0;
					$displayed = 1;
					foreach ($posts as $post):
						if($subCount == 3){
							$count -= $subCount;
							$subCount = 0;
						}

						$peaw_widget = new Peaw_Widget();
						$peaw_widget->post_ID = $post->ID;
						$peaw_widget->post_title = $post->post_title;
						$peaw_widget->publish_date = get_the_date('F j, Y', $peaw_widget->post_ID);

						/* Check if post has an excerpt, if not generate a post excerpt using the firsts 85 characters of content */
						if(empty($post->post_excerpt)){
							$call_text = strip_tags($post->post_content);
							if(strlen($call_text) > $excerpt_length){
								$call_text = substr($call_text, 0, $excerpt_length);
								$call_text = $call_text . '(...)';
							}					
						}else{
							$call_text		= strip_tags($post->post_excerpt);
							$call_text 		= substr($call_text, 0, $excerpt_length);
							$call_text		= $call_text.'(...)';

						}

						$peaw_widget->call_text = $call_text;

						//Get the categorys assigned to this post
						$categories 	= get_the_category($peaw_widget->post_ID);
						$category_output= '';
						foreach ($categories as $category) {
						 	$cat_link = get_category_link($category->term_id);
						 	$category_output .= "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
						} 

						$peaw_widget->category_output = $category_output;

						//Get the post link
						$peaw_widget->post_link = get_post_permalink($peaw_widget->post_ID);

						//If post has thumbnail set it, otherwise, get the default image
						if(has_post_thumbnail($post_id)){

							$image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
							$image = $image[0];

						}else{
							$image = PEAW_URI . 'public/images/image-not-found.png'; 
						}
						$peaw_widget->image = $image;

						if(!empty($layout)){
							$instance['layout_selected'] = $layout;
						}else{
							$instance['layout_selected'] = $defaults_layout_list[$categories[0]->term_id];
						}

						if($count >= 3){
							$peaw_widget->width = '32%';
						}elseif($count == 2){
							$peaw_widget->width = '45%';
						}elseif($count == 1){
							$peaw_widget->width = '80%';
						}

						/*Use the Layout Manager class to render the widget according to the specified settings*/
						Peaw_Layouts_Manager::peaw_layout_render($peaw_widget);

				?>
					<p style="visibility: hidden;" class="widget-displayed-counter" name="<?php echo $displayed; ?>"></p>
				<?php
						$subCount++;
						$displayed++;
					endforeach;
				?>
			</div>
		</div>
		<?php 
			if($loader): 
		?>
				<div class="row">
					<div class="col-xs-12 peaw-load-more-container">
						<p class="dashicons dashicons-image-rotate peaw-load-more-button" id="peaw-trigger-loader"></p>
					</div>
				</div>
		<?php
			endif;
			echo $args['after_widget'];
	}

	public function update($new_instance, $old_instance){
		if(!empty($new_instance['number_of_posts']) && is_int((int)$new_instance['number_of_posts'])){
			$instance['number_of_posts'] = $new_instance['number_of_posts'];
		}else{
			$instance['number_of_posts'] = '3';
		}

		if(!empty($new_instance['posts_first_shown']) && is_int((int)$new_instance['posts_first_shown'])){
			$instance['posts_first_shown'] = $new_instance['posts_first_shown'];
		}else{
			$instance['posts_first_shown'] = $instance['number_of_posts'];
		}

		if(!empty($new_instance['category_selected']) && $new_instance['category_selected'] !== 'all'){
			$instance['category_selected'] = $new_instance['category_selected'];
		}else{
			$instance['category_selected'] = 'all';
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
			$instance['excerpt_length'] = 85;
		}

		if(!empty($new_instance['read_more_text'])){
			$instance['read_more_text'] = sanitize_text_field($new_instance['read_more_text']);

		}else{
			$instance['read_more_text'] = 'Read More';
		}
		/* Mutual Form instance End */
		return $instance;
	}

	public function form($instance){
		$number_of_posts = !empty($instance['number_of_posts']) ? esc_attr($instance['number_of_posts']) : null;
		$posts_first_shown = !empty($instance['posts_first_shown']) ? esc_attr($instance['posts_first_shown']) : null;
		$category_selected = !empty($instance['category_selected']) ? esc_attr($instance['category_selected']) : 'all';
		$categories_list = get_categories();
		$layout_selected = !empty($instance['layout_selected']) ? esc_attr($instance['layout_selected']) : null;
		$layouts_list = Peaw_Layouts_Manager::peaw_get_layouts_list();
		$full_type_selected = !empty($instance['full_type_selected']) ? esc_attr($instance['full_type_selected']) : null;
		$font_size = !empty($instance['font_size']) ? esc_attr($instance['font_size']) : null;
		$excerpt_length = !empty($instance['excerpt_length']) ? esc_attr($instance['excerpt_length']) : null;

		$read_more_text = ! empty( $instance['read_more_text'] ) ? esc_attr($instance['read_more_text']) : esc_html__( 'Read More', PEAW_TEXT_DOMAIN );
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>">
			<?php esc_html_e('Number of Posts', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" style="width: 50px;" id="<?php echo  esc_attr( $this->get_field_id( 'number_of_posts' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'number_of_posts' )); ?>" type="number" min="5" max="50" value="<?php echo esc_attr($number_of_posts); ?>">

		<p><label for="<?php echo esc_attr($this->get_field_id('posts_first_shown')); ?>">
			<?php esc_html_e('Number of posts firstly displayed', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" style="width: 50px;" id="<?php echo  esc_attr( $this->get_field_id( 'posts_first_shown' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'posts_first_shown' )); ?>" type="number" min="5" max="50" value="<?php echo esc_attr($posts_first_shown); ?>">


		<p><label for="<?php echo esc_attr($this->get_field_id('category_selected')); ?>">
			<?php esc_html_e('Category', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<select id="<?php echo  esc_attr( $this->get_field_id( 'category_selected' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'category_selected' )); ?>">
		<option value="all" <?php echo $this->is_option_selected($category_selected,'all'); ?>>all</option>
		<?php
		foreach ($categories_list as $category) {
		?>	
			<option value="<?php echo $category->term_id; ?>" <?php echo $this->is_option_selected($category_selected,$category->$term_id); ?>><?php echo $category->name; ?></option>

	  	<?php
	  	}
	  	?>
	  	</select>
	  	
	  	<!-- Mutual Plugin Area Begin -->
		<p><label for="<?php echo esc_attr($this->get_field_id('layout_selected')); ?>">
			<?php esc_html_e('Select Layout', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<select id="<?php echo  esc_attr( $this->get_field_id( 'layout_selected' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'layout_selected' )); ?>">
			<?php 
			foreach ($layouts_list as $layout => $value) { 
			?>
				<option value="<?php echo esc_attr($layout) ?>" <?php echo $this->is_option_selected($layout_selected,$layout); ?>><?php echo $layouts_list[$layout]['layout_name']; ?></option>
			<?php
			}
			?>
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


	public function is_option_selected($selected, $option){
		if($selected == $option){
			return "selected='selected'";
		}
	}
}
	