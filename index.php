<?php
/**
 * This file implements the index.
 * 
 * @author    Cédric Levasseur <cedric.levasseur@ipocus.net>
 * @copyright 2014Cédric Levasseur
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/cyr-ius/Kapaseety
 */
ini_set('max_execution_time', 60);

/// Start session
session_start();
// store the current time
$now = time();
$cache_expire = session_cache_expire();
// get the time the session should have expired
$limit = $now - $cache_expire*60;

// check the time of the last activity
if (isset ($_SESSION['last_activity']) && $_SESSION['last_activity'] < $limit) {
	// if too old, clear the session array and redirect
	$_SESSION = array();
	echo "<script>window.location='/';</script>";
	exit;
} else {
	// otherwise, set the value to the current time
	$_SESSION['last_activity'] = $now;
}

/// Because we don't care about notices
if(function_exists("error_reporting")){
	 error_reporting( E_ERROR | E_WARNING);
}
function myErrorHandler($errno, $errstr, $errfile, $errline) {
	echo "<div style='position: relative; bottom: 0px;width: 100%' class='label-danger'>
			<span class='label'><b>My ERROR</b> [$errno] $errstr - Fatal error on line $errline in file $errfile</span>\n
		</div>";
}
set_error_handler("myErrorHandler");

/// Autoload classes
function my_autoload($class){
	if(file_exists(dirname(__FILE__)."/inc/$class.php")){
		require(dirname(__FILE__)."/inc/$class.php");
	}else{
		return FALSE;
	}
	require_once dirname(__FILE__).'/3rdparty/json-rpc/jsonRPC2Server.php';	
}
spl_autoload_register("my_autoload");

/// Take care of nasty exceptions
function exception_handler($exception) {
  echo "<div class='exception'>" , $exception->getMessage(), "</div>\n";
      /* Ne pas exï¿½cuter le gestionnaire interne de PHP */
    return true;
}
set_exception_handler('exception_handler');

/// Display trace with debug = true in Settings.php
function trace($var) {
	if (Settings::$debug==true) {
	echo "<div class='label-info'>
			<span class='label' style='white-space:pre;'>$var</span>\n
		</div>";
	}
}

function protect_user_send_var($var){
	if(is_array($var))
		return array_map('protect_user_send_var', $var);
	else 
		return addslashes($var);
}

if (!get_magic_quotes_gpc()){
	$_POST = protect_user_send_var($_POST);
	$_COOKIE = protect_user_send_var($_COOKIE);
	$_GET = protect_user_send_var($_GET);
}

if (isset($_SERVER['CONTENT_TYPE'])) {
	if(preg_match("/application\/json/",$_SERVER['CONTENT_TYPE'])){ 
		new API();

	}else {
		new Index();
	}
}else{
	new Index();
}
?>
