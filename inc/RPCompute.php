<?php
class RPCompute
{

private $MySQL;
private $RatioRP;
private $Cluster_Stat;
private $UpperLimit = 10000;
private $moref;
private $MinPoolCPUshares = 100;
private $MinPoolMemshares = 100;
private $rps;

	function __construct($moref){
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
		$this->moref = $moref;
		if (Settings::get("rpc_".$moref)) {
			$this->RatioRP=unserialize(Settings::get("rpc_".$moref)[0]);
		} else {
			$this->RatioRP=array("gold"=>4,"silver"=>2,"bronze"=>1);
		}
	}
	
	function compute() {

		$MinPoolCPUshares = $this->MinPoolCPUshares;
		$MinPoolMemshares = $this->MinPoolMemshares;
		
	
		$arrayindex = 0;
		foreach ($this->RatioRP as $key=>$ratio) {
			$SQL='SELECT respool_name as rpname, count(vmname) as vms_num,sum(vm_mem_total) as mem_total, sum(vm_cpu_num) as vcpu_total 
				FROM vmresourcepools WHERE vm_date="'.Settings::$timestamp.'" and respool_moref_cluster="'.$this->moref.'" and respool_name="'.$key.'" group by respool_name';
			$Cluster_Stat = $this->MySQL->TabResSQL($SQL);
			$name =  strtolower($key);
			$vm_num =0;
			$cpu = 0;
			$mem = 0;
			if (count($Cluster_Stat)) {
				$vm_num = strtolower($Cluster_Stat[0]['vms_num']);
				$cpu = strtolower($Cluster_Stat[0]['vcpu_total']);
				$mem = strtolower($Cluster_Stat[0]['mem_total']);
			}
			
			$ResourcePools[$arrayindex] = $name;
			$PoolCPUShares[$arrayindex] =$ratio*$cpu;
			$PoolMemShares[$arrayindex] =$ratio*$mem;
			$arrayindex += 1;
		}
				
		$MaxMemShares = $PoolMemShares[0];
		for ($i = 0; $i < count($PoolMemShares); $i++) {
			if ($PoolMemShares[$i] > $MaxMemShares){
				$MaxMemShares = $PoolMemShares[$i];
			}
		}

		$MaxCPUShares  = $PoolCPUShares[0];
		for ($i = 0; $i < count($PoolCPUShares); $i++) {
			if ($PoolCPUShares[$i] > $MaxCPUShares){
				$MaxCPUShares = $PoolCPUShares[$i];
			}
		}		

		If ($MaxCPUShares > 0) {
			#Set the highest share to a maximum of $SharesUpperLimit. All other shares will be a proprtional value of $SharesUpperLimit
			$CPUShareMultiplier = $this->UpperLimit  / $MaxCPUShares;
			for ($i=0; $i < count($PoolCPUShares); $i++)
			{
				$PoolCPUShares[$i] = $PoolCPUShares[$i] * $CPUShareMultiplier;
				#If we're below the minimum, readjust
				if ($PoolCPUShares[$i] < $MinPoolCPUshares)  { $PoolCPUShares[$i] = $MinPoolCPUshares;}
			}

		} else {
			#Set it to the minimum specified by the user
			for ($i=0; $i < count($PoolCPUShares); $i++)
			{
				$PoolCPUShares[$i] = $MinPoolCPUshares;
			}
		}	

		If ($MaxMemShares > 0) {
			#Set the highest share to a maximum of $SharesUpperLimit. All other shares will be a proprtional value of $SharesUpperLimit
			$MemShareMultiplier  = $this->UpperLimit  / $MaxMemShares;
			for ($i=0; $i < count($PoolMemShares); $i++)
			{
				$PoolMemShares[$i] = $PoolMemShares[$i] * $MemShareMultiplier;
				#If we're below the minimum, readjust
				if ($PoolMemShares[$i] < $MinPoolMemshares)  { $PoolMemShares[$i] = $MinPoolMemshares;}
			}

		} else {
			#Set it to the minimum specified by the user
			for ($i=0; $i < count($PoolMemShares); $i++)
			{
				$PoolMemShares[$i] = $MinPoolMemshares;
			}
		}
		
		$rps = array();
		for ($i=0; $i < count($ResourcePools); $i++)
			{
				$line = array();
				$line['Name'] = $ResourcePools[$i];
				$line['CPU Share'] = round($PoolCPUShares[$i]);
				$line['MEM Share'] = round($PoolMemShares[$i]);
				$rps[] = $line;
			}
		
		$this->rp = $rps;
		return $this->rp;
	}
	function render(){
			$style = new Style();
			$style->Tableau($this->rp,"respools-stats",
			"
			<div class='row' style='margin-right:10px'>
			<div class='col-sm-11'>Recomandations</div>
			<div class='col-sm-1'>
			<button class='btn btn-default btn-sm' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
			<span class='glyphicon glyphicon-cog'></span></button></div>
			</div>
			<div class='collapse' id='collapseExample'>
			  <div style='position:relative'>
			    <input id='slider_rp' type='text'/><br/>
			  </div>
			</div>
			",true,"table-simple");
	}
}
?>
