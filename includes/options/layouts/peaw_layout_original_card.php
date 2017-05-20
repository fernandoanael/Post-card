<?php
class Peaw_Layout_Original_Card implements Peaw_Layouts_Base{

	public function __construct($args, $instance, Peaw_Widgets_Base $peaw_widget){
		$this->peaw_layout_render($args,$instance,$peaw_widget);
	}

	public function peaw_layout_render(array $args, array $instance, Peaw_Widgets_Base $peaw_widget){
		/* Enqueue registered Styles and Scripts here. This way style and Script are only enqueued if widget is on page */
		wp_enqueue_style('bootstrap-v4');
		wp_enqueue_style('peaw-post-preview-card');

		echo $args['before_widget'];	
		$read_more_text = !is_null($instance['read_more_text']) ? $instance['read_more_text'] : 'Read More';
		//Render widget
		$width = !is_null($peaw_widget->width) ? $peaw_widget->width : '32%';

		if($peaw_widget->additional_css_names !== null){
			$additional_css_names = $peaw_widget->additional_css_names;
		}else{
			$additional_css_names = '';
		}
		
	?>
		<div class="card peaw-original-layout <?php echo $additional_css_names ?>" style="width: <?php echo $width; ?>;">

			<img src="<?php echo esc_attr($peaw_widget->image); ?>" class="post-preview-card-featured-image">
			<div class="card-block">
		  		<p class="card-text">
		  		<span class="peaw-info-span">
		  			<i class="dashicons dashicons-clock"></i>
		  		</span>
		  		<?php echo esc_html($peaw_widget->publish_date); ?> in <?php echo $peaw_widget->category_output; ?>

		  		</p>

			    <h4 class="card-title" style="font-size: <?php echo $instance['font_size']; ?>px;"><?php echo esc_html($peaw_widget->post_title); ?></h4>

			    <p class="peaw-call-text"><?php echo esc_html($peaw_widget->call_text); ?></p>

			    <a href="<?php echo esc_attr($peaw_widget->post_link); ?>" class=" peaw-read-more">
			    
			    	<?php echo esc_html($read_more_text); ?>

			    	<span class="peaw-read-more-span">

			    		<i class="dashicons dashicons-arrow-right-alt2"></i>
			    		
			    	</span>

			    </a>

		  </div>

		</div>
	<?php
		echo $args['after_widget'];
	}
 
}