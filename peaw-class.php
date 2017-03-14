<?php
/**
 * Post Card
 *
 * @package     Post Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
class Post_Elementor_Awesome_Widgets{
	/*
	 *	A reference of the instance of the <Post_Elementor_Awesome_Widgets> class.
	 */
	private static $instance;

	/*
	 *	Returns an instance of the <Post_Elementor_Awesome_Widgets> class.
	 */
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*
	 *	Loads the post-elementor textdomain
	 */
	public function peaw_load_textdomain(){
		load_plugin_textdomain("post-card");
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
	 *	Init the plugin and trigger all functions.
	 */
	private function __construct(){

		add_action('init', array($this,'peaw_load_textdomain'));

		add_action('widgets_init',array($this,'peaw_add_widgets'));

		add_action( 'wp_enqueue_scripts',array($this,'peaw_includes'));

	}

	/*
	 *	Add all the widgets
	 */
	public static function peaw_add_widgets(){
		//Single Post by Id
		include_once( PEAW_PATH . 'widgets/peaw-single-post.php');
		register_widget('PEAW_Single_Post');

		//Random Post by cat
		include_once(PEAW_PATH . 'widgets/peaw-random-post.php');
		register_widget('PEAW_Random_Post_By_Category');
	}

	/*
	 *	Apply styles and Javascript
	 */
	public function peaw_includes(){
		wp_enqueue_style( 'bootstrap-peaw-style', PEAW_URI . 'css/bootstrap.css');
		wp_enqueue_script('bootstrap-js', PEAW_URI .'js/bootstrap.js' );
		wp_enqueue_style( 'peaw-single-post-style', PEAW_URI . 'css/single-post-elementor.css');
	}
	




}
add_action('plugins_loaded', array('Post_Elementor_Awesome_Widgets', 'get_instance'));