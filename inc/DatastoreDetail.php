<?php
class DatastoreDetail {

	private $style;
	private $MySQL;

	function __construct() {
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function toHTML() {

		echo "<div class='row'><div class='col-lg-12'><h3 class='page-header'><i class='fa fa-database fa-fw'></i>Datastores</h3></div></div>";
		echo "<div class='row'>";
			$this->style->Graph('graph-datastore_usage','col-lg-12',220);
		echo "</div>";
		echo "<div class='row'>";
		//~ echo "<div class='row'>";
		//~ self::Graph('graph-consommation','col-lg-12');
		//~ echo "</div>";
		$SQL='SELECT 
			cluster_moref,
			cluster_datastore_total as "Total disk" ,
			cluster_datastore_used as "Disks used" 		
			from data_clusters
			where cluster_date ="'.Settings::$timestamp.'"
			group by cluster_moref
			order by clustername';
		$Resulats = $this->MySQL->TabResSQL($SQL);
		$this->style->Tableau($Resulats,"datastore","Les disques");
		echo "</div>";

	}
}
?>
