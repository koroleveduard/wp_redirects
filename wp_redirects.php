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

function redirect(){
	$uri = $_SERVER['REQUEST_URI'];
	global $wpdb;
	$table_redirects=$wpdb->prefix . "redirects";
	$redirect_url = $wpdb->get_row("SELECT to_url FROM $table_redirects WHERE from_url = '$uri'");
	if(!empty($redirect_url->to_url))
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$redirect_url->to_url);
		exit();
	}
	

}

function wp_redirects_menu(){
	add_options_page("Управление редиректами",'Редиректы','manage_options','wp_redirects','wp_redirects_admin_page');
}

function wp_redirects_admin_page(){
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$from_url = sanitize_text_field($_POST['from_url']);
		$to_url = sanitize_text_field($_POST['to_url']);
		if(empty($from_url) || empty($to_url))
			die("Недостаточно данных для редиректа!");
		global $wpdb;
		$table_redirects=$wpdb->prefix . "redirects";
		$sql = "INSERT INTO $table_redirects (from_url,to_url) VALUES('$from_url','$to_url')";
		$wpdb->query($sql);
		echo "<script>document.location.href = '".$_SERVER['REQUEST_URI']."';</script>";

	}
?>
<h2>Редиректы</h2>
<form action="<?=$_SERVER['REQUEST_URI'];?>" method="POST">
	<label for="from_url">Откуда:</label>
	<input type="text" name="from_url"><br/>
	<label for="to_url">Куда:</label>
	<input type="text" name="to_url"><br/>
	<input type="submit" value="Добавить">
</form>
<?php

}


add_filter("init",'redirect');
add_action("admin_menu",'wp_redirects_menu');
?>
