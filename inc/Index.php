<?php

class Index
{	
	function __construct(){
		
		/// Initialize variables
		Settings::init();

		$menu = (isset($_GET['m'])? $_GET['m'] : null);
		$display_menu = (isset($_GET['my'])? true : false);
		/// Check what to do
		switch ($menu){
			
			case "cluster":		$page = new ClusterDetail($_GET['moref']);
							break;
			case "host":		$page = new HostDetail($_GET['moref']);
							break;							
			case "vm":		$page = new VmDetail($_GET['moref']);
							break;
			case "datastore_usage":	$page = new DatastoreDetail($_GET['moref']);
							break;
			case "datastore_hist":	$page = new DatastoreHist($_GET['moref']);
							break;
			case "vms":		$page = new VmList();
							break;
			case "hosts":		$page = new HostList();
							break;
			case "dashboard":	$page = new Dashboard();
							break;
			case "search":		$page = new Search($_GET['search']);
							break;
			case "settings":	$page= new Settings();
							break;
			default	:		
						$page = new Dashboard();
							break;							
		}
		//Display page
		if ($display_menu or !isset($menu)) {
			$displaypage = new MainPage();
			$displaypage->content = $page;
			$displaypage->toHTML();
			$menu = (isset($_GET['m'])? $_GET['m'] : 'dashboard');
			echo "<script>loadchart_".$menu."();</script>";
		} else {
			$page->toHTML();
		}
	}
}

?>
