<?php
namespace Benchmarkly;

class Benchmarks {
	public $benchmarks;
	public $dir = "/benchmarks";
	static $instance = false;
	public $results = array();
	
	public function __construct() 
	{

	}

	/**
	 * Load all benchmarks from benchmark directory
	 * @requires php 5.2.0
	**/
	public function loadAll() 
	{
		$dir_to_read = Loader::getLibDir().$this->dir;
		$fh = opendir( $dir_to_read );
		while( false !== ( $file = readdir( $fh ) ) ) {
			if( in_array( $file , array(".","..") ) ) continue;
			if( "php" !== pathinfo("$dir_to_read/$file", PATHINFO_EXTENSION) ) continue;
			include_once "$dir_to_read/$file";
			$name = $this->normalizeName(pathinfo( "$dir_to_read/$file", PATHINFO_FILENAME ));
			$benchmark = "Benchmarkly\\$name";
			$this->addBenchmark( new $benchmark, $name );
		}
	}

	static function instance() 
	{
		if ( !self::$instance ) {
			self::$instance = new Benchmarks();
		}
		return self::$instance;
	}

	private function normalizeName( $name ) 
	{
		$name = ucfirst( str_replace("benchmark","Benchmark", $name) );
		return $name;
	}

	public function addBenchmark( Benchmark $benchmark, $name = 'default' ) 
	{
		$class=get_class($benchmark);
		$this->benchmarks[$name] = $benchmark;
	}

	public function testAll() 
	{	
		foreach( $this->benchmarks as $name => $obj ) {
			$this->results[$obj->key] = $obj->test()->response();
		}
		return $this->results;
	}

}
