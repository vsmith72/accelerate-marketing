<?php defined( 'ABSPATH' ) or die();
/*
Plugin Name: PushLive - Staging Site to Live Site in One Click
Plugin URI: http://1squared.com/pushlive
Description: PushLive is a full stage to live site push plugin for any Linux based server. Simply set up two WordPress installations on the same or networked servers (with full filesystem access capabilities), adjust the PushLive settings, and when you're ready to release your staging version to the live version, it's a simple one click operation. Note: This plugin will only work on Linux based systems.
Author: 1 Squared / Jamin Szczesny 
Author URI: http://1squared.com
Version: 0.6.8
License: GPLv3 or later
*/

/*
 * This Plugin is free to use for now, but if you change any code you are required (ok heavily requested) to send us the final product and keep us notified.
*/

	//FORCE FRONTEND LOGIN IF SETTINGS CRITERIA IS MET
	//simply prevents general public, crawlers, and anything else from viewing the staging site without logging in
	function pushlive_force_login(){
		is_user_logged_in() || auth_redirect();
	}
	
	//For Multisite compatability I changed get_option to get_site_option
	$force_login = trim( get_site_option( 'pushlive_force_login', 'stage.' ) );
	
	if( !empty( $force_login ) && strpos( get_bloginfo( 'wpurl' ), $force_login ) > -1 ){
		add_action ( 'parse_request', 'pushlive_force_login', 1 );
	}

	

	//LOGIN CRITERIA MET - CONTINUE LOADING NORMALLY...
	
	define( 'PUSHLIVE__WEBSITE', 'http://1squared.com/pushlive' );
	define( 'PUSHLIVE__PLUGINSITE', 'https://wordpress.org/plugins/pushlive/' );
	
	define( 'PUSHLIVE__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'PUSHLIVE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	
	define( 'PUSHLIVE__INCLUDES', PUSHLIVE__PLUGIN_DIR . 'includes/' );
	define( 'PUSHLIVE__INCLUDES_URL', PUSHLIVE__PLUGIN_URL . 'includes/' );
	
	define( 'PUSHLIVE__TABLES', PUSHLIVE__PLUGIN_DIR . 'tables/' );
	define( 'PUSHLIVE__VIEWS', PUSHLIVE__PLUGIN_DIR . 'views/' );
	define( 'PUSHLIVE__SETTINGS', PUSHLIVE__PLUGIN_DIR . 'settings/' );
	
	define( 'PUSHLIVE__STORAGE', ABSPATH . 'wp-content/pushlive/' ); //seems safer than the uploads file which is public access
	define( 'PUSHLIVE__BACKUPS', PUSHLIVE__STORAGE . 'backups/' );
	define( 'PUSHLIVE__OUTPUT', PUSHLIVE__STORAGE . 'output/' );
	
	global $wpdb;
	define( 'PUSHLIVE_DB_TABLE', $wpdb->base_prefix . 'pushlive' );
	//if( !get_site_option( 'pushlive_lock_pushing' ) || $wpdb->prefix == $wpdb->base_prefix ):
	
	
	//Detect Windows
	$sys = strtoupper(PHP_OS);
    if( substr( $sys, 0, 3 ) == "WIN" ){
        $win = true;
    }else{
    	$win = false;
    }
    define( 'WINDOWS_OS', $win );
    //define( 'WINDOWS_OS', false ); //for overriding
    
    

	//Since PushLive is a backend administrator tool, we're not going to do anything unless the user is an admin
	if ( is_admin() ){
	
		require_once ( PUSHLIVE__PLUGIN_DIR . 'class.pushlive-admin.php' );
		add_action( 'init', array( 'PushLive_Admin', 'init' ) );


		//settings pages...
		$page = ( !empty( $_GET['page'] ) ) ? $_GET['page'] : $_POST['option_page'] ;	
		switch ( $page ){ 
			case 'pushlive-admin-options':
				require_once( PUSHLIVE__SETTINGS . 'admin-settings.php' );
				add_action ( 'admin_init', 'register_admin_settings' );
			break;
			case 'pushlive-replicate-options':
				require_once( PUSHLIVE__SETTINGS . 'replicate-settings.php' );
				add_action ( 'admin_init', 'register_replicate_settings' );
			break;
		} 
		
	}
	
	//PLUGINS MANAGER PAGE LINKS
	function plugin_add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=pushlive-admin-options">' . __( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
	
	//ACTIVATION ONLY...
	register_activation_hook( __FILE__ , 'pushlive_activate' );


function pushlive_activate(){

	global $wpdb;

	$table_name = PUSHLIVE_DB_TABLE;

	$sql = "CREATE TABLE $table_name (
		pushId int(11) unsigned NOT NULL AUTO_INCREMENT,
		pushTime datetime NOT NULL,
		user int(10) unsigned NOT NULL,
		config text NOT NULL,
		rsync text NOT NULL,
		mysql text NOT NULL,
		siteId int(11) unsigned NOT NULL,
		PRIMARY KEY ( pushId )
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta( $sql );

}








