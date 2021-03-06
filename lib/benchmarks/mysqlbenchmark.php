<?php
namespace Benchmarkly;

use PDO;
use DateTime;
use DateInterval;
use Benchmarkly\BenchmarkImplementation;
use Benchmarkly\Data;

class MysqlBenchmark extends BenchmarkImplementation {
	public $db;
	public $source;
	public $key 		= "queries_per_sec";
	public $type 		= "server";
	public $label 		= "Queries per sec";
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

	public function runandreport()
	{
		$this->connect();
		$this->run()->report();
		return $this;
	}

	public function report()
	{
		if ( $this->report == "db" ) {
			$data = Data::instance();
			$data->saveBenchmark( $this );
		}
		
		return $this;
	}

	public function connect() 
	{
		$pdo = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASSWORD);
		if( !$pdo ) {
			throw new Exception("Cannot connect to db");
		}
		$this->db = $pdo;
	}

	public function run() 
	{
		$stats = $this->db->getAttribute(PDO::ATTR_SERVER_INFO);
		if ( preg_match( "#Queries per second avg:\s?(\d+\.\d+)#", $stats, $matches ) ) {
			$this->value = $matches[1];
		}
		return $this;
	}
	
}
