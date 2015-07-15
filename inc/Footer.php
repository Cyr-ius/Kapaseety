<?php
class Footer {
	
	function _construct() {
	}

	function scripts(){
		echo '
		<script src="3rdparty/jquery/jquery-1.11.1.min.js"></script>
		<script src="3rdparty/bootstrap/js/bootstrap.min.js"></script>
		<script src="3rdparty/metismenu/js/jquery.metisMenu.min.js"></script>			
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/heatmap.js"></script>
		<script src="http://code.highcharts.com/modules/treemap.js"></script>
		<script src="http://code.highcharts.com/highcharts-more.js"></script>
		<script src="http://code.highcharts.com/modules/solid-gauge.src.js"></script>
		<script src="3rdparty/dataTables/js/jquery.dataTables.min.js"></script>
		<script src="3rdparty/dataTables/js/dataTables.bootstrap.js"></script>
		<script src="3rdparty/form2js/form2js.js"></script>
		<script src="3rdparty/form2js/js2form.js"></script>
		<script src="3rdparty/form2js/jquery.toObject.js"></script>	
		<script src="3rdparty/bootstrap-slider/bootstrap-slider.min.js"></script>
		<script src="js/kapaseety.js"></script>	
		<script src="js/dashboard.js"></script>
		<script src="js/cluster.js"></script>	
		<script src="js/host.js"></script>
		<script src="js/vm.js"></script>
		<script src="js/datastore.js"></script>
		';	
		
	      echo "<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->\n
		     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->\n
		        <!--[if lte IE 9]>\n
		        <script src='3rdparty/iecompat/html5shiv.min.js'></script>\n
		        <script src='3rdparty/iecompat/respond.min.js'></script>\n
			 <![endif]-->\n";

	}
	
	function toHTML() {
		echo '<div id="filinfo" class="alert alert-info" style="display:none;position:absolute;bottom:0;height:20px;width:100%" role="alert"></div>';
	}
}
?>
