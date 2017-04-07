<?php
/*
 *
 *
 *
 *
 */
class Peaw_Widget_Manager implements Peaw_Options_Base{
	private static $instance;

	private static $peaw_widget_list;

	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct(){echo "hi";}
	/*
	 *	Get the Array and save as the widget list, filter the list to add only selected. For displaying the function will use the widget list 
	 */
	public function peaw_register_approved_widgets(Array $widget_list){
		self::peaw_register_widget_list($widget_list);
		
		$approved_widget_list = self::peaw_filter_widget_list($widget_list);
		foreach ($approved_widget_list as $peaw_widget){
			include_once($peaw_widget['path']);
			register_widget($peaw_widget['ID']);
		}
	}

	private static function peaw_register_widget_list(Array $widget_list){
		self::$peaw_widget_list = $widget_list;
	}
	private static function peaw_get_widget_list(){
		return self::$peaw_widget_list;
	}
	private function peaw_filter_widget_list(Array $widget_list){
		// See the selected widgets and return a new widget list
		return $widget_list;
	}

	public function peaw_get_options(){

	}
	public function peaw_display_options(){

	}
}