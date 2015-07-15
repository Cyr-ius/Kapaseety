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
		return $this->MySQL->ResSQL('SELECT clustername FROM clusters where cluster_date="'.Settings::$timestamp.'"');
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}
	
	function set_pool($variables){	
		$rpc_moref = "rpc_".$variables['moref'];
		$rp_values = serialize($variables['setting']);
		return Settings::set(array($rpc_moref=>$rp_values));
	}
}
?>
