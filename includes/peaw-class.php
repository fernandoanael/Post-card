<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
class Peaw_Class{
	/*
	 *	A reference of the instance of the Peaw_Class class.
	 */
	private static $instance;

	/*
	 *	Returns an instance of the Peaw_Class class.
	 */
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*
	 *	Clonning and unserializing instances is forbidden
	 */
	public function __clone(){
		_doing_it_wrong(__FUNCTION__, __('Something went wrong. Sorry about that.', PEAW_TEXT_DOMAIN),'1.0.0');
	}
	public function __wakeup(){
		_doing_it_wrong(__FUNCTION__, __('Something went wrong. Sorry about that.', PEAW_TEXT_DOMAIN), '1.0.0');
	}

	/*
	 *	Trigger all peaw functions.
	 */
	private function __construct(){

		add_action('widgets_init',array($this,'peaw_add_widgets'));

		add_filter('manage_posts_columns', array($this, 'peaw_add_post_id_to_column'));

		add_action('manage_posts_custom_column', array($this, 'peaw_show_post_id'), 10, 2 );

	}

	/*
	 *	Add all the widgets
	 */
	public static function peaw_add_widgets(){
		//Single Post by Id
		include_once( PEAW_PATH . 'includes/widgets/peaw-single-post-by-id.php');
		register_widget('PEAW_Single_Post_By_ID');

		//Random Post by cat
		include_once(PEAW_PATH . 'includes/widgets/peaw-random-post-by-category.php');
		register_widget('PEAW_Random_Post_By_Category');
	}

	/*
	 *	Add the post id on the All Post Table
	 */
	public function peaw_add_post_id_to_column($columns){
		if(!isset($columns['peaw_id'])){
			$columns['post_id'] = 'ID';
		}
		return $columns;
	}

	/*
	 *	Show the Post id on the All Post Table
	 */
	public function peaw_show_post_id($column, $id){
		if('post_id' == $column){
			echo $id;
		}
	}




}
add_action('plugins_loaded', array('Peaw_Class', 'get_instance'));