<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 *
 *	Main functionalities:
 *		Display optional Functionalities and plugin Info
 *			public function peaw_get_settings_value();
 *			public function peaw_build_options();
 */
class Peaw_General_Settings_Manager implements Peaw_Options_Base{
	/*Contains the static object*/
	private static $instance;

	/*Returns the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*	Static getter function
	 *		@return the value of the value_name, checks first if it is a valid value_name if not return false
	 */
	public static function peaw_get_settings_value($value_name){
		$value_list = ['peaw_show_post_id'];
		if(in_array($value_name, $value_list)){
			 $option_value = esc_attr(get_option($value_name));
			 return $option_value;
		}else{
			return false;
		}
	}

	/*	Build Options points to another static function
     *	If the build options need to build more than one section of custom options you just make peaw_build_section bigger and not this one
	 */
	public static function peaw_build_options(){
		self::peaw_build_section('peaw-general-plugin-settings');
	}

	/*Builds the section taking as parameter the section ID*/
	private static function peaw_build_section($id){
		if($id == 'peaw-general-plugin-settings'){
			/* Register the settings setcion */
			add_settings_section( 'peaw-general-plugin-settings', 'Plugin Extra Functionalities and Info', 'Peaw_General_Settings_Manager::peaw_render_settings_general_section_general', 'peaw_settings');

			/* Register the settings fields */
			add_settings_field( 'peaw-show-post-id', 'Show post ID column', 'Peaw_General_Settings_Manager::peaw_render_settings_general_show_post_id_field', 'peaw_settings', 'peaw-general-plugin-settings');

			/*register the settings to the database within a global UNIQUE group*/
			register_setting( 'peaw-settings-general-group', 'peaw_show_post_id');
		}
	}

	/*
	 *	==========================================
	 *		RENDER SETTINGS SECTION callbacks
	 *	==========================================
	 */
	/*Render the text for the section*/
	public static function  peaw_render_settings_general_section_general(){
		echo 'Change the way Post Preview Card should behave within your wordpress site. These are optional options that won\'t affect the functionality of the plugin.';
	}

	/*
	 *	=================================
	 *		SETTINGS FIELD callbacks
	 *	=================================
	 *
	 */
	/*Render the custom option*/
	public static function peaw_render_settings_general_show_post_id_field(){
		$show_post_id_value = esc_attr(get_option('peaw_show_post_id'));
		$checked = checked('true', $show_post_id_value, false);
		echo'<input type="checkbox" name="peaw_show_post_id" value="true" '.$checked.' /> Leave it checked if you like the post ID column in your \'All Post\' section table.';
	}

}
