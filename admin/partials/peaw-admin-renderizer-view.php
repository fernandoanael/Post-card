
<h1>Post Preview Card Plugin</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
	<?php 
		settings_fields('peaw-general-settings-group');
		do_settings_sections('peaw_settings');
	?>
</form>