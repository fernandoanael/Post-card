<h1>Post Preview Card Layouts Settings</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
	<?php 
		//All settings in this page are going to be related to the peaw-settings-group
		settings_fields('peaw-settings-layout-group'); 
		do_settings_sections('peaw_settings_layout');
		do_settings_sections('peaw_settings_helper');
		submit_button();
	?>
</form>