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