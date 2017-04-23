<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 */
/*
 *	Comments
 */
class Peaw_Admin {
	/*
	 *	A reference of the instance of the Peaw_Admin class.
	 */
	private static $instance;

	/*
	 *	Returns an instance of the Peaw_Admin class.
	 */
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	/*
		==================================
			Initialization Functions
		==================================
	*/
	private function __construct(){
		/*Add The menu and submenus. The callbkac does it */
		add_action( 'admin_menu', [$this,'peaw_add_menu_pages']);

		/*Add the custom settings*/
		add_action( 'admin_init', [$this,'peaw_custom_settings']);

	}

	/*
		=================================
			MENU AND SUBMENU callbacks
		=================================
	*/

	/*Add the menu and submenu pages*/
	public function peaw_add_menu_pages(){
		/*Add Main menu page and submenu attached to it */
		add_menu_page( 'Post Preview Card General Settings', 'Post Preview Card', 'manage_options', 'peaw_settings', [$this,'peaw_render_settings_general_page'], '', 110 );
		add_submenu_page( 'peaw_settings', 'Post Preview Card General Settings', 'General', 'manage_options', 'peaw_settings', [$this,'peaw_render_settings_general_page'] );

		/*Add Widgets Settings Sub Menu*/
		add_submenu_page( 'peaw_settings', 'Post Preview Card Widgets Settings', 'Widgets', 'manage_options', 'peaw_settings_widgets', [$this,'peaw_render_settings_widgets_page']);
	}

	/*
		========================================
			RENDER SETTINGS PAGES callbacks
		========================================
		Theses callbacks will require a specific partial settings template
	*/

	/*Render the general settings page*/
	public function peaw_render_settings_general_page(){
		require_once(PEAW_PATH.'admin/partials/peaw-admin-settings-general-page.php');
	}

	/*Render the widgets settings page*/
	public function peaw_render_settings_widgets_page(){
		require_once(PEAW_PATH.'admin/partials/peaw-admin-settings-widgets-page.php');
	}

	/*
		==============================================
			REGISTER SETTINGS AND SETCIONS callback
		==============================================
	*/

	public function peaw_custom_settings(){
		/*
			add_settings_section( $id, $title, $callback, $page );
			add_settings_field( $id, $title, $callback, $page, $section, $args ); 
			register_setting( $option_group, $option_name, $sanitize_callback );
		*/
		Peaw_General_Settings_Manager::peaw_build_options();
		Peaw_Widget_Register_Manager::peaw_build_options();

	}
}
add_action('init', array('Peaw_Admin','get_instance'));