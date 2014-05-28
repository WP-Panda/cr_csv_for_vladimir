<?php
	/**
		* Plugin Name: CSV importer for Vladimir
		* Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
		* Description: Сsv импорт.
		* Version: 1.0.2
		* Author: Maksim (WP_Panda) Popoov
		* Author URI: http://URI_Of_The_Plugin_Author
		* License: A "Slug" license name e.g. GPL2
	*/
	
	/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)
		
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.
		
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
		
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	/*----------------------------------------------------------------------------*/
	/* setting constants
	/*----------------------------------------------------------------------------*/
	define('CR_CSV_IMPORTER_DIR', plugin_dir_path(__FILE__));
	define('CR_CSV_IMPORTER_URL', plugin_dir_url(__FILE__));
	
	/*----------------------------------------------------------------------------*/
	/* includes components
	/*----------------------------------------------------------------------------*/
	
	require_once 'settings/setting-page.php';
	require_once 'settings/settings.php';
	require_once 'functions/csv-parser.php';
	require_once 'functions/cr-function.php';
	
	/*----------------------------------------------------------------------------*/
	 /* includes backend scripts & styles
	 /*----------------------------------------------------------------------------*/
	
		function cr_csv_importer_backend() {
			wp_register_style( 'cr-csv-importer-backend-style', CR_CSV_IMPORTER_URL . 'assets/css/cr-csv-importer-backend-style.css', '', '1.00');
			wp_register_script( 'cr-csv-importer-backend-script', CR_CSV_IMPORTER_URL . 'assets/js/cr-csv-importer-backend-script.js', '', '1.00');
			wp_enqueue_script('cr-csv-importer-backend-script');
			wp_enqueue_style('cr-csv-importer-backend-style');
		}	
		add_action( 'admin_enqueue_scripts', 'cr_csv_importer_backend' );
	/*----------------------------------------------------------------------------*/
	/* add setting Page
	/*----------------------------------------------------------------------------*/
	
	function cr_csv_importer_add_settings_link( $links ) {
		$settings_link = '<a href="tools.php?page=cr-csv-importer-submenu-page">' . __( 'Настройки','wp_panda' ) . '</a>';
		array_push( $links, $settings_link );
        return $links;
	}
	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'cr_csv_importer_add_settings_link' );