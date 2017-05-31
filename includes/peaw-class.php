<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
class Peaw_Class{
	public function peaw_ajax_loader(){
		echo "Hi";
	}
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

		add_action( 'wp_enqueue_scripts', [$this,'peaw_register_base_scripts']);
		
		/* Initialize widgets, layouts, and extra functionalities*/
		add_action('widgets_init',array($this,'peaw_add_widgets'));
		add_action('widgets_init',array($this,'peaw_add_layouts'));
		if(Peaw_General_Settings_Manager::peaw_get_settings_value('peaw_show_post_id') == 'true'){
			add_filter('manage_posts_columns', array($this, 'peaw_add_post_id_to_column'));
			add_action('manage_posts_custom_column', array($this, 'peaw_show_post_id'), 10, 2 );
		}

		/* SPECIAL ATTENTION FOR BETTER USAGE IN THIS */
		add_action( 'wp_ajax_peaw_ajax_loader', 'peaw_ajax_loader' );
		add_action( 'wp_ajax_nopriv_peaw_ajax_loader', 'peaw_ajax_loader');

		/*Loads the AJAX multiple-Post Responser. Should see if heres the real best place for it after*/
		require_once(PEAW_PATH . 'includes/options/loaders/peaw_ajax_multiple_posts_loader.php');

	}

	/*
	 *	Register Base Style but dont enqueue
	 */

	public function peaw_register_base_scripts(){
		wp_register_style( 'bootstrap-v4', PEAW_URI . 'public/css/bootstrap.css' );
		wp_register_style( 'peaw-post-preview-card', PEAW_URI . 'public/css/post-preview-card.css' );
	}

	/*
	 *	Add all the widgets
	 */
	public static function peaw_add_widgets(){
		//Single Post by Id
		$widgets_list = array(
			0	=> array(
					'ID'	=>	'PEAW_Single_Post_By_ID',
					'path'	=>	 PEAW_PATH . 'includes/widgets/peaw-single-post-by-id.php',
					'name'	=>	'Single Post by ID',
					'option_name'	=>	'post_by_id',	
			),
			1	=>	array(
					'ID'	=>	'PEAW_Random_Post_By_Category',
					'path'	=>	 PEAW_PATH . 'includes/widgets/peaw-random-post-by-category.php',
					'name'	=>	'Random Post by Category',
					'option_name'	=>	'post_by_cat',	
			),
			2	=>	array(
					'ID' 	=>	'PEAW_Multiple_Posts',
					'path'	=>	 PEAW_PATH . 'includes/widgets/peaw-multiple-posts.php',
					'name'	=>	'Multiple Posts',
					'option_name'	=>	'peaw_multiple_posts',

			),
		);
		Peaw_Widget_Register_Manager::peaw_register_approved_widgets($widgets_list);
	}

	/*
	 *
	 */
	public static function peaw_add_layouts(){
		$layouts_list = array(
			'original_layout' => array(
					'layout_name' 		=> 'Original Layout',
					'layout_class_name' => 'Peaw_Layout_Original_Card',
			),
		);

		Peaw_Layouts_Manager::peaw_layout_register($layouts_list);
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