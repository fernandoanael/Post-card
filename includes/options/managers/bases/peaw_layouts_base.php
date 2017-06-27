<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.1
 * 
 * Interface to be used by the Layout manager and any other layout related manager
 */
interface Peaw_Layouts_Base{
	/*Unique mandatory function is to render a layout*/
	public function peaw_layout_render(Peaw_Widgets_Base $peaw_widget);
}