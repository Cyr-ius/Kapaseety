<?php
class VmList {

	private $style;
	private $MySQL;

	function __construct() {
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}
	
	function toHTML() {
		echo "<div class='row'><div class='col-lg-12'><h3 class='page-header'><i class='fa fa-laptop fa-fw'></i>Virtuals Machines</h3></div></div>";	
		echo "<div class='row'>";
		$th = array("Nom","VM");
		$this->style->TableauVide($th,"dashboard","Les machines virtuelles","searchvm dashfirstlink");
		echo "</div>";
	}
}
?>
