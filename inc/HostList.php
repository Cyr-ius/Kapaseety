<?php
class HostList {

	private $style;
	private $MySQL;

	function __construct() {
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}
	
	function toHTML() {	
		$this->MySQL->connect();
		echo "<div class='row'><div class='col-lg-12'><h3 class='page-header'><i class='fa fa-laptop fa-fw'></i>Hosts</h3></div></div>";
		echo "<div class='row'>";
		$th = array("Nom","Hôte");
		$this->style->TableauVide($th,"dashboard","Les hyperviseurs","searchhost dashfirstlink");
		echo "</div>";
	}
}
?>
