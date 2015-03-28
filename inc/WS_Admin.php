<?php
class WS_Admin
{
	function __construct(){
	}

	public function set($variables) {
		return Settings::set($variables);
	}

	public function get($variables) {
		return unserialize(Settings::get($variables)[0]);
	}
	 

}?>
