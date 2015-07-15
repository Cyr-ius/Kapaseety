<?php
class Dashboard {

	private $style;
	private $MySQL;

	function __construct() {
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function toHTML() {
		
		echo "<div class='row'><div class='col-lg-12'><h3 class='page-header'><i class='fa fa-dashboard fa-fw'></i>Dashboard</h3></div></div>";
		echo "<div class='row'>";
		$this->style->Label('datacenter_vms_total','success','Machines virtuelles','col-lg-6');
		$this->style->Label('datacenter_hosts_total','success','Hyperviseurs','col-lg-6');
		echo "</div>";
		echo "<div class='row'>";
		$th = array("Nom","Hosts","Vms","Total memory (GB)","Real capacity (GB)","Memory Usage (GB)","Total CPU (Ghz)","Real capacity (Ghz)","CPU Usage (Ghz)");
		$this->style->TableauVide($th,"dashboard","Les clusters","dashboardtable dashfirstlink");
		echo "</div>";
		echo "<div class='row'>";
		echo "	<div id='datacenter-heatmap' class='col-lg-12'></div>";
		echo "</div>";
	}
}
?>
