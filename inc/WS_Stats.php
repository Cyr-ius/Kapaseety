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
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_clusters where cluster_date="'.$this->MySQL->esc_str(Settings::$timestamp).'" and cluster_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}		
	public function host_hist($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_hosts where moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function host_serie($variables){
		try {
		 return $this->MySQL->ResSQL('select '.$this->MySQL->esc_str($variables['select']).' from data_hosts where date="'.$this->MySQL->esc_str(Settings::$timestamp).'" and moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}	

	public function vm_hist($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_vms where vm_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	public function vm_serie($variables){
		try {
		 return $this->MySQL->ResSQL('select '. $this->MySQL->esc_str($variables['select']).' from data_vms where vm_date="'.$this->MySQL->esc_str(Settings::$timestamp).'" and vm_moref="'.$this->MySQL->esc_str($variables['moref']).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}

	function get_vmlist($variables){	
		$table = 'data_vms';
		$primaryKey = 'vm_moref';
		$columns = array(
			array( 'db' => 'vmname', 'dt' => 0 ),
			array( 'db' => 'vm_guest_os', 'dt' => 1 ),
			array( 'db' => 'vm_cpu_num', 'dt' => 2 ),
			array( 'db' => 'vm_cpu_usage',   'dt' => 3 ),
			array( 'db' => 'vm_cpu_total', 'dt' => 4 ),
			array( 'db' => 'vm_mem_usage',  'dt' => 5 ),
			array( 'db' => 'vm_mem_total', 'dt' => 6 ),
			array('db' => 'vm_moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'vm');})
		);
		$whereResult= array('db'=>'moref_host like "'.$variables['moref'].'"');
		$whereAll= array('db'=>'vm_date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
	}

	function get_hoststat($variables){	
		$table = 'data_hosts';
		$primaryKey = 'moref';
		$columns = array(
			array( 'db' => 'date', 'dt' => 0 ),
			array( 'db' => 'cpu_socket_num', 'dt' => 1 ),
			array( 'db' => 'cpu_usage', 'dt' => 2 ),
			array( 'db' => 'cpu_total', 'dt' => 3 ),
			array( 'db' => 'mem_usage',  'dt' => 4 ),
			array( 'db' => 'mem_total', 'dt' => 5 ),
			array( 'db' => 'datastore_free', 'dt' => 6 ),
			array( 'db' => 'datastore_total', 'dt' => 7 )
		);
		$whereResult= array('db'=>'moref="'.$variables['moref'].'"');
		$whereAll= array('db'=>'date >" ('.Settings::$timestamp.' - INTERVAL 14 DAY )"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
	}



}
?>
