<?php
/**
 * Post Card
 *
 * @package     Post Card
 * @author      Fernando Cabral
 * @license     GPLv3
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
		
		/* Register Styles and Scripts but don't Enqueue. */
		wp_register_style( 'bootstrap-v4', PEAW_URI . 'public/css/bootstrap.css' );
		wp_register_style( 'peaw-post-preview-card', PEAW_URI . 'public/css/post-preview-card.css' );
	}

	public function widget($args,$instance){
		/* Enqueue registered Styles and Scripts here. This way style and Script are only enqueued if widget is on page */
		wp_enqueue_style('bootstrap-v4');
		wp_enqueue_style('peaw-post-preview-card');
		
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
				$post_title 	= $post->post_title;
				$publish_date 	= get_the_date('F j, Y', $post_id);

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

				//Get the categorys assigned to this post
				$categories 	= get_the_category($post_id);
				$category_output= '';
				foreach ($categories as $category) {
				 	$cat_link = get_category_link($category->term_id);
				 	$category_output .= "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
				 } 

				 //Get the post link
				$post_link = get_post_permalink($post_id);

				//If post has thumbnail set it, otherwise, get the default image
				if(has_post_thumbnail($post_id)){

					$img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
					$img = $img[0];

				}else{
					$img = PEAW_URI . 'public/images/image-not-found.png'; 
				}
			}
		
		/* If Category is not set or null or does not exist or doesn't have any post assigned we create an error message  */
		}else{
			//If User didnt set a valid category
			$post_title 	= __('Something is wrong', PEAW_TEXT_DOMAIN);
			$publish_date 	= __('Date not found', PEAW_TEXT_DOMAIN);
			$call_text		= __('This can be caused by invalid category, category with no posts or category recently deleted.', PEAW_TEXT_DOMAIN);
			$category_output = "<a class='peaw-category-link' href='#'>".
								__('No category', PEAW_TEXT_DOMAIN)."</a>";
			$post_link = "#";
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

		    <p class="peaw-call-text">
		    <?php echo esc_html($call_text); ?>
		   
		    </p>

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
			'class'				=> 'postform widefat random-post-categoty-selector',
		);
		?>
		
	    <p><label for="<?php echo esc_attr($this->get_field_id( 'category' )); ?>"><?php esc_attr_e( 'Select category', PEAW_TEXT_DOMAIN ); ?>:
	    </label></p>

	    <?php wp_dropdown_categories($category_query_args); ?>
	  	
	  	<p class="random-post-call-text-notice">Call text is the post excerpt and if it is empty, it will be the first 85 characters of the post content.</p>
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
}