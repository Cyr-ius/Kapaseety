<?php
class VmDetail {

	private $moref;
	private $style;
	private $MySQL;

	function __construct($moref) {
		$this->moref = $moref;
		$this->style = new Style();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();		
	}

	function toHTML() {
		echo "<span id='moref' style='display:none'>".$this->moref."</span>";
		$SQL='SELECT 
			vmname,
			vm_guest_os,
			vm_powerstate,
			vm_cpu_num,
			vm_mem_total,
			moref,
			hostname
			FROM guestsandhosts WHERE vm_date="'.Settings::$timestamp.'" and vm_moref="'.$this->moref.'"';
		$Rslt = $this->MySQL->TabResSQL($SQL);	
		$os = $Rslt[0]['vm_guest_os'];
		$logo = '<i class="fa fa-desktop fa-fw"></i> '.$os;
		if (preg_match('#windows#i',$os)) {$logo = '<i class="fa fa-windows fa-fw"></i> '.$os;}
		if (preg_match('#linux|ubuntu|debian|centos|bsd|redhat#i',$os)) {$logo = '<i class="fa fa-linux fa-fw"></i> '.$os;}
		if (preg_match('#apple|mac|osx|macos#i',$os)) {$logo = '<i class="fa fa-apple fa-fw"></i> '.$os;}
		if ($Rslt[0]['vm_powerstate']=="poweredOn") {$power='(sous-tension)';} else {$power='(arrêté ou indisponible)';}
		echo "<div class='row'>
				<div class='col-lg-1'><h1><i class='fa fa-laptop fa-fw'></i></h1></div>
				<div class='col-lg-9'><h3> ".$Rslt[0]['vmname']."<font size='3px'>$power</font></h3><h6 class='page-subtitle'>".$logo." - ".$Rslt[0]['vm_cpu_num']." vCPU / ".$Rslt[0]['vm_mem_total']."Mo RAM</h6></div>
				<div class='col-lg-2 ref-host'><a href='#' data-href='host' data-moref='".$Rslt[0]['moref']."'><h6><i class='fa fa-building fa-fw'></i>".$Rslt[0]['hostname']."</h6></a></div>					
			</div>";
		echo "<div class='row'>";
		$this->style->Graph('graph-cpu','col-lg-4',200);
		$this->style->Graph('graph-mem','col-lg-4',200);
		$this->style->Graph('graph-disk','col-lg-4',200);
		echo "</div>";	
		echo "<div class='row'>";
		$this->style->Graph('graph-consommation','col-lg-12');
		echo "</div>";
		echo "<div class='row'>";
		$SQL='SELECT 
			date,
			vm_date as "Date",
			hostname as "Host",
			vm_cpu_num "vCPU",
			vm_cpu_usage as "Usage CPU (Mhz)",
			vm_cpu_total as "Total CPU (Mhz)",
			vm_mem_usage as "Usage Memory (Mo)",
			vm_mem_total as "Total Memory (Mo)"
			FROM guestsandhosts WHERE date > (CURRENT_DATE - INTERVAL 7 DAY) and vm_moref="'.$this->moref.'" order by vm_date desc';
//~ 		$Resulats = $this->MySQL->TabResSQL($SQL);
//~ 		$this->style->Tableau($Resulats,"datelist-stats");
		echo "</div>";
	}
}
?>
