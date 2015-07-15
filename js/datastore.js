function loadchart_datastore_usage(){

	// Graph Size Disk
	$('#graph-datastore_usage').highcharts(Highcharts.merge(graphOptions,{
        chart: { type: 'bar',marginLeft:100,marginRight:50}, title: {text: 'Datastore Usage'},subtitle: {text:'en %'},
        xAxis: { title: {text: 'Entitie'},categories: [],plotLines: [{color: 'red',dashStyle: 'longdashdot', value: 80,width: 2}]},
        yAxis: { title: {text: '%'},min:0,max:100},
	tooltip: {valueSuffix: ' %'},
        plotOptions: {series: {stacking: 'normal'}},
	series:[{"name":"Free"},{"name":"Used"}],
  	
	}));

	getjson("WS_ClusterDetail.get_clusterlist",null,function(data){
		$('#graph-datastore_usage').highcharts().xAxis[0].setCategories(data.result);
	});
	getjson("WS_DatastoreDetail.get_datastoreusage",null,function(data){
		$('#graph-datastore_usage').highcharts().series[0].setData(data.result.free);
		$('#graph-datastore_usage').highcharts().series[1].setData(data.result.used);
	});
}

function loadchart_datastore_hist(){

	// Graph Total Capacity History
        $('#graph-datastore_hist').highcharts({
            chart: {type: 'spline',zoomType: 'x',borderRadius:5,borderwidth:0,shadow: true,marginLeft:100,marginRight:50}, title: {text: 'History Total Disk capacity'},
            credits: {enabled: false},
            xAxis: {type:'datetime',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false}},
            yAxis: {title: {text: '(Go)'}, min: 0},
            tooltip: {headerFormat: '<b>{series.name}</b><br>',pointFormat: '{point.x:%e. %b}: {point.y:.2f} Go'},
        });

	getjson("WS_DatastoreDetail.get_datastorehist",null,function(data){
		for (var i = 0; i < data.result.length; i++) {
			$('#graph-datastore_hist').highcharts().addSeries(data.result[i]);
		}
	});

	// Graph Usage Capacity History
        $('#graph-datastore_usage_hist').highcharts({
            chart: {type: 'spline',zoomType: 'x',borderRadius:5,borderwidth:0,shadow: true,marginLeft:100,marginRight:50}, title: {text: 'History Usage Disk capacity'},
            credits: {enabled: false},
            xAxis: {type:'datetime',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false}},
            yAxis: {title: {text: '(Go)'}, min: 0},
            tooltip: {headerFormat: '<b>{series.name}</b><br>',pointFormat: '{point.x:%e. %b}: {point.y:.2f} Go'},
        });

	getjson("WS_DatastoreDetail.get_datastoreusagehist",null,function(data){
		for (var i = 0; i < data.result.length; i++) {
			$('#graph-datastore_usage_hist').highcharts().addSeries(data.result[i]);
		}
	});

}
