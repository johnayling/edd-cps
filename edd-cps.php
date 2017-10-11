<?php

/*
Plugin Name: EDD CPS
Description: EDD CPS is a custom plugin to connect Easy Digital Downloads with Copy Protect Software licensing
Version: 1.0
Author: John Ayling
Author URI: https://johnayling.com
License: GPL2
*/

	if (!defined('WP_CONTENT_URL')) {
		define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	}

	define('EDDCPSROOTDIR', plugin_dir_path(__FILE__));

	if ( file_exists( EDDCPSROOTDIR . '/cmb2/init.php' ) ) {
		require_once EDDCPSROOTDIR . '/cmb2/init.php';
	}

	if(!  class_exists('EDD_CPS')) {
		require_once(EDDCPSROOTDIR . 'edd-cps.class.php');
		EDD_CPS::init();
	}
