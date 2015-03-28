<?php
class WS_DatastoreDetail {

	private $MySQL;

	function __construct(){
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function get_datastoreusage(){
		try {
		 $rslt = $this->MySQL->TabResSQL('
			SELECT clustername,
			round((cluster_datastore_free/cluster_datastore_total)*100) as prct_free,
			round((cluster_datastore_used/cluster_datastore_total)*100) as prct_used
			FROM clusters where cluster_date="'.Settings::$timestamp.'"');
		foreach ($rslt as $item){
			$free[]=$item['prct_free'];
			$used[]=$item['prct_used'];
		}
		$response=array("free"=>$free,"used"=>$used);
		return $response;
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}

}
?>
