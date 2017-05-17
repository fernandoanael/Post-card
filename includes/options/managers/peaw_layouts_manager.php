<?php
class Peaw_Layouts_Manager implements Peaw_Options_Base{
	public static $instance;

	private static $layouts_list;

	private static $categories_list;

	/*Returns the static object*/
	public static function get_instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	public static function peaw_get_settings_value($value_name){
		if($value_name == 'layouts_list'){
			return self::peaw_get_layouts_list();
		}elseif($value_name == 'defaults_layout_list'){
			return self::peaw_get_defaults_layout_list();
		}else{
			return NULL;
		}
	}

	public static function peaw_build_options(){
		$categories = get_categories();
		self::$categories_list = $categories;
		add_settings_section( 'peaw-layout-settings', 'Layouts', 'Peaw_Layouts_Manager::peaw_render_settings_layout_section', 'peaw_settings_layout');
		foreach ($categories as $category) {
			add_settings_field( 'peaw-selected-layout-'.$category->term_id, $category->name, 'Peaw_Layouts_Manager::peaw_render_settings_layout_select_field', 'peaw_settings_layout', 'peaw-layout-settings', [$category] );
			register_setting('peaw-settings-layout-group','peaw_selected_layout_'.$category->term_id);
		}
		add_settings_section( 'peaw-layout-activate-helper', 'Helper', 'Peaw_Layouts_Manager::peaw_render_settings_helper_section', 'peaw_settings_layout');
		add_settings_field( 'peaw-activate-layout-helper', 'Layout Helper', 'Peaw_Layouts_Manager::peaw_render_settings_layout_activate_helper', 'peaw_settings_layout', 'peaw-layout-activate-helper');
		register_setting( 'peaw-settings-layout-group', 'peaw_activate_layout_helper');

	}

	public static function peaw_render_settings_layout_section(){
		echo 'Select the default layout for each category';
	}
	public static function peaw_render_settings_helper_section(){
		echo 'Activate or deactivate the layout Helper';
	}

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

	public static function peaw_render_settings_layout_activate_helper(){
		$checked = checked('true', esc_attr(get_option('peaw_activate_layout_helper')), false);
		echo '<input type="checkbox" name="peaw_activate_layout_helper" value="true"'.$checked.' /> Activate if you want the helper';
	}


	public static function peaw_layout_register(array $layouts_list){
		foreach($layouts_list as $layout_ID => $layout_info){
			foreach ($layout_info as $layout_info_key => $layout_info_value) {
				self::$layouts_list[$layout_ID][$layout_info_key] = $layout_info_value;
			}
		}
	}

	public static function peaw_layout_render($args,$instance, Peaw_Widgets_Base $peaw_widget){
		$layout_ID = $instance['layout_selected'];
		//$layout_ID = 'original_layout';
		$peaw_layout_class = (string) self::$layouts_list[$layout_ID]['layout_class_name'];
		new $peaw_layout_class($args,$instance,$peaw_widget);
	}

	public static function peaw_get_layouts_list(){
		foreach(self::$layouts_list as $layout_ID => $layout_info){
			$layouts_list[$layout_ID] = $layout_info;	
		}
		return $layouts_list;
	}

	private function peaw_get_defaults_layout_list(){
		$defaults_layout_list = [];
		$categories = self::$categories_list !== null ? self::$categories_list : get_categories();
		foreach($categories as $category){
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