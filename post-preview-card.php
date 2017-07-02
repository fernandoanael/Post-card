<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 *
 * @wordpress-plugin
 * Plugin Name: Post Preview Card
 * Plugin URI:  hhttps://github.com/fernandoanael/Post-card/tree/SettingsMenu
 * Description: This plugins adds 3 Post Preview Widgets in Card shape made to be used with Elementor
 * Version:     2.0.3
 * Author:      Fernando Anael Cabral
 * Author URI:  https://github.com/fernandoanael
 * Text Domain: post-card

 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *  This file is part of Post Preview Card.  
  *	Post Preview Card is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 2 of the License, or
  * any later version.
  * 
  * Post Preview Card is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  * GNU General Public License for more details.
  * 
  * You should have received a copy of the GNU General Public License
  * along with Post Preview Card. If not, see https://www.gnu.org/licenses/gpl-3.0.html
 */
//Don't access this file directly
if (!defined('WPINC')){ die; }

/* Set constant path to the plugin directory. */
define('PEAW_PATH' , trailingslashit(plugin_dir_path(__FILE__)));

/* Set constant path to the plugin directory URI */
define('PEAW_URI', trailingslashit(plugin_dir_url(__FILE__)));

/* Set constant to the textdomain value */
define('PEAW_TEXT_DOMAIN', 'post-preview-card');

/* */
require_once(PEAW_PATH . 'includes/options/loaders/peaw_autoloader.php');

/* Loads the Post_Preview_Card plugin Class */
require_once(PEAW_PATH . 'includes/peaw-class.php');

/* Loads the Peaw_admin Class */
require_once(PEAW_PATH . 'admin/peaw-admin-class.php');

