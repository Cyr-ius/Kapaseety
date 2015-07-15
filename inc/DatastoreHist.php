<?php
class DatastoreHist {

	private $style;
	private $MySQL;

	function __construct() {
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function toHTML() {
		echo "<div class='row'><div class='col-lg-12'><h3 class='page-header'><i class='fa fa-database fa-fw'></i>Datastores History</h3></div></div>";
		echo "<div class='row'>";
			$this->style->Graph('graph-datastore_hist','col-lg-12',640);
		echo "</div>";
		echo "<div class='row'>";
			$this->style->Graph('graph-datastore_usage_hist','col-lg-12',640);
		echo "</div>";
	}
}
?>
