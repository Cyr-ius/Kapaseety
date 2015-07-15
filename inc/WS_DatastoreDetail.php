<?php
class WS_DatastoreDetail {

	private $MySQL;

	function __construct(){
		$this->MySQL = new SGBD();
		$this->MySQL->connect();
	}

	function get_datastoreusage(){
		try {
		$free = null;
		$used = null;
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

	function get_datastorehist($variables){
		try {
		$current="";
		$rslt =  $this->MySQL->TabResSQL('select distinct clustername FROM data_clusters order by clustername');
		foreach ($rslt as $cluster) {
			$data = null;
			$datas =  $this->MySQL->TabResSQL('select unix_timestamp(cluster_date) as ladate,cluster_datastore_total FROM data_clusters WHERE clustername="'.$cluster['clustername'].'"');
			foreach ($datas as $item){
				$data[]=array($item['ladate'],$item['cluster_datastore_total']);
			}
			$serie[]=array("name"=>$cluster['clustername'],"data"=>$data);
		}
		
		return $serie;
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}

	function get_datastoreusagehist($variables){
		try {
		$current="";
		$rslt =  $this->MySQL->TabResSQL('select distinct clustername FROM data_clusters order by clustername');
		foreach ($rslt as $cluster) {
			$data = null;
			$datas =  $this->MySQL->TabResSQL('select unix_timestamp(cluster_date) as ladate,cluster_datastore_used FROM data_clusters WHERE clustername="'.$cluster['clustername'].'"');
			foreach ($datas as $item){
				$data[]=array($item['ladate'],$item['cluster_datastore_used']);
			}
			$serie[]=array("name"=>$cluster['clustername'],"data"=>$data);
		}
		
		return $serie;
		 } catch (Exception $e) { throw new jsonRPCException($e);}
	}

}
?>
