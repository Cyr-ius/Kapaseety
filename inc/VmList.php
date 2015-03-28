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
		$SQL='SELECT 
			vm_moref,
			vmname as "Name",
			vm_cpu_num "vCPU",
			vm_cpu_usage as "Usage CPU (Mhz)",
			vm_cpu_total as "Total CPU (Mhz)",
			vm_mem_usage as "Usage Memory (Mo)",
			vm_mem_total as "Total Memory (Mo)",
			vm_guest_os as "OS",
			hostname as "Host",
			clustername as "Cluster" 
			FROM clustersandhostsandguests WHERE vm_date="'.Settings::$timestamp.'" order by vmname';			
		$Resultats =  $this->MySQL->TabResSQL($SQL);
		$this->style->Tableau($Resultats,"vmlist-stats");
		echo "</div>";	
	}
}
?>
