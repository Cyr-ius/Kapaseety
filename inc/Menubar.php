<?php

class Menubar  implements HTMLObject
{

	private $menu;

	
	function __construct(){
            $this->MySQL = new SGBD();
            $this->MySQL->connect();
            $this->menu = new Menu();
	}
	
	function toHTML() {
        
	echo '
        <nav  id="topmenu" class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" data-toggle="modal" data-target="#about" href="#"><img src="'.Settings::$logo_path.'" height="40px" width="120px" style="margin-top:-10px"> '.Settings::$name.'</a>
            </div>
            <!-- /.navbar-header -->

            <form  id="datesearch" class="navbar-form navbar-right" role="datalist">
                <div class="form-group">
                    <select id="madate" class="form-control">';
                        $SQL = "select distinct date as madate from data_hosts order by date desc";
			$DateRslt = $this->MySQL->TabResSQL($SQL);
			if (!Settings::$timestamp) {                        
				Settings::$timestamp = $DateRslt[0]['madate'];
	                        setcookie("timestamp", $DateRslt[0]['madate']);
			}
                        foreach ($DateRslt as $key=>$value) {
			   if (Settings::$timestamp==$value['madate']) {
                            echo "<option selected>".$value['madate']."</option>";
			   } else {
                            echo "<option>".$value['madate']."</option>";
			   }

                        }
                echo '</select>
                </div>
            </form>
            <ul class="nav navbar-top-links navbar-right">';
		$SQL = "SELECT DISTINCT LEAST( cluster_vmcpu_left, cluster_vmmem_left ) AS vm_left, clustername, cluster_moref
				FROM data_clusters
				WHERE cluster_date = '".Settings::$timestamp."' AND (cluster_vmmem_left <10 OR cluster_vmcpu_left <10)";
		$AlertRslt = $this->MySQL->TabResSQL($SQL);
                if  (count($AlertRslt) > 0) {
    echo '
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">';
                        foreach ($AlertRslt as $key=>$value) {
                              echo'  <li>
                                    <a class="item" href="#" data-href="cluster" data-moref="'.$value['cluster_moref'].'">
                                        <div>
                                            <i class="fa fa-comment fa-fw"></i> '.$value['clustername'].'
                                            <span class="pull-right text-muted small">Cluster is full , left '.$value['vm_left'].' vm</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>';
                        }
                    echo '
                        <li>
                            <a class="text-center item" href="#" data-href="dashboard">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>';
                }
    echo '
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a class="item" href="#" data-href="settings"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
	';
	$this->menu->toHTML();
  echo "</nav>";
	}
}

?>
