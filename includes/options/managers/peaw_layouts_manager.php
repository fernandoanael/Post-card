<?php
class Peaw_Layouts_Manager{
	public static $instance;

	private static $layouts_list;

	/*Returns the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function peaw_layout_register(array $layouts_list){
			foreach($layouts_list as $layout => $val){
				self::$layouts_list[] .= $val;
			}

	}

	public function peaw_layout_render($args,$instance){

			var_dump(self::$layouts_list);

	}
}