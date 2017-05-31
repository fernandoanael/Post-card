<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
 * Interface to be used by Any Manager that creates custom options
 */
abstract class Peaw_Widgets_Base{
	/*Any needed extra data will be placed in the array*/
	protected $extra_data = [];

	/*Mandatory properties*/
	public $args = [];
	public $instance = [];
	public $image;
	public $publish_date;
	public $category_output;
	public $post_title;
	public $call_text;
	public $post_link;

	public abstract function __set($property, $value);

	public abstract function __get($property);

}
