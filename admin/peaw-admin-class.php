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

	private function __construct(){
		add_action('admin_menu', array($this,'peaw_add_settings_pages'));
		add_action('admin_menu', array($this,'peaw_create_settings'));
	}

	public function peaw_add_settings_pages(){
		$peaw_slug = 'peaw_settings';
		$capability = 'manage_options';
		add_options_page(
			'Post Preview Card Settings',
			'Post Preview Card',
			$capability,
			'peaw_settings',
			'Peaw_Admin::peaw_render_general_settings_page'
		);
	}

	public function peaw_create_settings(){
		/* Create the col in the db under the given group and with the name of second parameter */
		register_setting(
			'peaw-general-settings-group',
			'peaw_widgets_selection_checkbox'
		);

		/* Section of Settings */
		add_settings_section(
			'peaw-general-settings-section',
			__('General Settings', PEAW_TEXT_DOMAIN),
			'Peaw_Admin::peaw_render_setcion_general_settings',
			'peaw_settings'
		);

		/* Add the settings it self */
		add_settings_field(
			'peaw_widgets_selection_checkbox',
			'Widget Selection',
			'Peaw_Admin::peaw_render_form_checkbox_widget_selection',
			'peaw_settings',
			'peaw-general-settings-section'
		);
	}

	/*
	 *	Page Renderizer Callbacks Part
	 */

	public function peaw_render_general_settings_page(){
		require_once(PEAW_PATH . 'admin/partials/peaw-admin-renderizer-view.php');
	}

	/*
	 *	Section Renderizer Callbacks Part
	 */
	public function peaw_render_setcion_general_settings(){
		esc_attr_e('Select which widgets to add, Remove Post id from the "All Posts" section and more',PEAW_TEXT_DOMAIN);
	}

	/*
	 *	Form Inputs Renderizer Callbacks Part
	 */
	public function peaw_render_form_checkbox_widget_selection(){
		$html = '';
	}
}
add_action('init', array('Peaw_Admin','get_instance'));