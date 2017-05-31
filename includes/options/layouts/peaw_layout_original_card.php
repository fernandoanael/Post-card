<?php
/**
 * Post Preview Card
 *
 * @package     Post Preview Card
 * @author      Fernando Cabral
 * @license     GPLv3
 * @version 	2.0.0
 */
class Peaw_Layout_Original_Card implements Peaw_Layouts_Base{

	public function __construct(Peaw_Widgets_Base $peaw_widget){
		/* Enqueue registered Styles and Scripts here. This way style and Script are only enqueued if widget is on page */
		wp_enqueue_style('bootstrap-v4');
		wp_enqueue_style('peaw-post-preview-card');

		/*Call renderization*/
		$this->peaw_layout_render($peaw_widget);
	}

	public function peaw_layout_render(Peaw_Widgets_Base $peaw_widget){
		/*Start renderization*/
		echo $peaw_widget->args['before_widget'];
	?>
		<div class="card peaw-original-layout <?php echo $peaw_widget->additional_css_names ?>" style="<?php echo $peaw_widget->additional_style; ?>">

			<img src="<?php echo esc_attr($peaw_widget->image); ?>" class="post-preview-card-featured-image">
			<div class="card-block">
		  		<p class="card-text">
		  		<span class="peaw-info-span">
		  			<i class="dashicons dashicons-clock"></i>
		  		</span>
		  		<?php echo esc_html($peaw_widget->publish_date); ?> in <?php echo $peaw_widget->category_output; ?>

		  		</p>

			    <h4 class="card-title" style="font-size: <?php echo $peaw_widget->font_size; ?>px;"><?php echo esc_html($peaw_widget->post_title); ?></h4>

			    <p class="peaw-call-text"><?php echo esc_html($peaw_widget->call_text); ?></p>

			    <a href="<?php echo esc_attr($peaw_widget->post_link); ?>" class=" peaw-read-more">
			    
			    	<?php echo esc_html($peaw_widget->read_more_text); ?>

			    	<span class="peaw-read-more-span">

			    		<i class="dashicons dashicons-arrow-right-alt2"></i>
			    		
			    	</span>

			    </a>

		  </div>

		</div>
	<?php
		echo $peaw_widget->args['after_widget'];
	}
 
}