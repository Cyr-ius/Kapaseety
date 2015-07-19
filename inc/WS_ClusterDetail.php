<?php
class WS_ClusterDetail {

	private $MySQL;

	function __construct(){
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function compute_rp($variables){	
	 	$rpc = new RPCompute($variables['moref']);
		return $rpc->compute();
	}

	function get_pool($variables){	
	 	$rpc = new RPCompute($variables['moref']);
		return $rpc->RatioRP;
	}

	function get_clusterlist(){	
		try {
		return $this->MySQL->ResSQL('SELECT clustername FROM clusters where cluster_date="'.$this->MySQL->esc_str(Settings::$timestamp).'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	
	function set_pool($variables){	
		$rpc_moref = "rpc_".$variables['moref'];
		$rp_values = serialize($variables['setting']);
		return Settings::set(array($rpc_moref=>$rp_values));
	}

	function get_hostlist($variables){	

		$table = 'data_hosts';
		$primaryKey = 'moref';
		$columns = array(
			array( 'db' => 'hostname', 'dt' => 0 ),
			array( 'db' => 'version',  'dt' => 1 ),
			array( 'db' => 'vm_num',   'dt' => 2 ),
			array( 'db' => 'datastore_free', 'dt' => 3 ),
			array( 'db' => 'datastore_used', 'dt' => 4 ),
			array('db' => 'moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'host');})
		);
		$whereResult= array('db'=>'moref_cluster="'.$variables['cluster_moref'].'"');
		$whereAll= array('db'=>'date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex($variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );

	}

	function get_vmtoplist($variables){	

		$table = 'clustersandhostsandguests';
		$primaryKey = 'vm_moref';
		$columns = array(
			array( 'db' => 'vmname', 'dt' => 0 ),
			array( 'db' => 'vm_mem_usage',  'dt' => 1 ),
			array( 'db' => 'vm_cpu_usage',   'dt' => 2 ),
			array('db' => 'vm_moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'vm');})
		);
		$whereResult= array('db'=>'cluster_moref="'.$variables['cluster_moref'].'"');
		$whereAll= array('db'=>'date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );

	}
}
?>
