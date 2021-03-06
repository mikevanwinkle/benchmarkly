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
		return $this;
	}
	
	/**
	 * Singleton function to return instance
	 * @requires php 5.2.0
	**/
	static function instance() 
	{
		if ( !self::$instance ) {
			self::$instance = new Benchmarks();
		}
		return self::$instance;
	}
	
	/**
	 * Convert file neam to class name
	 * @requires php 5.2.0
	**/
	private function normalizeName( $name ) 
	{
		$name = ucfirst( str_replace("benchmark","Benchmark", $name) );
		return $name;
	}

	/**
	 * Add benchmark to benchmarks object
	 * @requires php 5.2.0
	**/
	public function addBenchmark( Benchmark $benchmark, $name = 'default' ) 
	{
		$class=get_class($benchmark);
		$this->benchmarks[$name] = $benchmark;
	}
	
	/**
	 * Run all benchmarks
	 * @requires php 5.2.0
	**/
	public function runAll() 
	{	
		foreach( $this->benchmarks as $name => $obj ) {
			$this->results[$obj->key] = $obj->runandreport()->response();
		}
		return $this->results;
	}
	
	/**
	 * Return json data for all benchmarks
	 * @requires php 5.2.0
	 * @params $benchmark required string 
	**/
	public static function buildChartJSON( $benchmark ) 
	{
		$bm = self::instance();
		$bm->loadAll();
		$json = array();
		foreach( $bm->benchmarks as $chart ) {
			$json[$chart->key] = $chart->getChartData();
		}
		return $json;
	}

}
