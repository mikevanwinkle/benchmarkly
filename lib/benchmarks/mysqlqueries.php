<?php
namespace Benchmarkly;

use PDO;
use Benchmarkly\BenchmarkImplementation;
use Benchmarkly\Data;
use Benchmarkly\Patterns;

class Mysqlqueries extends BenchmarkImplementation {
	public $source;
	public $key 		= "mysqlqueries";
	public $type 		= "site";
	public $label 		= "MySQL Queries";
	public $description	= "Counts the total queries required to load the site.";
	public $report 		= "db";
	public $datatype 	= "datanum";
	public $fillcolor 	= "#336699";
	public $strokecolor	= "#336699";
	public $pointcolor	= "#666";
	public $pointstrokecolor	= "#fff";
	public $labels		= "monthly";
	
	public function __construct() 
	{
		define("SAVEQUERIES", true);
		$this->source = basename( __FILE__ );
	}
	
	public function run() 
	{
		$resp = wp_remote_get( rtrim( get_option('siteurl'), "/" )."?doing_benchmarks=1" );
		if ( preg_match( Patterns::pattern("mysqlqueries"), $resp['body'], $matches) ) {
			$this->value = $matches[1];
		}
		return $this;
	}
	
	public function shutdown()
	{
		global $wpdb;
		echo "<!--BMKLY[[mysqlqueries:".round(count($wpdb->queries))."]]-->";
	}

}