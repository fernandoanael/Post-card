<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.
 */
	function peaw_ajax_loader(){
		/*Starts by getting all the vars*/
		$instance 	= $_POST['instance'];
		$args		= $_POST['args'];
		$displayed	= $_POST['postsDisplayed'];
		if(!defined('PEAW_URI')){
			define('PEAW_URI', $_POST['pluginUri']);
		}
		$defaults_layout_list = Peaw_Layouts_Manager::peaw_get_settings_value('defaults_layout_list');

		/*Check Category and layout*/
		if($instance['category_selected'] == 'all' ){
			$category = '';//This case each post has a layout
			if($instance['layout_selected'] !== null){
				$layout = $instance['layout_selected'];
			}else{
				$layout = '';// will set layout later
			}
		}else{
			$category = $instance['category_selected'];
			if($instance['layout_selected'] !== null){
				$layout = $instance['layout_selected'];
			}else{
				$layout = $defaults_layout_list[$category];
			}
		}

		/*Check how many letters to be displayed*/
		$excerpt_length = !is_null($instance['excerpt_length']) ? $instance['excerpt_length'] : 85;


		/*Check how many posts to be displayed*/
		if(($instance['number_of_posts'] - $displayed) < $instance['posts_first_shown']){
			$number_of_posts = $instance['number_of_posts'] - $displayed;
		}elseif(($instance['number_of_posts'] - $displayed) > $instance['posts_first_shown']){
			$number_of_posts = $instance['posts_first_shown'];
		}


		/*Getting the posts*/

		$posts = get_posts(
			array(
				'posts_per_page'   => $number_of_posts,
				'category'         => $category,
				'offset'					 => $displayed
			)
		);

		$count = count($posts);
		$subCount = 0;
		$displayed++;
		foreach ($posts as $post):
			if($subCount == 3){
				$count -= $subCount;
				$subCount = 0;
			}

			$peaw_widget = new Peaw_Widget();
			$peaw_widget->post_ID = $post->ID;
			$peaw_widget->post_title = $post->post_title;
			$peaw_widget->publish_date = get_the_date('F j, Y', $peaw_widget->post_ID);

			/* Check if post has an excerpt, if not generate a post excerpt using the firsts 85 characters of content */
			if(empty($post->post_excerpt)){
				$call_text = strip_tags($post->post_content);
				if(strlen($call_text) > $excerpt_length){
					$call_text = substr($call_text, 0, $excerpt_length);
					$call_text = $call_text . '(...)';
				}					
			}else{
				$call_text		= strip_tags($post->post_excerpt);
				$call_text 		= substr($call_text, 0, $excerpt_length);
				$call_text		= $call_text.'(...)';

			}

			$peaw_widget->call_text = $call_text;

			//Get the post link
			$peaw_widget->post_link = get_post_permalink($peaw_widget->post_ID);

			//Get the categorys assigned to this post
			$categories 	= get_the_category($peaw_widget->post_ID);
			$category_output= '';
			foreach ($categories as $category) {
			 	$cat_link = get_category_link($category->term_id);
			 	$category_output .= "<a class='peaw-category-link' href='".$cat_link."'>".$category->name."</a>";
			} 

			$peaw_widget->category_output = $category_output;

			//If post has thumbnail set it, otherwise, get the default image
			if(has_post_thumbnail($post_id)){

				$image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), [480,270]);
				$image = $image[0];

			}else{
				$image = PEAW_URI . 'public/images/image-not-found.png'; 
			}
			$peaw_widget->image = $image;

			if(!empty($layout)){
				$instance['layout_selected'] = $layout;
			}else{
				$instance['layout_selected'] = $defaults_layout_list[$categories[0]->term_id];
			}

			if($count >= 3){
				$peaw_widget->width = '32%';
			}elseif($count == 2){
				$peaw_widget->width = '45%';
			}elseif($count == 1){
				$peaw_widget->width = '80%';
			}

			$peaw_widget->additional_css_names = 'peaw-ajax-load-hidden';

			/*Use the Layout Manager class to render the widget according to the specified settings*/
			Peaw_Layouts_Manager::peaw_layout_render($args,$instance,$peaw_widget);
			echo '<p style="visibility: hidden;" class="widget-displayed-counter" name="'.$displayed.'"></p>';

			$subCount++;
			$displayed++;

		endforeach;

	wp_die();
	}