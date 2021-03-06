<?php
namespace Benchmarkly;

use PDO;
use Benchmarkly\BenchmarkImplementation;
use Benchmarkly\Data;
use Benchmarkly\Patterns;

class Apachememory extends BenchmarkImplementation {
	public $db;
	public $source;
	public $key 		= "apache_memory";
	public $type 		= "site";
	public $label 		= "Apache Memory";
	public $report 		= "db";
	public $datatype 	= "datanum";
	public $fillcolor 	= "rgba(220,220,220,0.5)";
	public $strokecolor	= "rgba(220,220,220,1)";
	public $pointcolor	= "rgba(220,220,220,1)";
	public $pointstrokecolor	= "#fff";
	public $labels		= "monthly";

	public function __construct()
	{
		$this->source = basename( __FILE__ );
	}

	public function run() 
	{
		$resp = wp_remote_get( rtrim( get_option('siteurl'), "/" )."?doing_benchmarks=1" );
		if ( preg_match( Patterns::pattern("apachemem"), $resp['body'], $matches) ) {
			$this->value = $matches[1];
		}
		return $this;
	}
	
	public function shutdown()
	{
		echo "<!--BMKLY[[apachememory:".memory_get_usage(TRUE)."]]-->";
	}
	
}
