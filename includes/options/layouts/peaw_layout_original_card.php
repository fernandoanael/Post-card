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
		$no_thumbnail_title = !$peaw_widget->image_flag ? ' no-thumbnail-title ' : '';

		/*Start renderization*/ 
		//echo $peaw_widget->args['before_widget'];
	?>
<div class="col-sm-12 col-md-4 <?php echo $peaw_widget->additional_css_names ?>" style="<?php echo $peaw_widget->additional_style; ?>">
	<div class="card  no-padding ">
		<?php 
			$no_thumbnail_title = ' no-thumbnail-title ';
			if($peaw_widget->image_flag): 
				$no_thumbnail_title = '';
		?>
			
				<img src="<?php echo esc_attr($peaw_widget->image); ?>" class="card-img-top">

		<?php endif; ?>

		<div class="card-block no-padding">

			<h4 class="card-title card-padding <?php echo $no_thumbnail_title ?>">
				<?php echo $peaw_widget->post_title; ?>
			</h4>

			<div class="card-padding">

				<p class="card-text"><?php echo $peaw_widget->call_text; ?></p>

				<p class="card-post-info">

		  			<?php echo esc_html($peaw_widget->publish_date); ?> in <?php echo $peaw_widget->category_output; ?>

				</p>
			</div>

			<a href="<?php echo $peaw_widget->post_link; ?>" class="btn btn-primary btn-sm card-btn" style="background-color: <?php echo $peaw_widget->button_backgroud_color; ?>; color: <?php echo $peaw_widget->button_font_color; ?>!important; font-size: <?php echo $peaw_widget->button_font_size; ?>px;">
				<?php echo esc_html($peaw_widget->read_more_text,PEAW_TEXT_DOMAIN); ?>
			</a>
			
		</div>

	</div>
</div>
	<?php
		//echo $peaw_widget->args['after_widget'];
	}
 
}