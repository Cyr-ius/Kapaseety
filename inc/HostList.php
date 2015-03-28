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
		$SQL='SELECT 
			moref,
			hostname as "Name",
			version as Version ,
			count(vm_moref) as "VM",
			cpu_total as "Total CPU (Mhz)",
			mem_total as "Total Memory (Mo)" 
			FROM clustersandhostsandguests WHERE date="'.Settings::$timestamp.'" group by hostname order by hostname';
		$Resultats = $this->MySQL->TabResSQL($SQL);		
		$this->style->Tableau($Resultats,"hostlist-stats","Hosts");
		echo "</div>";	
	}
}
?>
