<?php
/**
 * An abstract class for implementing common aspects of Benckmark behavior
 *
**/
namespace Benchmarkly;
use DateTime;
use DateInterval;
use Benchmarkly\Benchmark;

abstract class BenchmarkImplementation implements Benchmark {
	public $key;
	public $label;
	public $value;
	public $error = false;
	public $report = "db";
	
	public function runandreport()
	{
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
	
	public function returnSuccess() 
	{
		return array( $this->key = array( 'error' => -1 , 'label' => @$this->label, 'value' => @$this->value ));
	}
	
	public function returnError()
	{
	
		return array( $this->key = array( 'error' => 1 , 'label' => @$this->label, 'value' => @$this->value ));
	}
	
	public function response() 
	{
		if( !$this->error )
			return $this->returnSuccess();
		return $this->returnError();
	}
	
	public function getChartData()
	{
		$db = new Data();
		$params = array();
		if ( !isset($_GET['bmkly']['after']) ) {
			$today = new DateTime();
			$after = $today->add( DateInterval::createFromDateString( "-30 days"  ) );
			$params['after'] = $after->format("Y-m-d 00:00:00");
		} 
		$results = $db->get( $this->key , $params);
		$labels = array();
		$vals = array();
		foreach( $results as $result ) {
			$time = $result->date;
			$vals[$time] = $result->{$this->datatype};
		}
		$data = array("data"=>$vals);
		return $data;
	}
}
