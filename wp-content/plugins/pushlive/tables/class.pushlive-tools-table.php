<?php
	defined( 'ABSPATH' ) or die();
	
	
	class PushLive_Tools_Table{
		
		public $pushId;
		public $pushTime;
		public $user;
		public $config;
		public $rsync;
		public $mysql;
		
		public function __construct(){
			//do nothing for now
		}
		
		public function store(){
			global $wpdb;
			
			$this->pushTime = current_time( 'mysql', 0 );
			$this->user = get_current_user_id();
			
			$table_name = PUSHLIVE_DB_TABLE;
			
			$data = array( 
					'pushTime' => $this->pushTime, 
					'user' => $this->user,
					'config' => $this->config, 
					'rsync' => $this->rsync,
					'mysql' => $this->mysql,	
					'siteId' => get_current_blog_id()		
			);
			
			$result = $wpdb->insert( $table_name, $data );
			
			return $result;
			
			
		}
		
		
		public function get_history( $start = 0, $limit = 50 ){
			global $wpdb;
			
			$table_name = PUSHLIVE_DB_TABLE;
			$siteId = ( $wpdb->base_prefix == $wpdb->prefix ) ? '' : "WHERE siteId=" . get_current_blog_id() . " " ;
			
			$results = $wpdb->get_results( "SELECT pushId, pushTime, user, siteId
											FROM $table_name 
											$siteId
											ORDER BY pushId DESC
											LIMIT $start, $limit	
			" );
			
			return $results;
			
		}
		
	}