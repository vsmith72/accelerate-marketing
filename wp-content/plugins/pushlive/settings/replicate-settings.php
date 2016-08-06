<?php
	defined( 'ABSPATH' ) or die();
	
	//for handling ajax
	
	add_action( 'wp_ajax_test_replicate_database', 'test_replicate_database_callback' );
		add_action( 'admin_footer', 'test_replicate_database_javascript' );
	add_action( 'wp_ajax_begin_replication', 'begin_replication_callback' );
		add_action( 'admin_footer', 'begin_replication_javascript' );
	
	
			 function register_replicate_settings(){
			 	
			 	global $wpdb;
			 	
				//OKAY WE KNOW IT WORKS... NOW MAKE IT WORK!
				
			 	 	
			 	//If Multisite Parent Site or Single Site $is_main will be set to true
			 	$is_main = ( $wpdb->prefix == $wpdb->base_prefix ) ? true : false;
			 	if( $is_main ){
			 		
			 	add_settings_section( 'pushlive_replicate_general', 'Replicate Your Site', 'replicate_general_settings_callback', 'pushlive-replicate-options' );
			 		
			 		/* Possible future use...
			 		add_settings_field( 'pushlive_live_domain', 'Live Domain', 'live_domain_callback', 'pushlive-admin-options', 'pushlive_admin_general' );
			 			register_setting( 'pushlive-admin-options', 'pushlive_live_domain' );
			 		*/
			 	
			 	add_settings_field( 'pushlive_replicate_whatis', 'What is PushLive Replicate?', 'replicate_whatis', 'pushlive-replicate-options', 'pushlive_replicate_general' );
			 	add_settings_field( 'pushlive_replicate_whento', 'When to Use PushLive Replicate?', 'replicate_whento', 'pushlive-replicate-options', 'pushlive_replicate_general' );
			 	add_settings_field( 'pushlive_replicate_instructions', 'Before You Replicate You\'ll Need to Create a...', 'replicate_instructions', 'pushlive-replicate-options', 'pushlive_replicate_general' );
			 	
		
					add_settings_field( 'pushlive_replicate_live_base', 'New Site Base', 'replicate_live_base_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_live_base' );
						update_site_option( 'pushlive_replicate_live_base', get_option( 'pushlive_replicate_live_base' ) );
				
					add_settings_field( 'pushlive_replicate_db_host', 'New Database Host', 'replicate_db_host_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_db_host' );
						update_site_option( 'pushlive_replicate_db_host', get_option( 'pushlive_replicate_db_host' ) );

					add_settings_field( 'pushlive_replicate_db_db', 'New Database Name', 'replicate_db_db_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_db_db' );
						update_site_option( 'pushlive_replicate_db_db', get_option( 'pushlive_replicate_db_db' ) );
												
					add_settings_field( 'pushlive_replicate_db_user', 'New Database User', 'replicate_db_user_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_db_user' );
						update_site_option( 'pushlive_replicate_db_user', get_option( 'pushlive_replicate_db_user' ) );
					
					add_settings_field( 'pushlive_replicate_db_pw', 'New Database Password', 'replicate_db_pw_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_db_pw' );
						update_site_option( 'pushlive_replicate_db_pw', get_option( 'pushlive_replicate_db_pw' ) );
						
					add_settings_field( 'pushlive_replicate_install_path', 'New Install Path', 'replicate_db_install_path_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						register_setting( 'pushlive-replicate-options', 'pushlive_replicate_install_path' );
						update_site_option( 'pushlive_replicate_install_path', get_option( 'pushlive_replicate_install_path' ) );
				
					add_settings_field( 'pushlive_replicate_start_button', '', 'replicate_start_button_callback', 'pushlive-replicate-options', 'pushlive_replicate_general' );
						
			 	}
		
			}
			
			//Though fields are marked required for client side, I'm not currently checking them on the server side as this is an admin app only			
			function replicate_general_settings_callback(){
				/* nothing for now */
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
				function replicate_whatis(){
					echo "<div>An easy way for you to create a new staging area from this WordPress installation or to simply replicate/duplicate it for any number of reasons.</div>";
				}
				
				function replicate_whento(){
					echo '<div>Only to create a replica of your site - For normal stage to live pushes use the <a href="admin.php?page=pushlive-admin-tool" class="wp-first-item">PushLive</a> option</div>';
				}
				
				function replicate_instructions(){
					echo "<div>1. Target folder to replicate to.</div>";
					echo "<div>2. Domain and path set up that points to that folder.</div>";
					echo "<div>3. New/target database with a full rights user.</div>";
				}
							
				function replicate_live_base_callback(){
					$current_base = preg_replace( '/http[s]*\:\/\//i', '', get_bloginfo( 'wpurl' ) );
					
					echo '<div>Current Base Domain is:&nbsp;&nbsp;'. $current_base . '</div>';
					echo '<div>You can either use the same or change it to something else you\'ve set up to use</div>';
					echo '<input name="pushlive_replicate_live_base" 
							type="text" 
							value="' . get_site_option( 'pushlive_replicate_live_base', '' ) . '" 
							placeholder="example: new.example.com" />';
				}
							
				function replicate_db_host_callback(){
					echo '<input name="pushlive_replicate_db_host" 
							type="text" 
							value="' . get_site_option( 'pushlive_replicate_db_host' ) . '" 
							placeholder="example: localhost" required />';	
				}
				
				function replicate_db_user_callback(){
					echo '<input name="pushlive_replicate_db_user" 
							type="text" 
							value="' . get_site_option( 'pushlive_replicate_db_user' ) . '" 
							placeholder="example: dbusername" />';
				}
				
				function replicate_db_db_callback(){
					echo '<input name="pushlive_replicate_db_db" 
							type="text" 
							value="' . get_site_option( 'pushlive_replicate_db_db' ) . '" 
							placeholder="example: wpdatabase" required/>';
				}
				
				function replicate_db_pw_callback(){
					echo '<input name="pushlive_replicate_db_pw" 
							type="password" 
							value="' . get_site_option( 'pushlive_replicate_db_pw' ) . '" 
							placeholder="" />';
					echo '<input type="button" 
							class="button button-primary test-database-button" 
							value="Test Database">';
					echo '<span class="test-database-results"></span>';

				}
				
				function replicate_db_install_path_callback(){
					echo '<div>This is the folder we\'re replicating to - Ideally the target folder shouldn\'t have anything in it</div>';
					echo '<input name="pushlive_replicate_install_path" 
							type="text" 
							value="' . get_site_option( 'pushlive_replicate_install_path' ) . '" 
							placeholder="example: /var/www/example.com/public_html" 
							required />';
				}
				
				function replicate_start_button_callback(){
					echo '<input type="button"
						class="button button-primary Xreplicate-start-button"
						value="ENABLED SOON! Replicate Now" disabled>';
					echo '<div class="replication-status"></div>';
					echo '<div>Please note: Replication might take a while (especially for large sites) -  once you push the button take a deserving break.</div>';
					echo '<div>If you get impatient and leave/reload this page prematurely - don\'t worry - replication will begin where it last left off when you click the Replicate Now button again</div>';
					
				}
				
	
				function test_replicate_database_callback(){
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
				
				function begin_replication_callback(){
					switch( $_POST['step'] ){
						case 0:
							echo "<div>STARTING REPLICATION: Please wait...</div>";
							echo "<div>Replicating Files...</div>";
							break;
						case 1:
							require_once PUSHLIVE__INCLUDES . 'class.pushlive-rsync.php';
							$rsync = new Pushlive_RSync();
							$rsync->setSource( ABSPATH );
							$rsync->setDestination( $_POST['path'] );
							$rsync->excludes = array();
							$rsync->excludeFile = '';
							$rsync->execute();
							
							if( isset( $rsync->getRunInfo()['return']['transfer'] ) ){
								echo "<div>" . $rsync->getReturnStatus( $rsync->getRunInfo()['return']['transfer'] ) . "</div>";
							}else{
								echo 0;
								break;
							}
														
							echo '<div>Replicating Database...</div>';
							break;
						case 2:
							echo "<div>STEP 2</div>";
							break;
						case 3:
							echo "1";
							break;
						default:
							echo "0";	
					}
					wp_die();
					
				}
				
				function begin_replication_javascript(){ ?>
					<script type="text/javascript" >
					var stepG = 0; 
					jQuery(document).ready(function($) {
						$('input').change(function(){
							$('.replicate-start-button')
								.prop("disabled",true)
								.val('Save First Then Replicate');
						});
						$('.replicate-start-button').click(function(){
							$(this).prop("disabled",true);
							
							$('.replication-status').show();
							
							var AjaxReplicate = function(){
								var ipfx = 'input[name=pushlive_replicate_';
								$.ajax({
									method: "POST",
									url: ajaxurl,
									data: {
										action: 'begin_replication',
										option_page: 'pushlive-replicate-options',
										base: $(ipfx + 'live_base]').val(),
										host: $(ipfx + 'db_host]').val(),
										db: $(ipfx + 'db_db]').val(),
										user: $(ipfx + 'db_user]').val(),
										pass: $(ipfx + 'db_pw]').val(),
										path: $(ipfx + 'install_path]').val(),
										'step': stepG					
									}
								}).done(function( html ) {
									if( html != 0 && html != 1 ){
										$('.replication-status').append( html );
										stepG++;
										AjaxReplicate();
									}else{
										if( html == 1 ){
											$('.replication-status').append( '<div>FINISHED!</div>' );
										}else{
											$('.replication-status').append( '<div>AN ERROR OCCURED!</div>' );
										}
									}
									
								}).fail(function(){
									$('.replication-status').append( '<div>Replication FAILED</div>' );
								});
							};

							AjaxReplicate( stepG );
						});
					});
					</script><?php 
				}
				
				function test_replicate_database_javascript(){ ?>
					<script type="text/javascript" >
						jQuery(document).ready(function($) {
							$('input').change(function(){
								$('.replicate-start-button').prop("disabled",true);
								$('.replicate-start-button').val('Save First Then Replicate');
							});
							
							$('.test-database-button').click(function(){
								var ipfx = 'input[name=pushlive_replicate_';
								$.ajax({
									method: "POST",
									url: ajaxurl,
									data: {
										action: 'test_replicate_database',
										option_page: 'pushlive-replicate-options',
										host: $(ipfx + 'db_host]').val(),
										db: $(ipfx + 'db_db]').val(),
										user: $(ipfx + 'db_user]').val(),
										pass: $(ipfx + 'db_pw]').val()	
														
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
				
