<?php
namespace Benchmarkly;

use Benchmarkly\Data;

class Ajax {
	public $map = array( "priv" => 
		array( "check_benchmarks" => "checkBenchmarks" ),
	);
	public $data;
	
	public function __construct()
	{
		$this->data = new Data;
	}

	public static function privateHandlers() {
		$ajax = new Ajax();
		foreach( $ajax->map['priv'] as $hook => $method ) {
			add_action("wp_ajax_$hook", array( $ajax, $method ) );
		}			
	}

	public function checkBenchmarks()
	{
		$this->printJson( $this->data->benchmarks() );		
	}

	public function printJson($data) 
	{
		header("Content-type: application/json");
		print json_encode( $data );
		exit;
	}
	
	public function __destruct() 
	{
		exit;
	}
}
