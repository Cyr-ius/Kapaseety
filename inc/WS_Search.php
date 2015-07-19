<?php
class WS_Search
{
	private $MySQL;

	function __construct(){
		Settings::init();
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function search_cluster($variables) {

		$table = 'data_clusters';
		$primaryKey = 'cluster_moref';
		$columns = array(
			array( 'db' => 'clustername', 'dt' => 0 ),
			array('db' => 'cluster_moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'cluster');})
		);
		$whereResult=null;
		$whereAll= array('db'=>'cluster_date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
		
	}

	function search_host($variables) {

		$table = 'data_hosts';
		$primaryKey = 'moref';
		$columns = array(
			array( 'db' => 'hostname', 'dt' => 0 ),
			array( 'db' => 'cluster', 'dt' => 1 ),
			array('db' => 'moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'host');}),
		);
		$whereResult=null;
		$whereAll= array('db'=>'date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
		
	}

	function search_vm($variables) {

		$table = 'guestsandhosts';
		$primaryKey = 'vm_moref';
		$columns = array(
			array( 'db' => 'vmname', 'dt' => 0 ),
			array( 'db' => 'hostname', 'dt' => 1 ),
			array( 'db' => 'vm_guest_os', 'dt' => 2 ),
			array( 'db' => 'vm_cpu_num', 'dt' => 3 ),
			array( 'db' => 'vm_cpu_usage',   'dt' => 4 ),
			array( 'db' => 'vm_cpu_total', 'dt' => 5 ),
			array( 'db' => 'vm_mem_usage',  'dt' => 6 ),
			array( 'db' => 'vm_mem_total', 'dt' => 7 ),
			array('db' => 'vm_moref','dt' => 'DT_RowData','formatter' => function( $d, $row ){return array('data-moref'=>$d,'data-href'=>'vm');}),
		);
		$whereResult=array('db'=>'vmname is not null');
		$whereAll= array('db'=>'date="'.Settings::$timestamp.'"');

		// SQL server connection information
		$sql_details = array('user' => Settings::$sgbd_user,'pass' => Settings::$sgbd_password,'db'   => Settings::$sgbd_database,'host' => Settings::$sgbd_server);
		return SSP::complex( $variables, $sql_details, $table, $primaryKey, $columns,$whereResult,$whereAll );
		
	}	



}
?>
