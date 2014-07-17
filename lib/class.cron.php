<?php
namespace Benchmarkly;

use Benchmarkly\Benchmarkly;

class Cron {
	public static function setup( Benchmarkly $plugin ) 
	{
		wp_schedule_event( time() + 3600, 'twicedaily', 'bm_do_regular_cron', array() ); 
	}

	public static function run() 
	{
		$benchmarkly = Benchmarkly::instance();
		$benchmarkly->loadBenchmarks();
	}
}
