<?php
namespace Benchmarkly;

use Benchmarkly\Options;
use Benchmarkly\Ajax;

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
		wp_enqueue_script( "benchmarkly-js", plugins_url("assets/benchmarkly.js",__FILE__) , array('jquery'));
	}

	public function settingsPage()
	{
		if ( isset($_REQUEST['check_benchmarks']) ) { 
			$this->doCheck();
		}
		$load = Loader::instance();
		$load->loadView( "settings-main", $this->options );
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

	public function checkActive()
	{
		global $wpdb;
		$tbl = "CREATE TABLE IF NOT EXISTS `%sbenchmarks` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `type` varchar(10) DEFAULT 'NOT NULL',
			  `name` varchar(10) DEFAULT 'NOT NULL',
			  `source` varchar(10) DEFAULT 'NOT NULL',
			  `dataint` int(11) DEFAULT NULL,
			  `datachar` varchar(10) DEFAULT NULL,
			  `datalong` longtext,
			  `date` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDb DEFAULT CHARSET=utf8;";
		$tbl = sprintf( "$tbl", $wpdb->prefix );
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $tbl );
	}

	public function checkInactive()
	{
		global $wpdb;
		$sql = sprintf( "DROP TABLE %sbenchmarks" , $wpdb->prefix );
		$wpdb->query( $sql );
	}
} 
