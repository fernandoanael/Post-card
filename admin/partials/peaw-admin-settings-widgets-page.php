<h1>Post Preview Card General Settings</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
	<?php 
		//All settings in this page are going to be related to the peaw-settings-group
		settings_fields('peaw-settings-widgets-group'); 
		do_settings_sections('peaw_settings_widgets');
		submit_button();
	?>
</form>