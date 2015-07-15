<?php

class Settings
{
	static public $sgbd_server;
	static public $sgbd_user;
	static public $sgbd_password;
	static public $sgbd_database;
	static public $name = "KapaSeeTy";
	static public $logo_path="/img/kapaseety.png";
	static public $icon_path="";
	static public $timestamp;
	static public $debug=false;
	static public $rp_maxshare=10000;
	static public $rp_mincpushare=100;
	static public $rp_minmemshare=100;
	
	function __construct(){

	}
	
	static public function init($config_file=NULL) {
		/// Load config.php file 
		if (!isset($config_file)){
		    $config_file		=	realpath(dirname(__FILE__)."/../config.php");
		}

		if (!include($config_file)){
			throw new Exception("You need to create a configuration file.");
		}
		/// Check Timestamp for all pages
		if (isset($_REQUEST['madate'])) { 
			Settings::$timestamp = $_REQUEST['madate'];
			setcookie("timestamp",$_REQUEST['madate']);
#			var_dump($_REQUEST['madate']);
		} elseif (isset($_COOKIE['timestamp'])) {
#			var_dump('cookie');
			Settings::$timestamp = $_COOKIE['timestamp'];
		} else {
		}

		/// Set TimeZone
		date_default_timezone_set($config->timezone);			
		Settings::$sgbd_server = $config->sgbd_server ;
		Settings::$sgbd_user = $config->sgbd_user ;
		Settings::$sgbd_password = $config->sgbd_password;
		Settings::$sgbd_database = $config->sgbd_database;

		/// Load settings values
		if (count(Settings::get('name'))>0){Settings::$name=Settings::get('name')[0];}
		if (count(Settings::get('logo_path'))>0){Settings::$logo_path=Settings::get('logo_path')[0];}
		if (count(Settings::get('icon_path'))>0){Settings::$icon_path=Settings::get('icon_path')[0];}
		if (count(Settings::get('debug'))>0){Settings::$debug=Settings::get('debug')[0];}
		if (count(Settings::get('rp_maxshare'))>0){Settings::$rp_maxshare=Settings::get('rp_maxshare')[0];}
		if (count(Settings::get('rp_mincpushare'))>0){Settings::$rp_mincpushare=Settings::get('rp_mincpushare')[0];}
		if (count(Settings::get('rp_minmemshare'))>0){Settings::$rp_minmemshare=Settings::get('rp_minmemshare')[0];}
	}
	
	static public function get($name){
		$MySQL = new SGBD();
		$MySQL->connect();
		$Resultat = $MySQL->ResSQL("SELECT value FROM settings WHERE name='".$name."'");
		return $Resultat;
	}

	static public function remove($name){
		$MySQL = new SGBD();
		$MySQL->connect();
		$Resultat = $MySQL->ResSQL("DELETE FROM settings WHERE name=".$name);
		return $Resultat;
	}

	static public function set($array) {
		$MySQL = new SGBD();
		$MySQL->connect();
	 	foreach ($array as $name=>$value) {
			if (is_array($value)){$value=serialize($value);}
			if (count(self::get($name))==0) {
				$Resultat = $MySQL->ExecuteSQL("INSERT INTO settings (name,value) VALUES ('".$name."','".$value."')");
			} else {
				$Resultat = $MySQL->ExecuteSQL("UPDATE settings SET value='".$value."' where name='".$name."'");	
			}
		 }
		return $Resultat;
 	}
	
	function toHTML() {
		echo '<div class="row">
		<form id="admin_form" role="form">
		  <div class="form-group">
		    <label for="name">Site Name</label>
		    <input type="text" class="form-control" id="name" placeholder="Enter site name" name="name" value="'.Settings::$name.'"/>
		  </div>
		  <div class="form-group">
		    <label for="logo_path">Path of logo</label>
		    <input type="text" class="form-control" id="logo_path" placeholder="Enter logo path"  name="logo_path" value="'.Settings::$logo_path.'"/>
		  </div>
		  <div class="form-group">
		    <label for="icon_path">Path of Icon</label>
		    <input type="text" class="form-control" id="icon_path" placeholder="Enter Icon path"  name="icon_path" value="'.Settings::$icon_path.'"/>
		  </div>
		  <div class="form-group">
		    <label for="debug">Display Debug</label>
		    <input type="radio" class="" id="debug" name="debug" value="true" ';
			if (Settings::$debug) {echo 'checked';}
		   echo '/> Oui
		   <input type="radio" class="" id="debug" name="debug" value="false" ';
			if (!Settings::$debug) {echo 'checked';}
		   echo '/> Non
		  </div>	
		  <div class="form-group">
		    <label for="rp_maxshare">Ressource Pool : Max Shares</label>
		    <input type="text" class="form-control" id="rp_maxshare" placeholder="Max Shares"  name="rp_maxshare" value="'.Settings::$rp_maxshare.'"/>
		  </div>		  
		  <div class="form-group">
		    <label for="rp_mincpushare">Ressource Pool : Min CPU Shares</label>
		    <input type="text" class="form-control" id="rp_mincpushare" placeholder="Min CPU Share"  name="rp_mincpushare" value="'.Settings::$rp_mincpushare.'"/>
		  </div>	
		  <div class="form-group">
		    <label for="rp_minmemshare">Ressource Pool : Min Mem. Shares</label>
		    <input type="text" class="form-control" id="rp_minmemshare" placeholder="Min Mem. Share"  name="rp_minmemshare" value="'.Settings::$rp_minmemshare.'"/>
		  </div>			  
		  <button type="submit" class="btn btn-default">Submit</button>
		</form>
			</div>';
	}
}

?>
