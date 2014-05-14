<?php
namespace Benchmarkly;
use Benchmarkly\Benchmark;
abstract class BenchmarkImplementation implements Benchmark {
	public $key;
	public $label;
	public $value;
	public $error = false;
	
	public function returnSuccess() 
	{
		return array( $this->key = array( 'error' => -1 , 'label' => @$this->label, 'value' => @$this->value ));
	}
	
	public function returnError()
	{
	
	}
	
	public function response() 
	{
		if( !$this->error )
			return $this->returnSuccess();
	}
}