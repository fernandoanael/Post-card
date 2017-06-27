<?php 
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 * 
 * Interface to be used by Any Manager that creates custom options
 */
interface Peaw_Options_Base{
	/*	Two mandatory functions
	 *		peaw_get_settings_value is a getter to retrieve some important data
	 *		peaw_build_options is a function to create the custom option itself
	 */
	public static function peaw_get_settings_value($value_name);
	public static function peaw_build_options();
}