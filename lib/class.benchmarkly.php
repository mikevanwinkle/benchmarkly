<?php
namespace Benchmarkly;

use Benchmarkly\Options;
use Benchmarkly\Ajax;
use Benchmarkly\Cron;
use Benchmarkly\Data;

class Benchmarkly {
	public $default = array();
	public $data = array();
	public $options = false;
	static $instance = false;
	
	public function __construct() 
	{
		if( !$this->options ) {	
			$options = Options::instance();	
			$this->options = $options->getOptions();
		}
		$this->actions();
		self::$instance = $this;	
	}

	static function instance() 
	{
		if ( !self::$instance ) {
			self::$instance = new Benchmarkly();
		}
		return self::$instance;
	}
	
	/**
 	 * WordPress API - actions
	 * 
	**/
	public function actions() 
	{
		// load admin actions here
		if( is_admin() ) {
			add_action( "init", array( $this, "init" ) );
			add_action( "admin_menu", array( $this, "admin_menu" ) );
			add_action( "admin_enqueue_scripts", array( $this, "admin_enqueue_scripts" ) );
			// load admin ajax handlers
			Ajax::privateHandlers();
			
		}
		
		// load all others here
		add_action('bm_do_regular_cron',array($this, 'doCheck'));
		$this->shutdown();
	}

	public function init() 
	{
		// get_options 
	}

	/**
	 * The primary admin page
	 *  -
	**/
	public function admin_menu() 
	{
		add_menu_page('Benchmarkly', 'Benchmarkly', 'administrator','benchmarkly', array( $this, 'settingsPage' ) );
	}

	public function admin_enqueue_scripts()
	{
	}

	public function settingsPage()
	{
		if ( isset($_REQUEST['check_benchmarks']) ) { 
			$this->doCheck();
		}
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'flot-js', plugins_url("assets/flot.js",__FILE__) , array('jquery'));
		wp_enqueue_script( "flot-js-time", plugins_url("assets/flot-time.js",__FILE__) , array('jquery','flot-js'));
		wp_enqueue_script( "benchmarkly-js", plugins_url("assets/benchmarkly.js",__FILE__) , array('jquery','flot-js'));
		wp_enqueue_style ( 'benchmarkly-css', plugins_url("assets/benchmarkly.css",__FILE__) );
		$load = Loader::instance();
		$data = Data::instance();
		wp_localize_script( "benchmarkly-js", 'bmkly', array( "benchmarks" => json_encode( Benchmarks::buildChartJSON("queries_per_sec") ) , 'chart_data' => 1 ) );
		$load->loadView( "settings-main", array_merge( $this->options, array( 'benchmarks'=>Benchmarks::instance() ) ) );
	}

	public function doCheck() 
	{
		$this->loadBenchmarks();
	}

	public function loadBenchmarks()
	{ 
		$benchmarks = Benchmarks::instance();
		$benchmarks->loadAll();
		$benchmarks->testAll();
	}

	public function doCron() 
	{
		$this->loadBenchmarks();
	}

	public function checkActive()
	{
		global $wpdb;
		$tbl = "CREATE TABLE IF NOT EXISTS `%sbenchmarks` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `type` varchar(10) DEFAULT 'NOT NULL',
			  `name` varchar(10) DEFAULT 'NOT NULL',
			  `source` varchar(10) DEFAULT 'NOT NULL',
			  `datanum` DECIMAL(10,2) NULL,
			  `datachar` varchar(10) DEFAULT NULL,
			  `datalong` longtext,
			  `date` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDb DEFAULT CHARSET=utf8;";
		$tbl = sprintf( "$tbl", $wpdb->prefix );
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $tbl );
		Cron::setup( $this );
	}

	public function checkInactive()
	{
		global $wpdb;
		$sql = sprintf( "DROP TABLE %sbenchmarks" , $wpdb->prefix );
		$wpdb->query( $sql );
	}

	public function shutdown() 
	{
		if ( isset($_REQUEST['doing_benchmarks']) ) {	
			$benchmarks = Benchmarks::instance();
			$benchmarks->loadAll();
			foreach( $benchmarks->benchmarks as $name => $obj ) {
				if( method_exists($obj,"shutdown")) {
					add_action("shutdown", array( $obj, "shutdown" ) );
				}
			}
		}
	}
}
