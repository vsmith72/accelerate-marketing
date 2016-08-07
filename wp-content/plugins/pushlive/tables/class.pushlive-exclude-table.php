<?php
	defined( 'ABSPATH' ) or die();
	
	
	class Pushlive_Exclude_Table{

		function __construct(){
			//do nothing for now...
		}
		
		public function get_tables(){
			global $wpdb;

			
			//<<<Edited for Multisite...
			$is_main = ( $wpdb->prefix == $wpdb->base_prefix ) ? true : false; //is main site for multisite?
			
			if($is_main){
				$expr = '/^' . preg_quote( $wpdb->prefix, '/' ) . '[0-9]+\_/';
			}
			
			$current_prefix = str_replace( '_', '\_', $wpdb->prefix ) . '%';
			
			$prefix_like = " LIKE '" . $current_prefix . "'";
			
			$results = $wpdb->get_results( "SHOW TABLES" . $prefix_like );
			//>>>
			
			$tables = array();
			
			if( $results ){ 

				foreach( $results as $key => $result ){
					
					//<<<Edited For Multisite...
					$table_name = $result->{ 'Tables_in_' . $wpdb->dbname . ' (' . $current_prefix . ')' };
					
					if( !$is_main || preg_match( $expr, $table_name ) !== 1 ){
						$tables[] = $table_name;
					}
					//>>>
					
				}

			}

			return $tables;
		}
		
	}