<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 */
 interface Peaw_Ajax_Base{
 	public function peaw_register_ajax(array $args);
 	public static function peaw_load_all_registered_ajax();
 }