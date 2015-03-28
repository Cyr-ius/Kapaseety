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
		//~ echo "<div class='row'>";
		//~ self::Graph('graph-consommation','col-lg-12');
		//~ echo "</div>";
		$SQL='SELECT 
			cluster_moref,
			clustername as "Nom",
			cluster_vms_total as "Vms",
			round(cluster_mem_total/1024) as "Total memory (GB)" ,
			round(cluster_mem_realcapacity/1024) as "Real capacity (GB)" ,
			round(cluster_mem_usage/1024) as "Memory Usage (GB)",	
			round(cluster_cpu_total/1000) as "Total CPU (Ghz)" ,
			round(cluster_cpu_realcapacity/1000) as "Real capacity (Ghz)" ,			
			round(cluster_cpu_usage/1000) as "CPU Usage (Ghz)"		
			from data_clusters
			where cluster_date ="'.Settings::$timestamp.'"
			group by cluster_moref
			order by clustername';
		$Resulats = $this->MySQL->TabResSQL($SQL);
		$this->style->Tableau($Resulats,"dashboard","Les clusters");
		echo "</div>";

	}
}
?>
