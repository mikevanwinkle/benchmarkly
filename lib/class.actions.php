<?php
namespace Benchmarkly;

use Benchmarkly\Loader;

class Actions {

	public function __construct() {}
	
	public function loadActions() 
	{
		$dir_to_read = Loader::getLibDir().$this->dir;
		$fh = opendir( $dir_to_read );
		while( false !== ( $file = readdir( $fh ) ) ) {
			if( in_array( $file , array(".","..") ) ) continue;
			echo $file;
			continue;
			if( "php" !== pathinfo("$dir_to_read/$file", PATHINFO_EXTENSION) ) continue;
			include_once "$dir_to_read/$file";
			#$name = $this->normalizeName(pathinfo( "$dir_to_read/$file", PATHINFO_FILENAME ));
			$benchmark = "Benchmarkly\\$name";
			$this->addBenchmark( new $benchmark, $name );
		}
		
	}		
	
}
