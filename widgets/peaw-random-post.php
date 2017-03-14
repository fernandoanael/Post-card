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
		$widget_name 		= 'PEAW:' . __(' Random Post By Category' , PEAW_TEXT_DOMAIN);
		$sidebar_options 	= [
			'classname' 					=> 'peaw_random_post_by_category',
			'description'					=> __('Preview random post by given category', PEAW_TEXT_DOMAIN),
			'customize_selective_refresh' 	=> true,
		];

		parent::__construct($base_id,$widget_name,$sidebar_options);
		$this->alt_option_name = "peaw_random_post_by_category";
	}

	public function widget($args,$instance){
		//Check if user set a valid category
		if(isset($instance['category'])){
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
				$call_text		= $post->post_excerpt;

				$categories 	= get_the_category($post_id);
				$category_output= '';
				foreach ($categories as $category) {
				 	$cat_link = get_category_link($category->term_id);
				 	$category_output .= "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
				 } 

				$post_link = get_post_permalink($post_id);

				//If post has thumbnail set it, otherwise, get the default image
				if(has_post_thumbnail($post_id)){

					$img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);

				}else{
					$img[0] = PEAW_URI . 'img/image-not-found.png'; 
				}
			}
		//If User didnt set a valid category
		}else{
			$post_title 	= __('Insert a Valid Category', PEAW_TEXT_DOMAIN);
			$publish_date 	= __('No date found', PEAW_TEXT_DOMAIN);
			$call_text		= __('Insert a Valid category by going to Elementor page builder or in the default wordpress widget admin area', PEAW_TEXT_DOMAIN);
			$category_output = "<a class='peaw-category-link' href='#'>".
								__('No category', PEAW_TEXT_DOMAIN)."</a>";
			$post_link = "#";
			$img[0] = PEAW_URI . 'img/image-not-found.png';
		}
		

		echo $args['before_widget'];	
		//Render widget itself
	?>
		<div class="card" style="width: 22rem;">

			<img src="<?php echo $img[0] ?>" width="480" height="270">

		  <div class="card-block">
		  	<p class="card-text">
		  		<span class="peaw-info-span">
		  			<i class="fa fa-clock-o"></i>
		  		</span>
		  		<?php echo $publish_date; ?> in <?php echo $category_output; ?>

		  	</p>

		    <h4 class="card-title"><?php echo $post_title; ?></h4>

		    <p class="peaw-call-text">
		    <?php 
		    	//If call text (excerpt) is empty and is admin user and the user set a valid category
		    	if((empty($call_text)) && (current_user_can('edit_posts')) && (isset($intance['category']))){
	    			edit_post_link( __('Add Excerpt'), '', "", $post_id);
	    		}else{
	    			echo $call_text;
	    		}
		    ?>
		   
		    </p>

		    <a href="<?php echo $post_link; ?>" class=" peaw-read-more">
		    
		    	Read More

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
		$instance = [];

		$instance['category'] = $new_instance['category'];

		return $instance;
	}

	public function form($instance){
		$category = !empty($instance['category']) ? esc_attr($instance['category']) : null ; 

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
		
	    <p><label for="<?php echo esc_attr($this->get_field_id( 'category' )); ?>"><?php _e( 'Select category', PEAW_TEXT_DOMAIN ); ?>:
	    </label></p>

	    <?php wp_dropdown_categories($category_query_args); ?>
	  	
	  	<p class="random-post-call-text-notice">Call text is by default the Post's Excerpt</p>
	  <?php
	}
}