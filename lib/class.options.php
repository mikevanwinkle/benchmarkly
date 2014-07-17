<?php
namespace Benchmarkly;

class Options {
	private $default = array(
		'installed'	=> 1,
		'do_cron'	=> 1,
	);	
	private $key = "benchmarkly_options";
	private $options = false; // loaded options
	static 	$instance = false;

	public function __construct() 
	{
		$this->loadOptions();
	}

	static function instance() 
	{
		if ( !self::$instance ) {
			self::$instance = new Options();
		}
		return self::$instance;
	}

	public function loadOptions() 
	{
		if ( !$this->options = get_option($this->key, false) ) {
			$this->options = $this->default;
			$this->saveOptions();
		}
		return $this;
	}

	public function saveOptions()
	{
		update_option( $this->key, $this->options );
	}

	public function getOptions() 
	{
		return $this->options;
	}
}
