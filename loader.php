<?php
namespace Benchmarkly;
/**
 * Class handles the loading of files and options for the plugin
 */
use Benchmarkly;

class Loader {
	private $options;
	private $default = array(
		'how_often' => 'daily'
	);
	public $libdir = '/lib';
	static $instance;

	public function __construct() 
	{
		spl_autoload_register( array($this,"__autoload") ); 
	}

	public function loadController( $class , $return = true ) 
	{
		if( file_exists( __DIR__."{$this->libdir}/class.$class.php" ) ) {
			require_once __DIR__."{$this->libdir}/class.$class.php";
		}
		
		if ( $return ) {
			$class = ucfirst($class);
			$class = "Benchmarkly\\$class";
			if ( class_exists( $class ) ) {
				return new $class();
			} else {
				return false;
			}
		} else {
			return;
		}
	}

	public function loadView( $view, $data = array() ) {
		extract( $data );
		$viewfile = __DIR__."{$this->libdir}/views/view.$view.php";
		if( file_exists( "$viewfile" ) ) {
			include "$viewfile";
		} else {
			print "Could not find view file.";
		}
	}

	static function instance() 
	{
		if ( !self::$instance ) {
			self::$instance = new Loader();
		}
		return self::$instance;
	}

	static function getPluginDir() 
	{
		return __DIR__;
	}

	static function getLibDir()
	{
		$loader = self::instance();
		return self::getPluginDir().$loader->libdir;
	}

	private function __autoload( $class ) {
		if( strstr( $class, "Benchmarkly" ) ) {
			$class = ltrim( str_replace('benchmarkly','', strtolower($class) ), "\\");
			$path = __DIR__.$this->libdir."/class.".$class.".php";
			if ( file_exists( $path ) ) {
				include_once $path;
			}
		}
	}
}
