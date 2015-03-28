<?php
class Footer {
	
	function _construct() {
	}

	function scripts(){
		echo '
		<script src="3rdparty/jquery/jquery-1.11.1.min.js"></script>
		<script src="3rdparty/bootstrap/js/bootstrap.min.js"></script>
		<script src="3rdparty/metismenu/js/jquery.metisMenu.min.js"></script>			
		<script src="3rdparty/highcharts/js/highcharts.js"></script>
		<script src="3rdparty/highcharts/js/highcharts-more.js"></script>
		<script src="3rdparty/highcharts/js/modules/solid-gauge.src.js"></script>
		<script src="3rdparty/dataTables/js/jquery.dataTables.min.js"></script>
		<script src="3rdparty/dataTables/js/dataTables.bootstrap.js"></script>
		<script src="3rdparty/form2js/form2js.js"></script>
		<script src="3rdparty/form2js/js2form.js"></script>
		<script src="3rdparty/form2js/jquery.toObject.js"></script>	
		<script src="3rdparty/bootstrap-slider/bootstrap-slider.min.js"></script>
		<script src="js/kapaseety.js"></script>';			
	}
	
	function toHTML() {
		echo '<div id="filinfo" class="alert alert-info" style="display:none;position:absolute;bottom:0;height:20px;width:100%" role="alert"></div>';
	}
}
?>
