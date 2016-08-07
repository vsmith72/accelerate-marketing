<?php
	defined( 'ABSPATH' ) or die();
	
	
	class PushLive_Admin{
		
		
		function init(){
			
			wp_enqueue_script( 'jquery' );
			
			wp_enqueue_style( 'pushlive-admin', PUSHLIVE__INCLUDES_URL . 'admin.css');
			
			require_once( PUSHLIVE__TABLES . 'class.pushlive-admin-table.php' );
			
			//Add PushLive Links to Wordpress Tool Menu Section
			add_action( 'admin_menu', array( 'PushLive_Admin', 'pushlive_menu' ) );
			add_action( 'network_admin_menu', array( 'PushLive_Admin', 'pushlive_menu' ) );
				
		}
		
		
		function pushlive_menu(){
			
			add_menu_page( 'PushLive', 'PushLive', 'manage_options', 'pushlive-admin-tool', array( 'PushLive_Admin', 'pushlive_tools' )  );
			
			add_submenu_page('pushlive-admin-tool','PushLive Setup', 'Setup', 'manage_options', 'pushlive-admin-options',  array( 'PushLive_Admin', 'pushlive_options' ) );
			
			global $wpdb;
			if ( $wpdb->prefix == $wpdb->base_prefix ) {
				add_submenu_page('pushlive-admin-tool','PushLive Replicate', 'Replicate (Soon!)', 'manage_options', 'pushlive-replicate-options',  array( 'PushLive_Admin', 'pushlive_replicate' ) );
			}				
			
		}
		

		
		function pushlive_tools(){
			
			if( WINDOWS_OS ){
				echo '<h1>Sorry, PushLive Does Not Work On Windows Servers</h1>';
				return false;
			}
			
			require_once( PUSHLIVE__TABLES . 'class.pushlive-tools-table.php' );
			$table = new PushLive_Tools_Table();
			
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			global $wpdb;
			
			if( isset( $_POST[ 'pushlive-now' ] ) && ( !get_site_option( 'pushlive_lock_pushing' ) || $wpdb->prefix == $wpdb->base_prefix ) ){

				require_once( PUSHLIVE__INCLUDES . 'class.pushlive-dbsync.php' );
				require_once( PUSHLIVE__INCLUDES . 'class.pushlive-rsync.php' );

				$dbsync = new Pushlive_DBSync();
				$rsync = new Pushlive_RSync();

				$rsync->setSource( ABSPATH );
				$rsync->setDestination( get_site_option('pushlive_install_path') );
				$rsync->setExcludes( explode( "\n", get_site_option( 'pushlive_exclusions' , 'wp_config.php' ) ) );
				
				if( $dbsync->execute() ){
					$dbsync->fixdata();
				}
				
				$rsync->execute();
				
				$table->rsync = json_encode( $rsync->getRunInfo() );
				$table->mysql = json_encode( $dbsync->getRunInfo() );
				$table->config = json_encode( array(
						'db_host' => get_site_option( 'pushlive_db_host' ),
						'db_user' => get_site_option( 'pushlive_db_user' ),
						'db_pw' => get_site_option( 'pushlive_db_pw' ),
						'db_db' => get_site_option( 'pushlive_db_db' ),
						'install_path' => get_site_option( 'pushlive_install_path' )
				) );
				
				$table->store();
				
				
				echo "<hr><div>Push Complete - Please Confirm by Checking the Live Site</div><hr>";
				
				$results = array();
				$results['database'] = array_map( function( $code ) use ( &$dbsync ){ return $dbsync->getReturnStatus( $code ); }, $dbsync->return);
				$results['filesystem'] = array_map( function( $code ) use ( &$rsync ){ return $rsync->getReturnStatus( $code ); }, $rsync->return);
				
				include( PUSHLIVE__VIEWS . 'results.php' );
				
				
			}
			
			include( PUSHLIVE__VIEWS . 'tools.php' );
			include( PUSHLIVE__VIEWS . 'history.php' );
		}
		
		function pushlive_replicate(){
				
			if( WINDOWS_OS ){
				echo '<h1>Sorry, PushLive Does Not Work On Windows Servers</h1>';
				return false;
			}
				
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
				
			include( PUSHLIVE__VIEWS . 'replicate.php' );
				
		}
		
		function pushlive_options(){
			
			if( WINDOWS_OS ){
				echo '<h1>Sorry, PushLive Does Not Work On Windows Servers</h1>';
				return false;
			}
			
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			
			include( PUSHLIVE__VIEWS . 'options.php' );
		}
		

		
		
	}