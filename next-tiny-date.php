<?php
/*
* Plugin Name: Next Tiny Date
* Plugin URI: https://nxt-web.com/plugins/next-tiny-date/
* Description: Next Tiny Date allows you to propose an appointment booking form on your website through the adding of a simple shortcode. Configure your opening hours, add your reasons for appointment. Choose the appointment duration. Then let clients book in a week view directly from your website! You can then view your appointments or cancel them. Lock or unlock some appointment slots for personal use, export them in a .CSV file... It can send confirmation emails, and redirect to a payment page. It generates stats with pie and bar charts of number of appointments per day and per reason of appointment. Usefull for health professional for medical appointments, teachers with different types of courses, business people for planing meetings... Very light and easy to use!
* Author: F.Leroux
* Text Domain: next-tiny-date
* Domain Path: /languages
* Version: 3.0
* Author URI: https://nxt-web.com/
*/

/*
Copyright 2023 F.Leroux
*/

global $wpdb;

if (!defined('NTDT_VERSION'))
   { define('NTDT_VERSION','3.0');
   }
if (!defined('NTDT_TYPE'))
   { define('NTDT_TYPE','Free');
   }

function ntdt_PluginActivation()
{ update_option('ntdtCurrentVersion',NTDT_VERSION);
  update_option('ntdtCurrentType',NTDT_TYPE);
    
  return NTDT_VERSION;
}
register_activation_hook(__FILE__,'ntdt_PluginActivation');

function ntdt_InstallDB()
{	global $wpdb;
	global $opt_VersionDB;

	$TableName = $wpdb->prefix . 'ntdtRV';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $TableName (
		   id VARCHAR(8) NOT NULL,
		   DayRVs text NOT NULL,
		   PRIMARY KEY  (id)
	     ) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);

	add_option('optVersionDB',$opt_VersionDB);
}
register_activation_hook(__FILE__,'ntdt_InstallDB');

require_once plugin_dir_path(__FILE__) . 'includes/ntdt-functions.php';
