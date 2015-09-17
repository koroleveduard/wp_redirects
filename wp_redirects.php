<?php
/**
 * @package WP_SEO
 * @version 1.0
 */
/*
Plugin Name: wp_redirects
Description: The plugin allows you to add redirects without clogging htaccess.
Author: Korolev Eduard
Version: 1.0
*/


register_activation_hook( __FILE__, 'wp_redirects_install' );
register_deactivation_hook(__FILE__,'wp_redirects_deinstall');
function wp_redirects_install()
{
	global $wpdb;
    $table_redirects=$wpdb->prefix . "redirects";
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_redirects'") != $table_redirects) {
       $sql = "CREATE TABLE " . $table_redirects . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      from_url	varchar(255),
      to_url varchar(255),  
	  PRIMARY KEY (id)
	);";
     $wpdb->query($sql);
	} 
}

function wp_redirects_deinstall(){
	global $wpdb;
	$table_redirects=$wpdb->prefix . "redirects";
	$sql = "DROP TABLE IF EXISTS " . $table_redirects . ";";
	$wpdb->query($sql);
}
?>
