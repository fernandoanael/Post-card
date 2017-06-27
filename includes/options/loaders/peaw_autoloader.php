<?php 
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
 */
/*Provides interface to search and instantiate all the needed Peaw class*/
spl_autoload_register(function($peaw_class){
	if(strrpos($peaw_class, 'Peaw') !== false){
		if(file_exists( PEAW_PATH . 'includes/options/'.strtolower($peaw_class).'.php')){
			require_once(PEAW_PATH . 'includes/options/'.strtolower($peaw_class).'.php');
		}elseif (file_exists( PEAW_PATH . 'includes/options/managers/'.strtolower($peaw_class).'.php')) {
			require_once(PEAW_PATH . 'includes/options/managers/'.strtolower($peaw_class).'.php');
		}elseif(file_exists( PEAW_PATH . 'includes/options/managers/bases/'.strtolower($peaw_class).'.php')){
			require_once(PEAW_PATH . 'includes/options/managers/bases/'.strtolower($peaw_class).'.php');
		}elseif(file_exists( PEAW_PATH . 'includes/options/layouts/'.strtolower($peaw_class).'.php')){
			require_once(PEAW_PATH . 'includes/options/layouts/'.strtolower($peaw_class).'.php');
		}
	}
});
