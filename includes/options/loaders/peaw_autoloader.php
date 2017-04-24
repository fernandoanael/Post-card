<?php 
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
