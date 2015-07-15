<?php
class WS_Stats
{
	private $MySQL;

	function __construct(){
		Settings::init();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}
	
	public function cluster_hist($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_clusters where cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function cluster_serie($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_clusters where cluster_date="'.Settings::$timestamp.'" and cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	
	public function cluster_serie_notmaintenance($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_clusters where cluster_date="'.Settings::$timestamp.'" and cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'" and maintenance="False"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	
	public function host_hist($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_hosts where moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function host_serie($variables){
		try {
		 return $this->MySQL->ResSQL('select '.$this->MySQL->esc_str($variables['select']).' from data_hosts where date="'.Settings::$timestamp.'" and moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	

	public function vm_hist($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_vms where vm_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function vm_serie($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_vms where vm_date="'.Settings::$timestamp.'" and vm_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function vms_total($variables){
		try {
		 return $this->MySQL->ResSQL('select count(vmname) as vms_count from data_vms where vm_date="'.Settings::$timestamp.'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	
	public function hosts_total($variables){
		try {
		 return $this->MySQL->ResSQL('select count(hostname) as hosts_count from data_hosts where date="'.Settings::$timestamp.'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	
	public function clusters_hosts_vms($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from ClustersAndHostsAndGuests where cluster_date="'.Settings::$timestamp.'" and  cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'" or moref="'.$this->MySQL->esc_str($variables['moref']).'" or vm_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	
	public function clusters_hosts($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from ClustersAndHosts where cluster_date="'.Settings::$timestamp.'" and cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'" or moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}




}
?>
