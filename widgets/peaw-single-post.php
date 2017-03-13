<?php
/**
 * Post Card
 *
 * @package     Post Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
class PEAW_Single_Post extends WP_Widget{
	public function __construct(){
		$base_id 			= "PEAW_Single_Post";
		$widget_name 		= 'PEAW:' . __(' Single Post Preview' , PEAW_TEXT_DOMAIN);
		$sidebar_options 	= [
			'classname' 					=> 'peaw_single_post',
			'description'					=> __('Preview single post by id on static elementor page', PEAW_TEXT_DOMAIN),
			'customize_selective_refresh' 	=> true,
		];

		parent::__construct($base_id,$widget_name,$sidebar_options);
		$this->alt_option_name = "peaw_single_post";
	}
	
	public function widget($args, $instance){

		/*
		 *	Get the data from the Widget Form.
		 */
		$post_id 	= (isset($instance['post_id'])) && ($instance['post_id'] !== 0) ? $instance['post_id'] : null ;
		$call_text 	= ($instance['call_text']) ? $instance['call_text'] : __('Insert an Awesome Calling text here.', PEAW_TEXT_DOMAIN) ; 

		/*
		 *	If there's a post with the given ID, let's set all the variables
		 */
		if(get_post($post_id)){
			$peaw_post 	= get_post($post_id);
			$post_title = $peaw_post->post_title;
			$publish_date = get_the_date('F j, Y',$post_id);
			
			//Creat the category <a> attribute for each post category.
			$categories = get_the_category($post_id);
			$category_output = '';
			foreach ($categories as $category) {
				$cat_link = get_category_link( $category->term_id);
				$category_output .=  "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
			}

			$post_link = get_post_permalink($post_id);

			//Get Post featured image or default image
			if(has_post_thumbnail($post_id)){

				$img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);

			}else{
				$img[0] = PEAW_URI . 'img/image-not-found.png'; 
			}
		//Create error message to display as a post card
		}else{
			$post_title = __('Post not Found, insert a valid ID', PEAW_TEXT_DOMAIN);
			$publish_date = __('date not found', PEAW_TEXT_DOMAIN);
			$category_output = "<a class='peaw-category-link' href='#'>".__('Category not found', PEAW_TEXT_DOMAIN)."</a>";
			$post_link = "#";
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

		    <p class="peaw-call-text"><?php echo $call_text; ?></p>

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
		$instance = $old_instance;

		$instance['post_id'] 	= (int) $new_instance['post_id'];
		$instance['call_text'] = ( ! empty( $new_instance['call_text'] ) ) ? strip_tags( $new_instance['call_text'] ) : '';

		return $instance;
	}

	public function form($instance){
		$post_id 	= isset($instance['post_id']) ? esc_attr($instance['post_id'] ) : 0 ;
		$call_text = ! empty( $instance['call_text'] ) ? $instance['call_text'] : esc_html__( 'New call_text', PEAW_TEXT_DOMAIN );
	?>
		<p><label for="<?php echo esc_attr($this->get_field_id('post_id')); ?>">
			<?php _e('Post ID', PEAW_TEXT_DOMAIN); ?>
		</label></p>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_id')); ?>" type="number" name="<?php echo esc_attr($this->get_field_name('post_id')); ?>" value="<?php echo $post_id; ?>" >

		<label for="<?php echo esc_attr( $this->get_field_id( 'call_text' ) ); ?>"><?php esc_attr_e( 'call text:', PEAW_TEXT_DOMAIN ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'call_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'call_text' ) ); ?>" type="text" value="<?php echo esc_attr( $call_text ); ?>">
		</p>
	<?php
	}

}