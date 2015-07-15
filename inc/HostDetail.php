<?php
class HostDetail {

	private $moref;
	private $order;
	private $desc;
	private $style;
	private $MySQL;

	function __construct($moref) {
		$this->moref = $moref;
		$this->maintenance ="";
		$this->style = new Style();
		$this->MySQL = new SGBD();
	}

	function toHTML() {
		$this->MySQL->connect();
		echo "<span id='moref' style='display:none'>".$this->moref."</span>";
		$SQL='SELECT 
			hostname,
			version,
			manufacturer,
			cpu_socket_num,
			mem_total,
			cluster,
			maintenance,
			connectionstate,
			moref_cluster
			FROM data_hosts where date="'.Settings::$timestamp.'" and moref="'.$this->moref.'"';
		$Rslt = $this->MySQL->TabResSQL($SQL);
		if (sizeof($Rslt) >0) {
			$os = $Rslt[0]['manufacturer'];
			$logo = '<i class="fa fa-building fa-fw"></i> '.$os;
			if (preg_match('#ibm#i',$os)) {$logo = 'IBM -  '.$os;}
			if (preg_match('#dell#i',$os)) {$logo = 'DELL - '.$os;}
			if (preg_match('#cisco#i',$os)) {$logo = 'DELL - '.$os;}
			if (preg_match('#hp|hewlett|packard#i',$os)) {$logo = 'HP - '.$os;}
			if ($Rslt[0]['maintenance']=='True') {$this->maintenance = "<font color='red' size='3px'>(En maintenance)</font>";}
			echo "<div class='row'>
					<div class='col-lg-1'><h1><i class='fa fa-building fa-fw'></i></h1></div>
					<div class='col-lg-9'>
						<h3>".$Rslt[0]['hostname']." ".$this->maintenance."</h3>
						<h6 class='page-subtitle'>(".$Rslt[0]['cpu_socket_num']." vCPU / ".$Rslt[0]['mem_total']."Mo RAM) - VMWare ESXi v".$Rslt[0]['version']."</h6>
					</div>";
					if($Rslt[0]['moref_cluster']) {
						echo "<div class='col-lg-2 ref-cluster'><a href='#' data-href='cluster' data-moref='".$Rslt[0]['moref_cluster']."'><h6><i class='fa fa-sitemap fa-fw'></i> ".$Rslt[0]['cluster']."</h6></a></div>";	
					}
			echo "</div>";
			echo "<div class='row'>";
			$this->style->Graph('graph-cpu','col-lg-4',200);
			$this->style->Graph('graph-mem','col-lg-4',200);
			$this->style->Graph('graph-disk','col-lg-4',200);
			echo "</div>";	
			echo "<div class='row'>";
				echo "<div class='panel panel-yellow vmlist-stats' style='margin: 9px 18px;'>";
				echo "<div class='panel-body'>";
				$SQL='SELECT 
					vm_moref,
					vmname,
					vm_cpu_usage,
					vm_mem_usage 
					FROM data_vms WHERE vm_date="'.Settings::$timestamp.'" and moref_host="'.$this->moref.'" order by vmname';
				$Rslt = $this->MySQL->TabResSQL($SQL);
				foreach ($Rslt as $value) {
				echo "<button class='btn btn-warning btn-circle' style='margin-left:2px' data-toggle='tooltip' data-placement='top' data-href='vm' data-moref='".$value['vm_moref']."' title='Name :".$value['vmname']."\nCPU: ".$value['vm_cpu_usage']."%\nRAM: ".$value['vm_mem_usage']."Mo'><i class='fa fa-laptop'></i></button>";
				}
				echo "</div></div>";
			echo "</div>";	
			echo "<div class='row'>";
			$this->style->Graph('graph-consommation','col-lg-12');
			echo "</div>";	
			echo "<div class='row'>";
			$SQL='SELECT 
				vm_moref,
				vmname as "Name",
				vm_guest_os as "OS",
				vm_cpu_num "vCPU",
				vm_cpu_usage as "Usage CPU (Mhz)",
				vm_cpu_total as "Total CPU (Mhz)",
				vm_mem_usage as "Usage Memory (Mo)",
				vm_mem_total as "Total Memory (Mo)"
				FROM data_vms WHERE vm_date="'.Settings::$timestamp.'" and moref_host="'.$this->moref.'" order by vmname';
			$Resulats = $this->MySQL->TabResSQL($SQL);
			$this->style->Tableau($Resulats,"vmlist-stats");
			echo "</div>";		
			echo "<div class='row'>";
			$SQL='SELECT 
				date,
				date as "Date",
				cpu_socket_num as "Sockets",
				cpu_usage as "Usage CPU (Mhz)",
				cpu_total as "Total CPU (Mhz)",
				mem_usage as "Usage Memory (Mo)",
				mem_total as "Total Memory (Mo)",
				datastore_free as "Free space",
				datastore_total as "Total space"
				FROM data_hosts WHERE date > (CURRENT_DATE - INTERVAL 7 DAY) and moref="'.$this->moref.'" order by date';
			$Resulats = $this->MySQL->TabResSQL($SQL);
			$this->style->Tableau($Resulats,"datelist-stats");
		echo "</div>";	
		} else {
			echo "<div>Cet objet n'existait pas en date du ".Settings::$timestamp."</div>";
		}
	}
}
?>
