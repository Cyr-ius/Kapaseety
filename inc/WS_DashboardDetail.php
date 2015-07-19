<?php
class WS_DashboardDetail {

	private $MySQL;

	function __construct(){
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function get_clusterlistres($variables) {

		$table = 'data_clusters';
		$primaryKey = 'cluster_moref';
		$columns = array(
			array( 'db' => 'clustername', 'dt' => 0 ),
			array( 'db' => 'cluster_hosts_total',  'dt' => 1 ),
			array( 'db' => 'cluster_vms_total',   'dt' => 2 ),
			array( 'db' => 'round(cluster_mem_total/1024)', 'dt' => 3 ),
			array( 'db' => 'round(cluster_mem_realcapacity/1024)', 'dt' => 4 ),
			array( 'db' => 'round(cluster_mem_usage/1024)',     'dt' => 5 ),
			array( 'db' => 'round(cluster_cpu_total/1000)',     'dt' => 6 ),
			array( 'db' => 'round(cluster_cpu_realcapacity/1000)',     'dt' => 7 ),
			array( 'db' => 'round(cluster_cpu_usage/1000)',     'dt' => 8 ),
			array('db' => 'cluster_moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'cluster');})
		);
		$whereResult=null;
		$whereAll= array('db'=>'cluster_date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
		
	}

	public function datacenter_view($variables){
		try {
		$rslt =  $this->MySQL->TabResSQL('select distinct clustername,hostname,vmname,vm_mem_usage FROM clustersandhostsandguests where cluster_date="'.$this->MySQL->esc_str(Settings::$timestamp).'"');
		foreach ($rslt as $item) {
			$serie[ $item['clustername'] ] [ $item['hostname'] ] [ $item['vmname'] ]=$item['vm_mem_usage'];
		}
		
		return $serie;
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}

	public function vms_total($variables){
		try {
		 return $this->MySQL->ResSQL('select count(vmname) as vms_count from data_vms where vm_date="'.$this->MySQL->esc_str(Settings::$timestamp).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	
	public function hosts_total($variables){
		try {
		 return $this->MySQL->ResSQL('select count(hostname) as hosts_count from data_hosts where date="'.$this->MySQL->esc_str(Settings::$timestamp).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
}
?>
