<?php defined( 'ABSPATH' ) or die();
/*
 * Converted from Old System July 13, 2015
 */
class Pushlive_DBSync {
	protected $excludes = array();	//Actually "includes" - sorry for the confustion
	protected $host;
	protected $user;
	protected $password;
	protected $database;

	protected $source_host;
	protected $source_user;
	protected $source_password;
	protected $source_database;
	
	protected $dumpfile;
	protected $backupfile;
	protected $importfile;

	protected $output;
	protected $cmds = array();
	public $return = array();
	
	protected $livefind;
	protected $livereplace;
	protected $liveDB;

	public function __construct($config = array()) {
		
		ini_set('memory_limit', '4096M'); //fixes memory issues for large sites up to 4 GIGS
		foreach($config as $var => $val) {
			if(property_exists($this, $var)) {
				$this->{$var} = $val;
			}
		}

		//This remains the same "get_option" for multisite or single site
		$this->excludes = get_option( 'pushlive_exclude_tables' );
		
		//For Multisite - Changed get_option to get_site_option
		$this->host = get_site_option( 'pushlive_db_host' );
		$this->user = get_site_option( 'pushlive_db_user' );
		$this->password = get_site_option( 'pushlive_db_pw' );
		$this->database = get_site_option( 'pushlive_db_db' );
		
		$this->source_host = DB_HOST;
		$this->source_user = DB_USER;
		$this->source_password = DB_PASSWORD;
		$this->source_database = DB_NAME;
		
		
		
	}

	public function execute() {
		return $this->dump() && $this->import();
	}
	
	public function fixdata(){
		
		$this->liveDB = new wpdb( $this->user, $this->password, $this->database, $this->host );
		
		
		
		$stagebase = trim( get_site_option( 'pushlive_stage_base' ) );
		$livebase = trim( get_site_option( 'pushlive_live_base' ) );
		
		if( !empty( $stagebase ) ){
			$somesuccess = false;
			$somefailures = false;
			
			//FOR Replicate you'll need to re-use this...
			//$tables = $this->get_tables(); //get all tables
			$tables = $this->excludes; //only the selected tables
			
			
		
			foreach( $tables as $key => $table ){
				
				$fields = $this->get_fields( $table );
				//echo "<hr>$table<br>";
				foreach( $fields as $fkey => $field ){
					//echo "$field | ";
					$query = "UPDATE `$table` SET `$field` = REPLACE( `$field`, '$stagebase', '$livebase' );";
					$result = $this->liveDB->query( $query );
					if( $result !== false ){
						$somesuccess = true;
					}else{
						$somefailures = true;
					}	
				}	
			}
		}else{
			$this->return['replace'] = 901;
			return false;
		}
		//die();
		if( $somesuccess && $somefailures ){
			$this->return['replace'] = 902;
		}elseif( !$somesuccess && !$somefailures ){
			$this->return['replace'] = 903;
		}elseif ( $somesuccess ){
			$this->return['replace'] = 900;
		}else{
			//only failures	
			$this->return['replace'] = 904;
		}
		
	
	}
	
	public function get_tables(){
				
		$results = $this->liveDB->get_results( "SHOW TABLES" );
		
		$tables = array();
		
		if( $results ){
			foreach( $results as $key => $result ){
				$tables[] = $result->{ 'Tables_in_' . $this->liveDB->dbname };
			}
		}
		
		return $tables;
			
	}
	
	public function get_fields($table){
		
		$fields = array();
		
		foreach( $this->liveDB->get_col( 'DESC ' . $table, 0 ) as $field ){
			$fields[] = $field;
		}
		
		return $fields;
		
	}
	
	public function getReturnStatus($code){
	
		switch ($code) {
			case 0: return 'Success';
			case 900: return 'Success';
			case 901: return 'Not Applied - No Staging Base in Settings';
			case 902: return 'Partial Success - Some Query Errors';
			case 903: return 'Fail - Database Empty or Inaccessible';
			case 904: return 'Fail - Bad Query';
			case 1000: return 'Fail - No Backup Path Rights';
			default: return 'Unknown - Possible Error';
		}
	
	}

	public function getCommands() {
		return $this->cmds;
	}

	public function getExcludes() {
		return $this->excludes;
	}

	public function getDumpFilename() {
		if(!isset($this->dumpfile)) {
			$filename = $this->getBackupDirectory(); 
			$filename .= 'dump.'.$this->source_database.'.'.date('Ymd-His').'.sql';
			$this->dumpfile = $filename;
		}

		return $this->dumpfile;
	}

	public function getBackupFilename() {
		if(!isset($this->backupfile)) {
			$filename = $this->getBackupDirectory(); 
			$filename .= 'backup.'.$this->database.'.'.date('Ymd-His').'.sql';
			$this->backupfile= $filename;
		}

		return $this->backupfile;
	}

	public function getImportFilename() {
		if(!isset($this->importfile)) {
			$filename = $this->getBackupDirectory(); 
			$filename .= 'import.'.$this->database.'.'.date('Ymd-His').'.sql';
			$this->importfile = $filename;
		}

		return $this->importfile;
	}

	protected function checkBackupFolders($dir){
		//Create Necessary Folders if they don't exist
		$folders = [ 'pushlive', 'pushlive/backups', 'pushlive/output' ];
		foreach($folders as $key => $folder){
			if ( !file_exists( $dir . $folder ) ) {
				if( !mkdir( $dir . $folder , 0770, true ) ){
					$this->return['backup-path'] = 1000;
				}
			}
		}
	}	
	
	protected function getBackupDirectory() {
		//For Multisite or Single Site
		$dir = rtrim( get_site_option( 'pushlive_backup_path' ), '/' ) . '/'; //make sure it ends with triling slash
		
		$this->checkBackupFolders($dir);
		$dir .= 'pushlive/backups/';
		return $dir;
	}

	public function getOutput() {
		return is_array($this->output) ? implode("\n", $this->output) : $this->output;
	}

	public function getFiles() {
		return array(
			'dump' => $this->getDumpFilename(),
			'backup' => $this->getBackupFilename(),
			'import' => $this->getImportFilename(),
		);
	}

	public function getRunInfo() {
		$output = $this->getOutput();
		$cmd = $this->getCommands();
		$files = $this->getFiles();

		return array(
			'cmds' => $cmd,
			'output' => $output,
			'files' => $files,
			'return' => $this->return,				
		);
	}

	public function setExcludes($excludes) {
		$this->excludes = $excludes;
	}

	public function setHost($host) {
		$this->host = $host;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setPassword($pass) {
		$this->password = $pass;
	}

	public function setDatabase($database) {
		$this->database = $database;
	}

	public function addExclude($exclude) {
		$this->excludes[] = $exclude;
	}

	public function addCommand($cmd, $for) {
		$this->cmds[$for] = $cmd;
	}

	public function addOutput($output) {
		$this->output[] = $output;
	}

	protected function dump() {
		$cmd = $this->buildDumpCommand();

		$result = exec($cmd, $output, $return);

		$this->addCommand($cmd, 'dump');
		//@todo figure out what this $output should/could be
		@$this->addOutput($output);
		
		$this->return['dump'] = $return;

		return true;
	}

	protected function backup() {
		$cmd = $this->buildBackupCommand();
		$result = exec($cmd, $output, $return);

		$this->addCommand($cmd, 'backup');
		//@todo figure out what this $output should/could be
		@$this->addOutput($output);
		
		$this->return['backup'] = $return;

		return true;
	}

	protected function import() {
		if($this->backup()) {
			//echo "<hr>{$this->getDumpFilename()}<hr>"; die();
			
			$dumpfile = file_get_contents($this->getDumpFilename());

			//if you get an error (perhaps Allowed Memory Size) pointing near here
			//simply up the ini memory_limit located within the __construct above
			$importfile = $dumpfile;

			if(file_put_contents($this->getImportFilename(), $importfile)) {
				$cmd = $this->buildImportCommand();
				$result = exec($cmd, $output, $return);

				$this->addCommand($cmd, 'import');
				$this->addOutput($output);
				$this->return['import'] = $return;

				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	protected function buildBackupCommand() {
		$cmd = "mysqldump -u {$this->user} --password='" . addslashes( $this->password ) . "' {$this->database}";
		$filename = $this->getBackupFilename();

		$cmd .= " > {$filename}";

		return $cmd;
	}

	protected function buildImportCommand() {
		$cmd = "mysql -u {$this->user} --password='" . addslashes( $this->password ) . "' {$this->database}";
		$filename = $this->getImportFilename();

		$cmd .= " < {$filename}";

		return $cmd;
	}

	protected function buildDumpCommand() {
		$cmd = "mysqldump -u {$this->source_user} --password='" . addslashes( $this->source_password ) . "' {$this->source_database}";
		$filename = $this->getDumpFilename();

		//It's actually includes here - sorry for any confusion in using excludes (wasn't my idea ;)
		foreach($this->excludes as $exclude) {
			//if($exclude->status == 1) {
				$cmd .= " {$exclude}";
			//}
		}

		$cmd .= " > {$filename}";

		return $cmd;
	}
}
