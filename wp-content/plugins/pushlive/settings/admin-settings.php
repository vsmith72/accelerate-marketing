<?php
	defined( 'ABSPATH' ) or die();
	
	//for handling ajax
	
	add_action( 'wp_ajax_test_database', 'test_database_callback' );
	
	
			 function register_admin_settings(){
			 	
			 	global $wpdb;
			 	

			 	 	
			 	//If Multisite Parent Site or Single Site $is_main will be set to true
			 	$is_main = ( $wpdb->prefix == $wpdb->base_prefix ) ? true : false;
			 	if( $is_main ){
			 		
			 	add_settings_section( 'pushlive_admin_general', 'General Settings', 'admin_general_settings_callback', 'pushlive-admin-options' );
			 		
			 		/* Possible future use...
			 		add_settings_field( 'pushlive_live_domain', 'Live Domain', 'live_domain_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
			 			register_setting( 'pushlive-admin-options', 'pushlive_live_domain' );
			 		*/
					if ( is_multisite() ){
						add_settings_field( 'pushlive_lock_pushing', 'Disable Pushing For All Users/Sites', 'lock_pushing_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
							register_setting( 'pushlive-admin-options', 'pushlive_lock_pushing' );
							update_site_option('pushlive_lock_pushing', get_option('pushlive_lock_pushing') );
							register_setting( 'pushlive-admin-options', 'pushlive_lock_reason' );
							update_site_option('pushlive_lock_reason', get_option('pushlive_lock_reason') );
					}
				
					add_settings_field( 'pushlive_force_login', 'Require Login When Base URL Contains', 'force_login_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_force_login' );
						update_site_option('pushlive_force_login', get_option('pushlive_force_login') );
						
					add_settings_field( 'pushlive_stage_base', 'Staging Site Base', 'stage_base_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_stage_base' );
						update_site_option( 'pushlive_stage_base', get_option( 'pushlive_stage_base' ) );
						
					add_settings_field( 'pushlive_live_base', 'Live Site Base', 'live_base_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_live_base' );
						update_site_option( 'pushlive_live_base', get_option( 'pushlive_live_base' ) );
				
					add_settings_field( 'pushlive_db_host', 'Live Database Host', 'db_host_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_db_host' );
						update_site_option( 'pushlive_db_host', get_option( 'pushlive_db_host' ) );

					add_settings_field( 'pushlive_db_db', 'Live Database Name', 'db_db_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_db_db' );
						update_site_option( 'pushlive_db_db', get_option( 'pushlive_db_db' ) );
												
					add_settings_field( 'pushlive_db_user', 'Live Database User', 'db_user_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_db_user' );
						update_site_option( 'pushlive_db_user', get_option( 'pushlive_db_user' ) );
					
					add_settings_field( 'pushlive_db_pw', 'Live Database Password', 'db_pw_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_db_pw' );
						update_site_option( 'pushlive_db_pw', get_option( 'pushlive_db_pw' ) );
						
					add_settings_field( 'pushlive_install_path', 'Live Install Path', 'db_install_path_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_install_path' );
						update_site_option( 'pushlive_install_path', get_option( 'pushlive_install_path' ) );
					
					add_settings_field( 'pushlive_backup_path', 'Backup Location Path', 'backup_path_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
						register_setting( 'pushlive-admin-options', 'pushlive_backup_path' );
						update_site_option( 'pushlive_backup_path', get_option( 'pushlive_backup_path' ) );
			 	}else{
			 		//If is a Multisite child site
			 		add_settings_section( 'pushlive_admin_is_multisite', '', 'admin_is_multisite_settings_callback', 'pushlive-admin-options' );

			 	}
				
				add_settings_section( 'pushlive_admin_exclude', 'Exclusion Settings', 'admin_exclude_settings_callback', 'pushlive-admin-options' );
					
					if ($is_main ){
					add_settings_field( 'pushlive_exclusions', '', 'exclude_files_callback', 'pushlive-admin-options', 'pushlive_admin_exclude' );
						register_setting( 'pushlive-admin-options', 'pushlive_exclusions' );
						update_site_option( 'pushlive_exclusions', get_option( 'pushlive_exclusions' ) );
					}
					add_settings_field( 'pushlive_exclude_tables', '', 'exclude_tables_callback', 'pushlive-admin-options', 'pushlive_admin_exclude' );
						register_setting( 'pushlive-admin-options', 'pushlive_exclude_tables' );
					
			}
			
			//Though fields are marked required for client side, I'm not currently checking them on the server side as this is an admin app only			
			function admin_general_settings_callback(){
				//echo '<h3>General</h3>';
			}
			function admin_is_multisite_settings_callback(){
				echo "<div><strong>Multi-Site Notice:</strong>This site belongs to a Multisite installation. If PushLive isn't working correctly it's possible the PushLive Options aren't properly set up in the Master Site PushLive settings.</div>";
			}
			
				function lock_pushing_callback(){
						
					$selected = get_site_option( 'pushlive_lock_pushing' ) ? 'checked' : '';
					echo '<div>';
					echo '<input type="checkbox" name="pushlive_lock_pushing" 
							value="1" ' . $selected . '>Disable Pushing For Everyone Else</div>';
					echo 'Reason:<div><input name="pushlive_lock_reason"
							type="text"
							value="' . get_site_option( 'pushlive_lock_reason', '' ) . '"
							placeholder="example: Updating Sites" /></div>';
					

				}
				
				/*
				 * This is an idea that other plugins can handle and is not yet a PushLive feature
				function live_domain_callback(){

					echo '<div>If you own another domain and have set it up to be used with this site\'s IP Address enter it in here.</div>';
					echo '<input name="pushlive_live_domain" 
							type="text" 
							value="' . get_option( 'pushlive_live_domain', '' ) . '" 
							placeholder="www.example.com or example.com (leave/reset to blank if not setup)" />';
	
				}
				*/
				
				function force_login_callback(){
					
					echo '<div>Current Base URL is:&nbsp;&nbsp;'.preg_replace( '/http[s]*\:\/\//i', '', get_bloginfo( 'wpurl' ) ) . '</div>'; 
					echo '<input name="pushlive_force_login" 
							type="text" 
							value="' . get_site_option( 'pushlive_force_login', 'stage.' ) . '" 
							placeholder="example: stage." />';
				}
				
				function stage_base_callback(){
					echo '<div>All occurances of this in the live database will be replaced by Live Site Base below</div>';
					echo '<input name="pushlive_stage_base" 
							type="text" 
							value="' . get_site_option( 'pushlive_stage_base', '' ) . '" 
							placeholder="example: stage.example.com" />';
				}
				
				function live_base_callback(){
					echo '<div>Replace all occurances of Staging Site Base in live site with this</div>';
					echo '<input name="pushlive_live_base" 
							type="text" 
							value="' . get_site_option( 'pushlive_live_base', '' ) . '" 
							placeholder="example: example.com" />';
				}
							
				function db_host_callback(){
					echo '<input name="pushlive_db_host" 
							type="text" 
							value="' . get_site_option( 'pushlive_db_host' ) . '" 
							placeholder="example: localhost" required />';	
				}
				
				function db_user_callback(){
					echo '<input name="pushlive_db_user" 
							type="text" 
							value="' . get_site_option( 'pushlive_db_user' ) . '" 
							placeholder="example: dbusername" />';
				}
				
				function db_db_callback(){
					echo '<input name="pushlive_db_db" 
							type="text" 
							value="' . get_site_option( 'pushlive_db_db' ) . '" 
							placeholder="example: wpdatabase" required/>';
				}
				
				function db_pw_callback(){
					echo '<input name="pushlive_db_pw" 
							type="password" 
							value="' . get_site_option( 'pushlive_db_pw' ) . '" 
							placeholder="" />';
					echo '<input type="button" 
							class="button button-primary test-database-button" 
							value="Test Database">';
					echo '<span class="test-database-results"></span>';
					add_action( 'admin_footer', 'test_database_javascript' );

				}
				
				function db_install_path_callback(){
					echo '<div>This should be the root folder of your live WordPress installation</div>';
					echo '<input name="pushlive_install_path" 
							type="text" 
							value="' . get_site_option( 'pushlive_install_path' ) . '" 
							placeholder="example: /var/www/example.com/public_html" 
							required />';
				}
				
				function backup_path_callback(){
					echo '<div>This should be a safe existing location on your server that is not accessable from the web ( a pushlive folder will be auto created here )</div>';
					echo '<input name="pushlive_backup_path"
							type="text"
							value="' . get_site_option( 'pushlive_backup_path' ) . '"
							placeholder="example: /var/www/"
							required />';
				}
				
				function exclude_files_callback(){
					
					$default_exclusions = 
						"wp-config.php\n" .
						"robots.txt\n" .
						".file_list";
					
					$current = trim( get_site_option( 'pushlive_exclusions' ) );
					
					$exclusions = ( !empty( $current ) ) ? $current : $default_exclusions;
					echo '<div>Add WordPress root relative paths to any files you do not want to push to the live site ( each on separate lines ).</div>';
					echo '<div><strong>wp-config.php</strong> should be in this list</div>';
					echo '<textarea name="pushlive_exclusions" class="pushlive">' . $exclusions . '</textarea>';
				}
				
				
				function test_database_callback(){
					$wpdbtest = new wpdb(
							$_POST['user'],
							$_POST['pass'],
							$_POST['db'],
							$_POST['host']
					);
					echo '<span class="test-database-result-';
					echo $wpdbtest->error ? "error" : "good";
					echo '"></span>';
					wp_die();
				}
				function test_database_javascript(){ ?>
					<script type="text/javascript" >
						jQuery(document).ready(function($) {
							$('.test-database-button').click(function(){
								$.ajax({
									method: "POST",
									url: ajaxurl,
									data: {
										action: 'test_database',
										option_page: 'pushlive-admin-options',
										host: $('input[name=pushlive_db_host]').val(),
										db: $('input[name=pushlive_db_db]').val(),
										user: $('input[name=pushlive_db_user]').val(),
										pass: $('input[name=pushlive_db_pw]').val()						
									}
								}).done(function( html ) {
									$('.test-database-results').html( html );
								}).fail(function(){
									$('.test-database-results').html( ' Can\'t Test - Check Connection ' );
								});
						    });
						});
					</script><?php 
				}
				
				function exclude_tables_callback(){
					require_once( PUSHLIVE__TABLES . 'class.pushlive-exclude-table.php' );
					require_once( PUSHLIVE__VIEWS . 'exclude.php' );
				}