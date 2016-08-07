<?php defined( 'ABSPATH' ) or die();

class Pushlive_RSync {
	
	protected $options = '-rlgDtvO --delete'; //add z option if sending remotely
	protected $destination;
	protected $source;
	public $excludes = array( 'wp-config.php' );
	public $excludeFile = '.file_list';
	protected $output = array();
	protected $cmds = array();

	// @todo
	protected $ssh = false;
	
	public $return = array();

	/**
	 * Config takes arguments like member variables: options, destination,
	 * source, ssh, etc...
	 */
	public function __construct( $config = array() ) {
		foreach( $config as $var => $val ) {
			if( property_exists( $this, $var ) ) {
				$this->{$var} = $val;
			}
		}
	}

	public function execute() {
		// n is dry run
		$this->writeExcludes();
		
		$cmd = $this->buildCommand();

		$this->cmds[] = $cmd;
		$output = array();
		$return = null;
		
		exec( $cmd, $output, $return );
		$this->output = $output;
		$this->return['transfer'] = $return;

		return $return;
	}
	
	public function getReturnStatus($code){
		
		switch ($code) {
			case 0: return 'Success';
			case 1: return 'Syntax or usage error';
			case 2:	return 'Protocol incompatibility';
			case 3: return 'Errors selecting input/output files, dirs';
			case 4: return 'Requested  action not supported: an attempt was made to manipulate 64-bit files on a platform that cannot support them; or an option was specified that is supported by the client and not by the server.';
			case 5: return 'Error starting client-server protocol';
			case 6: return 'Daemon unable to append to log-file';
			case 10: return 'Error in socket I/O';
			case 11: return 'Error in file I/O';
			case 12: return 'Error in rsync protocol data stream';
			case 13: return 'Errors with program diagnostics';
			case 14: return 'Error in IPC code';
			case 20: return 'Received SIGUSR1 or SIGINT';
			case 21: return 'Some error returned by waitpid()';
			case 22: return 'Error allocating core memory buffers';
			case 23: return 'Partial transfer due to error';
			case 24: return 'Partial transfer due to vanished source files';
			case 25: return 'The --max-delete limit stopped deletions';
			case 30: return 'Timeout in data send/receive';
			case 35: return 'Timeout waiting for daemon connection';
			default: return 'Unknown';
		}
		
	}

	public function getOptions() {
		return $this->options;
	}

	public function getSource() {
		return $this->source;
	}

	public function getCommands() {
		return $this->cmds;
	}

	public function getDestination() {
		return $this->destination;
	}

	public function getExcludes() {
		return $this->excludes;
	}
	
	public function getOutput() {
		return is_array( $this->output ) ? implode( "\n", $this->output ) : $this->output;
	}

	public function getRunInfo() {
		$cmds = $this->getCommands();
		$output = $this->getOutput();
		$files = $this->getExcludeFilePath();

		return array(
			'cmds' => $cmds,
			'output' => $output,
			'files' => $files,
			'return' => $this->return,
		);
	}

	public function getExcludeFilePath() {
		$path = ABSPATH . $this->excludeFile;
		return $path;
	}
	
	public function getExcludeFileOptions() {
		if( !empty( $this->excludeFile ) ){
			return ' --exclude-from '.$this->getExcludeFilePath();
		}
		return '';
	}
	
	public function setOptions( $options ) {
		if( !preg_match( '/[a-z-_ ]+/i', $options ) ) {
			return false;
		}
		else {
			$this->options = $options;
			return true;
		}
	}
	
	/**
	 * @todo filter
	 */
	public function setDestination( $destination ) {
		$this->destination = $destination;
	}

	public function setExcludes( $excludes ) {
		$this->excludes = array_merge( $this->excludes, $excludes );
	}

	public function setSource( $source ) {
		$this->source = $source;
	}

	public function addExclude( $exclude ) {
		$this->excludes[] = $exclude;
		$this->excludes = $this->excludes;
	}

	public function __get( $var ) {
		$fn = 'get'.ucwords( $var );

		if( method_exists( $this, $fn ) ) {
			return $this->$fn();
		}
		else {
			return $this->get( $var );
		}
	}

	protected function buildCommand() {

		// output to file ( for furure use? )
		$filename = PUSHLIVE__OUTPUT . 'rsync.'.date('Ymdgis').'.log';

		//$cmd = 'nohup '.$cmd.' > '.$filename;
		//$cmd = JPATH_COMPONENT.'/rsync.sh '.$this->source.' '.$this->destination.' '.escapeshellarg($this->options).' '.$this->getExcludeFilePath();
		$cmd = 'rsync '.$this->options.$this->getExcludeFileOptions(); 
		$cmd .= ' '.$this->source.' '.$this->destination;

		return $cmd;
	}

	protected function writeExcludes() {
		$this->excludes = array_unique( array_map( 'trim', $this->excludes ) );
		$success = file_put_contents( $this->getExcludeFilePath(), implode( "\n", $this->excludes ) );

		return !!$success;
	}
}
