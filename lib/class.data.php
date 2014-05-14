<?php 
namespace Benchmarkly;

use Benchmarkly\Benchmarks;

class Data {
	private $wpdb;
	static $instance = false;
	
	public function __construct()
	{
		$this->wpdb = $GLOBALS['wpdb'];
	}
	
	public static function instance()
	{
		if ( !self::$instance )
			self::$instance = new self();
		return self::$instance;
	}

	public function benchmarks()
	{
		$benchmarks = Benchmarks::instance();
		$benchmarks->loadAll();
		return $benchmarks->testAll();
	}
	
	public function saveBenchmark( BenchmarkImplementation $benchmark )
	{
		$query = $this->wpdb->prepare("INSERT INTO {$this->wpdb->prefix}benchmarks ( `type`,`name`,`source`,`{$benchmark->datatype}`,`date`) VALUES( %s, %s, %s, %s, %s )",
					array( 
						$benchmark->type, 
						$benchmark->key, 
						$benchmark->source, 
						$benchmark->value, 
						time()
					) 
			);
		$response = $this->wpdb->query($query);
	}
}
