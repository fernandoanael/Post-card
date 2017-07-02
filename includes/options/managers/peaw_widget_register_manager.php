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
 *		-> Save list of all Peaw Widgets
 *		-> Register to WP activated widgets only
 *		-> Display Activate and Deactivate options
 *		-> Give info about the widgets	
 *			public function peaw_get_settings_value();
 *			public function peaw_build_options();
 */

class Peaw_Widget_Register_Manager implements Peaw_Options_Base{
	/*Contains the static object*/
	private static $instance;

	/*Registered widget list*/
	public static $widget_list;

	/* Options name of each widget list */
	private static $widgets_options_name;

	/*Returns the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*return the value if value_name exist*/
	public static function peaw_get_settings_value($value_name){
		$value_list = self::$widgets_options_name;
		if(in_array($value_name, $value_list)){
			$option_value = esc_attr(get_option($value_name));
			return $option_value;
		}else{
			return false;
		}
	}


	/*Build the custom options depending on the registered widgets
	 *	Build Options points to another static function
     *	If the build options need to build more than one section of custom options you just make peaw_build_section bigger and not this one
	 */
	public static function peaw_build_options(){
		self::peaw_build_section('peaw-general-widgets-settings');
	}

	/*Builds the section taking as parameter the section ID*/
	private static function peaw_build_section($id){
		if($id == "peaw-general-widgets-settings"){
			$widget_list = self::$widget_list;
			add_settings_section( 'peaw-general-widgets-settings', 'Widgets general settings', 'Peaw_Widget_Register_Manager::peaw_render_settings_widgets_section_general', 'peaw_settings_widgets');
			foreach ($widget_list as $widget) {
				add_settings_field( 'peaw-activate-'.$widget['option_name'], $widget['option_name'], 'Peaw_Widget_Register_Manager::peaw_render_settings_widgets_activate_field', 'peaw_settings_widgets', 'peaw-general-widgets-settings', [$widget]);
				register_setting( 'peaw-settings-widgets-group', 'peaw_activate_'.$widget['option_name']);
			}
		}
	}

	/*
	 *	==========================================
	 *		RENDER SETTINGS SECTION callbacks
	 *	==========================================
	 */
	/*widgets general section text*/
	public static function peaw_render_settings_widgets_section_general(){
		echo 'Activate the widgets you want to be added to your wordpress widgets list';
	}

	/*
	 *	================================
	 *		SETTINGS FIELD callbacks
	 *	================================
	 */
	/*Render checkbox of the activate widget*/
	public static function peaw_render_settings_widgets_activate_field(array $widget){
		$widget = $widget[0];
		$activate_value = esc_attr(get_option('peaw_activate_'.$widget['option_name']));
		$checked = checked('true',$activate_value, false);
		echo'<input type="checkbox" name="peaw_activate_'.$widget['option_name'].'" value="true" '.$checked.' /> Uncheck this option if you do not want this widget registered within your wordpress';
	}

	/*
	 *	================================
	 *		Other functionalities
	 *	================================
	 */

	/*Register the widget list in the plugin even if not activated*/
	public static function peaw_register_widget_list(array $widget_list){
		self::$widget_list = $widget_list;
		foreach($widget_list as $widget){;
			self::$widgets_options_name[] .= 'peaw_activate_'.$widget['option_name'];
		}
	}

	/*Register in Wordpress only the Peaw registered widgets*/
	public static function peaw_register_approved_widgets(array $widget_list = NULL){
		if(self::$widget_list == null){
			if($widget_list !== null){
				self::peaw_register_widget_list($widget_list);
			}else{
				return;
			}
		}
		foreach(self::$widget_list as $widget){
			if(self::peaw_get_settings_value('peaw_activate_'.$widget['option_name']) == 'true'){
				include_once($widget['path']);
				register_widget($widget['ID']);
			}else{
			}
		}
		
	}

}
