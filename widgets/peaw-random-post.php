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

	}

	public function update($new_instance, $old_instance){

	}

	public function form($instance){
		
	}
}