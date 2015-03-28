<?php
abstract class Header implements HTMLObject
{
	public function header($head_content=NULL){
		echo "<!DOCTYPE html>\n";
		echo "<html lang='fr'>\n";
		echo "<head>\n";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
                echo "<meta name='viewport' content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;'/>\n";
		echo "<meta http-equiv='X-UA-Compatible' content='IE=9' />";
		echo "<title>".Settings::$name."</title>\n";
		echo "<link rel='icon' type='image/ico' href='".Settings::$icon_path."'>\n";
		/// CSS
		echo "<link rel='stylesheet' type='text/css' href='3rdparty/bootstrap/css/bootstrap.min.css'>\n";		
		echo "<link rel='stylesheet' type='text/css' href='3rdparty/metismenu/css/jquery.metisMenu.css'>\n";
		echo "<link rel='stylesheet' type='text/css' href='3rdparty/font-awesome-4.1.0/css/font-awesome.min.css'>\n";
		echo "<link rel='stylesheet' type='text/css' href='3rdparty/dataTables/css/dataTables.bootstrap.css'>\n";
		echo "<link rel='stylesheet' type='text/css' href='3rdparty/bootstrap-slider/css/bootstrap-slider.min.css'>\n";

	      echo "<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->\n
		     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->\n
		        <!--[if lt IE 9]>\n
		        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>\n
		        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>\n
			 <![endif]-->\n";
		echo "<link rel='stylesheet' href='css/kapaseety.css'>\n";
		echo "<noscript><style>.noscript_hidden { display: none; }</style></noscript>\n";
		echo "</head>\n";	
	}
}
?>
