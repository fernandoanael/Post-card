<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 */

class Peaw_Widget extends Peaw_Widgets_base{
	protected $extra_data = [];

	public $args = [];
	public $instance = [];
	public $image;
	public $image_flag;
	public $publish_date;
	public $category_output;
	public $post_title;
	public $call_text;
	public $post_link;

	public function __set($property, $value){
		$this->extra_data[$property] = $value;
	}

	public function __get($property){
		if($this->extra_data[$property] == NULL){
			return '';
		}else{
			return $this->extra_data[$property];
		}
	}
}