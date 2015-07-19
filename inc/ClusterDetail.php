<?php
class ClusterDetail {

	private $moref;
	private $style;
	private $MySQL;

	function __construct($moref) {
		$this->moref = $moref;
		$this->style = new Style();
		$this->MySQL = new SGBD();
	}
	
	public function VMList($moref){
		//VM  Buttons List
		echo "<div class='panel-yellow-titletab'>Virtual machines <b>".$vm_num."</b></div>";
		echo "<div class='panel panel-yellow-bodytab vmlist-stats'>";
		echo "<div class='panel-body'>";
		$SQL='SELECT 
		vm_moref,
		vmname,
		vm_cpu_usage,
		vm_mem_usage, 
		round((vm_mem_usage/vm_mem_total)*100) as prct_mem,
		round((vm_cpu_usage/vm_cpu_total)*100) as prct_cpu,
		vm_powerstate
		FROM clustersandhostsandguests WHERE vm_date="'.Settings::$timestamp.'" and  cluster_moref="'.$moref.'" order by vmname';
		$RsltVM = $this->MySQL->TabResSQL($SQL);
		foreach ($RsltVM as $value) {
			if ($value['vm_powerstate'] != "poweredOn"){$btncolor="btn-undefined";} else {$btncolor="btn-info";}
			if ($value['prct_cpu'] > 80 or $value['prct_mem']>80){$btncolor="btn-warning";}
			if ($value['prct_cpu'] > 95 or $value['prct_mem']>95){$btncolor="btn-danger";}
			echo "<button class='btn $btncolor btn-circle' style='margin-left:2px' data-toggle='tooltip' data-placement='top' data-href='vm' data-moref='".$value['vm_moref']."' title='Name :".$value['vmname']."\nCPU: ".$value['prct_cpu']."%\nRAM: ".$value['prct_mem']."%'><i class='fa fa-laptop'></i></button>";
		}
		echo "</div></div>";	
	}

	function toHTML() {
		$this->MySQL->connect();
		echo "<span id='moref' style='display:none'>".$this->moref."</span>";
		$SQL='SELECT 
			clustername
			FROM data_clusters WHERE cluster_date="'.Settings::$timestamp.'" and  cluster_moref="'.$this->moref.'"';
		$Rslt = $this->MySQL->TabResSQL($SQL);
		if (sizeof($Rslt) >0) {

		echo "<div class='row'>
				<div class='col-lg-1'><h1><i class='fa fa-sitemap fa-fw'></i></h1></div>
				<div class='col-lg-11'><h3> ".$Rslt[0]['clustername']."</h3><h6 class='page-subtitle'></h6></div>
			</div>";
		echo "<div class='row'>";
		$this->style->Graph('graph-vm-left','col-lg-4',220);//VM Left
				echo "<div class='col-lg-4'>";
					echo "<div class='row'>";
					$this->style->Graph('graph-ratio-cpu','col-lg-12',105);//Ratio CPU
					$this->style->Graph('graph-ratio-vm','col-lg-12',105); //Ratio VM
					echo "</div>";							
				echo "</div>";			
		$this->style->Graph('graph-ha','col-lg-4',220);	//HA	
		echo "</div>";				
		echo "<div class='row'>";//Host  Buttons List
			$SQL='SELECT moref,hostname,cpu_usage,mem_usage,maintenance 
				FROM clustersandhosts where cluster_date="'.Settings::$timestamp.'" and moref_cluster="'.$this->moref.'" order by hostname';
			$Rslt = $this->MySQL->TabResSQL($SQL);
			echo "<div class='panel-primary-titletab'>Hosts <b>".count($Rslt)."</b></div>";		
			echo "<div class='panel panel-primary-bodytab hostlist-stats'>";
			echo "<div class='panel-body'>";
			foreach ($Rslt as $value) {
			$fqdn = explode(".",$value['hostname']);
			$shortname = $fqdn[0];
			if ($value['maintenance']=='True') {$color = 'undefined';} else {$color='primary';}
			echo "<button class='btn btn-".$color."' style='margin-left:2px' data-toggle='tooltip' data-placement='top' data-href='host' data-moref='".$value['moref']."' title='Name :".$value['hostname']."\nCPU: ".$value['cpu_usage']."Mhz\nRAM: ".$value['mem_usage']."Mo'><i class='fa fa-building'></i> ".$shortname."</button>";
			}
			echo "</div></div>";
		echo "</div>";		
		echo "<div class='row'>";
		echo "</div>";			
		echo "<div class='row'>";
		$this->style->Graph('graph-consommation','col-lg-8'); //graph Consumed
		$this->style->Graph('graph-disk','col-lg-4',220); //graph Disk
		echo "</div>";	
		echo "<div class='row'>";//VM  Buttons List
			$SQL='SELECT 
			vm_moref,
			vmname,
			vm_cpu_usage,
			vm_mem_usage, 
			round((vm_mem_usage/vm_mem_total)*100) as prct_mem,
			round((vm_cpu_usage/vm_cpu_total)*100) as prct_cpu,
			vm_powerstate
			FROM clustersandhostsandguests WHERE vm_date="'.Settings::$timestamp.'" and  cluster_moref="'.$this->moref.'" order by vmname';
			$RsltVM = $this->MySQL->TabResSQL($SQL);
			echo "<div class='panel-yellow-titletab'>Virtual machines <b>". count($RsltVM)."</b></div>";
			echo "<div class='panel panel-yellow-bodytab vmlist-stats'>";
			echo "<div class='panel-body'>";
			foreach ($RsltVM as $value) {
				if ($value['vm_powerstate'] != "poweredOn"){$btncolor="btn-undefined";} else {$btncolor="btn-info";}
				if ($value['prct_cpu'] > 80 or $value['prct_mem']>80){$btncolor="btn-warning";}
				if ($value['prct_cpu'] > 95 or $value['prct_mem']>95){$btncolor="btn-danger";}
				echo "<button class='btn $btncolor btn-circle' style='margin-left:2px' data-toggle='tooltip' data-placement='top' data-href='vm' data-moref='".$value['vm_moref']."' title='Name :".$value['vmname']."\nCPU: ".$value['prct_cpu']."%\nRAM: ".$value['prct_mem']."%'><i class='fa fa-laptop'></i></button>";
			}
			echo "</div></div>";
		echo "</div>";
		echo "<div class='row'>";
			echo "<div class='col-lg-8'>"; //Ressource Pool
			$SQL='SELECT
			respool_name as "Name",
			respool_cpu_reservation as "CPU Reserv.",
			respool_cpu_limit as "CPU Limit",
			respool_cpu_expand as "CPU expand",
			respool_cpu_shares as "CPU Shares",
			respool_mem_reservation as "RAM Reserv.",
			respool_mem_limit as "RAM Limit",
			respool_mem_expand as "RAM Expand",
			respool_mem_shares as "RAM Share"
			FROM clusterresourcepools WHERE respool_name !="Resources" AND cluster_moref="'.$this->moref.'" order by respool_mem_shares desc';
			$Resultats = $this->MySQL->TabResSQL($SQL);
			$this->style->Tableau($Resultats,"respools-stats","Resource Pools",true,"table-simple");
			echo "</div>";	
			echo "<div class='col-lg-4'>"; //Resource Pool Recommandation
			$RPStats = new RPCompute($this->moref);
			$RPStats->compute();
			$RPStats->render();
			echo "</div>";
		echo "</div>";		

		echo "<div class='row'>"; //VM Consummed Historic
			$this->style->Graph('graph-consommation-hist','col-lg-12');
		echo "</div>";		

		echo "<div class='row'>"; //VM Historic
		$this->style->Graph('graph-nombrevm-hist','col-lg-12');
		echo "</div>";

		echo "<div class='row'>";// VM Top Max
		$th = array("VM","Memory Usage","CPU Usage");
		$this->style->TableauVide($th,"dashboard","","vmtoplisttable dashfirstlink");
		echo "</div>";

		echo "<div class='row'>";// Hypervisor Table
		$th = array("Nom","Version","Vm","Free space (Go)","Usage");
		$this->style->TableauVide($th,"dashboard","","hostlisttable dashfirstlink");
		echo "</div>";


		} else {
			echo "<div>Cet objet n'existait pas en date du ".Settings::$timestamp."</div>";
		}
	}
}
?>
