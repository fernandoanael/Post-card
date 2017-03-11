<?php
/**
 * Post Card
 *
 * @package     Post Card
 * @author      Fernando Cabral
 * @license     GPLv3
 *
 * @wordpress-plugin
 * Plugin Name: Post Card
 * Plugin URI:  https://github.com/fernandoanael/Post-card
 * Description: Plugin install beatiful widget Post in card shape. Works perfect with ELEMENTOR
 * Version:     1.0.0
 * Author:      Fernando Cabral
 * Author URI:  https://github.com/fernandoanael
 * Text Domain: post-card
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */
//Don't access this file directly
if (!defined('WPINC')){ die; }

/*
	===========
	 Constants
	===========
*/
/* Set constant path to the plugin directory. */
define('PEAW_PATH' , trailingslashit(plugin_dir_path(__FILE__)));

/* Set constant path to the plugin directory URI */
define('PEAW_URI', trailingslashit(plugin_dir_url(__FILE__)));

/* Set constant to the textdomain value */
define('PEAW_TEXT_DOMAIN', 'post-card');

/* Loads and trigger the Post-Elementor plugin Class */
require_once(PEAW_PATH . 'peaw-class.php');
