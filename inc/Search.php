<?php
class Search {
	private $search;
	private $style;
	private $MySQL;

	function __construct($search) {
		$this->search = $search;
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();		
	}
	
	function toHTML() {
		echo "<div class='row'>";
		$th = array("Nom");
		$this->style->TableauVide($th,"dashboard","Les clusters","searchcluster dashfirstlink");
		echo "</div>";
		echo "<div class='row'>";
		$th = array("Nom","Hôte");
		$this->style->TableauVide($th,"dashboard","Les hyperviseurs","searchhost dashfirstlink");
		echo "</div>";
		echo "<div class='row'>";
		$th = array("Nom","VM");
		$this->style->TableauVide($th,"dashboard","Les machines virtuelles","searchvm dashfirstlink");
		echo "</div>";
	}
}
?>
