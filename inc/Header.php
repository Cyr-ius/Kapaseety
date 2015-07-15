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


		echo "<link rel='stylesheet' href='css/kapaseety.css'>\n";
		echo "<noscript><style>.noscript_hidden { display: none; }</style></noscript>\n";
		echo "</head>\n";	
	}
}
?>
