<?php

class Menu implements HTMLObject
{

	
	
	function __construct(){
            $this->MySQL = new SGBD();
            $this->MySQL->connect();
	}
	
	function query($sql){
		return $this->MySQL->TabResSQL($sql);
	}
	
	function toHTML() {
				
		
	echo '<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input id="search" type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button id="search-btn" class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a  class="item" href="#" data-moref="" data-href="dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Clusters<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">';
					$SQL='select distinct clustername,cluster_moref from clusters where cluster_date="'.Settings::$timestamp.'"  order by clustername';
					$ClustersList = $this->query($SQL);
					foreach ($ClustersList as $value)  {
					echo "<li><a class='item' href='#' data-moref='".$value['cluster_moref']."' data-href='cluster'>".$value['clustername']."</a>";
					}
                      echo '</ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-building fa-fw"></i> Hosts<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">';
					// Esxi in cluster
					foreach ($ClustersList as $value)  {
						echo "<li><a href='#'>".$value['clustername']."</a>";
						$SQLHosts='select distinct hostname,moref from hosts where date="'.Settings::$timestamp.'" and moref_cluster ="'.$value['cluster_moref'].'" order by hostname';
						$HostsList = $this->query($SQLHosts);
							echo '<ul class="nav nav-third-level collapse">';
							foreach ($HostsList as $value)  {
								echo "<li><a class='item' href='#' data-moref='".$value['moref']."' data-href='host'>".$value['hostname']."</a>";
							}
							echo '</ul>';
						echo "</li>";
					}	
					//ESXi out of cluster
					$SQLHosts='select distinct hostname,moref from hosts where date="'.Settings::$timestamp.'" and moref_cluster IS NULL order by hostname';
					$HostsList = $this->query($SQLHosts);
					foreach ($HostsList as $value)  {
						echo "<li><a class='item' href='#' data-moref='".$value['moref']."' data-href='host'>".$value['hostname']."</a></li>";
					}						
			 echo '</ul>
			 
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
			    <a  class="item" href="#" data-moref="" data-href="datastore"><i class="fa fa-database fa-fw"></i> Datastores</a>';
#                          <a href="#"><i class="fa fa-database fa-fw"></i> Datastores<span class="fa arrow"></span></a>
#                           <ul class="nav nav-second-level collapse">';
#					// Cluster
#					foreach ($ClustersList as $value)  {
#						echo "<li><a class='item' href='#' data-moref='".$value['cluster_moref']."' data-href='datastore'>".$value['clustername']."</a>";
#					}	
#					//ESXi out of cluster
#					$SQLHosts='select distinct hostname,moref from hosts where date="'.Settings::$timestamp.'" and moref_cluster IS NULL order by hostname';
#					$HostsList = $this->query($SQLHosts);
#					foreach ($HostsList as $value)  {
#						echo "<li><a class='item' href='#' data-moref='".$value['moref']."' data-href='datastore'>".$value['hostname']."</a></li>";
#					}						
#			 echo '</ul>
			 echo '
			</li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->';
	}
}

?>
