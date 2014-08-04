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
						gmdate("Y-m-d H:i:s")
					) 
			);
		$response = $this->wpdb->query($query);
	}

	public function get( $name = null, $params = array() ) 
	{
		if ( @$params['before'] ) {
			$before =  $params['before'];
		}
		if ( @$params['after'] ) {
			$after = $params['after'];
		}
		$query = vsprintf( "SELECT * FROM %s WHERE 1=1 ", array( $this->wpdb->prefix."benchmarks" ) );
		if ( $name ) {
			$query .= $this->wpdb->prepare( "AND `name` = %s ", array( $name )  );
		}
		
		if ( @$before ) {
			$query .= $this->wpdb->prepare( "AND date < '%s' ", array( $before ) );
		}

		if ( @$after ) {
			$query .= $this->wpdb->prepare( "AND date > '%s' ", array( $after )  );
		}

		$query .= " ORDER BY date ASC";
		return $this->cached_results($query);
	}

	public function cached_results($query) 
	{
		if ( !$results = wp_cache_get( md5($query), "benchmarkly" ) ) {
			$results = $this->wpdb->get_results($query);
			wp_cache_set( md5( $query ), "benchmarkly" );
		}
		return $results; 
	}
	
	static public function formatter( $data, $format = "none" ) 
	{
		$out = array();
		switch ( $format ) {
			case "series":
				foreach( $data as $bench ) {
					$out[$bench->name][$bench->date][] = $bench->datanum;
				}
				break;
			default:
			break;
		}
		return $out;
	}	
}
