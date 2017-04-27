<?php

abstract class Peaw_Widgets_Base{
	protected $extra_data = [];

	public $image;
	public $publish_date;
	public $category_output;
	public $post_title;
	public $call_text;
	public $post_link;

	public abstract function __set($property, $value);

	public abstract function __get($property);

}