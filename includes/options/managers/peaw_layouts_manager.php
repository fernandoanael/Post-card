<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 *
 *	Class that manage all the registered layout and default layouts.
 */
class Peaw_Layouts_Manager implements Peaw_Options_Base{
	/*Holds the instance of the static object*/
	public static $instance;

	/*Holds the list of registered layouts*/
	private static $layouts_list;

	/*Holds the created categories list*/
	private static $categories_list;

	/*Returns the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*Instantiate the layout class of the layout selected*/
	public static function peaw_layout_render(Peaw_Widgets_Base $peaw_widget){
		$layout_ID = $peaw_widget->instance['layout_selected'];
		//$layout_ID = 'original_layout';
		$peaw_layout_class = (string) self::$layouts_list[$layout_ID]['layout_class_name'];
		new $peaw_layout_class($peaw_widget);
	}

	/*Returns the value depending on the value_name */
	public static function peaw_get_settings_value($value_name){
		$value_list = ['layouts_list','defaults_layout_list'];
		if(in_array($value_name, $value_list)){
			if($value_name == 'layouts_list'){
				return self::peaw_get_layouts_list();
			}elseif($value_name == 'defaults_layout_list'){
				return self::peaw_get_defaults_layout_list();
			}
		}else{
			return false;
		}
	}

	/*	Build Options points to another static function
     *	If the build options need to build more than one section of custom options you just make peaw_build_section bigger and not this one
	 *  Returns the custom options. It builds options depending on the number of categories created 
	 */
	public static function peaw_build_options(){
		/*Layout settings custom fields*/
		self::peaw_build_section('peaw-layout-settings');

		/*Activate layout helper field*/
		/*
			FEATURE NOT YET DONE.
		 */
		/*self::peaw_build_section('peaw-layout-activate-helper');*/
	}
	/*Builds the section taking as parameter the section ID*/
	private static function peaw_build_section($id){
		if($id == 'peaw-layout-settings'){
			$categories = get_categories();
			self::$categories_list = $categories;
			add_settings_section( 'peaw-layout-settings', 'Layouts', 'Peaw_Layouts_Manager::peaw_render_settings_layout_section', 'peaw_settings_layout');
			foreach ($categories as $category) {
				add_settings_field( 'peaw-selected-layout-'.$category->term_id, $category->name, 'Peaw_Layouts_Manager::peaw_render_settings_layout_select_field', 'peaw_settings_layout', 'peaw-layout-settings', [$category] );
				register_setting('peaw-settings-layout-group','peaw_selected_layout_'.$category->term_id);
			}
		} elseif($id == 'peaw-layout-activate-helper'){
			add_settings_section( 'peaw-layout-activate-helper', 'Helper', 'Peaw_Layouts_Manager::peaw_render_settings_helper_section', 'peaw_settings_layout');
			add_settings_field( 'peaw-activate-layout-helper', 'Layout Helper', 'Peaw_Layouts_Manager::peaw_render_settings_layout_activate_helper', 'peaw_settings_layout', 'peaw-layout-activate-helper');
			register_setting( 'peaw-settings-layout-group', 'peaw_activate_layout_helper');
		}
	}

	/*
	 *	==========================================
	 *		RENDER SETTINGS SECTION callbacks
	 *	==========================================
	 */
	/*Layout Section callback*/
	public static function peaw_render_settings_layout_section(){
		echo 'Select the default layout for each category';
	}

	/*Helper Section callback*/
	public static function peaw_render_settings_helper_section(){
		echo 'Activate or deactivate the layout Helper';
	}

	/*
	 *	================================
	 *		SETTINGS FIELD callbacks
	 *	================================
	 */
	/*Render the select options with the layouts registered as options*/
	public static function peaw_render_settings_layout_select_field(array $category){
		$category = $category[0];
		$layout_selected = esc_attr(get_option( 'peaw_selected_layout_'.$category->term_id));
		$layouts_list = self::peaw_get_layouts_list();
	?>
		<select name="peaw_selected_layout_<?php echo $category->term_id; ?>">
		<?php
			foreach ($layouts_list as $layout => $layout_info) {
			?>
				<option value="<?php echo $layout ?>">
					<?php echo $layout_info['layout_name']; ?>
				</option>
			<?php
			}
		?>
		</select>

	<?php
	}

	/*Render checkbox for the helper activate*/
	public static function peaw_render_settings_layout_activate_helper(){
		$checked = checked('true', esc_attr(get_option('peaw_activate_layout_helper')), false);
		echo '<input type="checkbox" name="peaw_activate_layout_helper" value="true"'.$checked.' /> Activate if you want the helper';
	}

	/*
	 *	================================
	 *		Other functionalities
	 *	================================
	 */

	/*Register the layout*/
	public static function peaw_layout_register(array $layouts_list){
		foreach($layouts_list as $layout_ID => $layout_info){
			foreach ($layout_info as $layout_info_key => $layout_info_value) {
				self::$layouts_list[$layout_ID][$layout_info_key] = $layout_info_value;
			}
		}
	}



	/*return an array with the layouts registered*/
	public static function peaw_get_layouts_list(){
		foreach(self::$layouts_list as $layout_ID => $layout_info){
			$layouts_list[$layout_ID] = $layout_info;	
		}
		return $layouts_list;
	}

	/*Returns an array with the default layout of each category*/
	/********ALWAYS RETURNING ORIGINAL LAYOUT, CHANGE AFTER NEW LAYOUTS CREATED*********/
	private function peaw_get_defaults_layout_list(){
		$defaults_layout_list = [];
		$categories = self::$categories_list !== null ? self::$categories_list : get_categories();
		foreach($categories as $category){
			//$option = get_option('peaw_selected_layout_'.$category->term_id);
			$option = 'peaw_selected_layout_'.$category->term_id;
			if($option !== null | !empty($option) | $option !== false){
				$defaults_layout_list[$category->term_id] = 'original_layout';
				$defaults_layout_list[$category->name]	= 'original_layout';
			}else{
				$defaults_layout_list[$category->term_id] = $option;
				$defaults_layout_list[$category->name]	= $option;
			}
		}
		return $defaults_layout_list;
	}
}