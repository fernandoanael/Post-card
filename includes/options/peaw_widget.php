<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.
 */

class Peaw_Widget extends Peaw_Widgets_base{
	protected $extra_data = [];

	public $args = [];
	public $instance = [];
	public $image;
	public $publish_date;
	public $category_output;
	public $post_title;
	public $call_text;
	public $post_link;

	public function __set($property, $value){
		$this->extra_data[$property] = $value;
	}

	public function __get($property){
		if(in_array($property, $this->extra_data)){
			return $this->extra_data[$property];
		}else{
			return '';
		}
	}
}