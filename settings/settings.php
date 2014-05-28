<?php
	/**
		* @package Cr_CSV_IMPORT_FOR_DMITRIY
		* @version 1.0.4
	*/
	add_action( 'admin_init', 'register_cr_exemple_settings' );
	
	function register_cr_exemple_settings() {
		//register our settings
		register_setting( 'cr-csv-importer-settings-group', 'default_id' );

	}
	
	function cr_exemple_settings_page() { ?>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'cr-csv-importer-settings-group' ); ?>
		<?php do_settings_sections( 'cr-csv-importer-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('ID категории в которую помещать записи у которых категория не определена, если настройка не определена запись будет добавленна в  рубрику с ID = 1','wp_panda') ?></th>
				<td><input type="text" name="default_id" value="<?php echo get_option('default_id'); ?>" /></td>
			</tr>
		</table>
		<?php submit_button(__('Обновить','wp_panda')); ?>
	</form>
	
<?php }