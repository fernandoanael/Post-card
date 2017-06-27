<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 * 
 * Will provide functions for widgets or layouts that need ajax to work
 */
class Peaw_Ajax_Register_Manager implements Peaw_Ajax_Base{
	/*holds the ajax files and its functions*/
	protected static $ajax_list;

	/*holds the instance*/
	public static $instance;

	/*Returns the instance of the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*
	 *	Example of args array
	 *
	$args = [
		'peaw_widget_multiple_posts_loader'	=> [
			'ajax_file_url'		=>	PEAW_PATH . 'includes/options/loaders/peaw_ajax_multiple_posts_loader.php',
			'ajax_functions_name'=> ['peaw_ajax_loader']
		],
	];
	*/
	/*Register a new ajax file to array*/
	public function peaw_register_ajax(array $args){

		foreach ($args as $ajax_ID => $ajax) {

			self::$ajax_list[$ajax_ID] = $args[$ajax_ID];

		}

	}

	/*Register and load ajax files and functions to wordpress*/
	public static function peaw_load_all_registered_ajax(){
		$ajax_list = self::$ajax_list;
		
		foreach ($ajax_list as $ajax_ID => $ajax_info) {

			require_once($ajax_list[$ajax_ID]['ajax_file_url']);

			foreach ($ajax_list[$ajax_ID]['ajax_functions_name'] as $function_name) {

				add_action('wp_ajax_'.$function_name, $function_name);
				add_action('wp_ajax_nopriv_'.$function_name, $function_name);

			}	

		}

	}

}