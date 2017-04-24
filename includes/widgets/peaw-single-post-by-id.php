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
		 Peaw_Layouts_Manager::peaw_layout_render($args,$instance);
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