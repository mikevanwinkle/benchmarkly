<?php
namespace Benchmarkly;

use Benchmarkly\Patterns;

class Patterns {
	public $patterns;
	public static $instance;

	public function __construct() 
	{
		$this->loadPatterns();
	}

	public static function instance()
	{
		if( !self::$instance ) {
			self::$instance = new Patterns();
		}
		return self::$instance;
	}

	public static function pattern( $key ) 
	{
		$instance = Patterns::instance();
		return $instance->patterns[$key];
	}

	public function loadPatterns()
	{
		if( !file_exists(__DIR__.'/patterns.ini') ) return false;
		$this->patterns = parse_ini_file(__DIR__.'/patterns.ini');
		if ( !is_array( $this->patterns ) ) {
			$this->patterns = array();
		}
		return $this;
	}

}
