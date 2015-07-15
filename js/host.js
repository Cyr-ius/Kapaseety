function loadchart_host(){

	moref = $('#moref').html();
	
	//Graph CPU
	$('#graph-cpu').highcharts(Highcharts.merge(gaugeOptions,{
	title: { text: 'Compute'},
        yAxis: { min: 0, max: 100},
        credits: {enabled: false},
	series: [{	name: 'CPU', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y}</span><span style="font-size:14px">%</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));	
	// Graph Mem
	$('#graph-mem').highcharts(Highcharts.merge(gaugeOptions,{
	title: { text: 'Memory'},
        yAxis: { min: 0, max: 100},
        credits: {enabled: false},
	series: [{	name: 'Memoire', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y}</span><span style="font-size:14px">%</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));	
	//Graph Disk
	$('#graph-disk').highcharts(Highcharts.merge(gaugeOptions,{
	title: { text: 'Datastore'},
        yAxis: { min: 0, max: 100},
        credits: {enabled: false},
	series: [{	name: 'Disque', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y}</span><span style="font-size:14px">%</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));	
	
	// Load series for Graph below
	getjson("WS_Stats.host_serie",{"moref":moref,"select":"round(cpu_usage*100/cpu_total),round(mem_usage*100/mem_total),round(datastore_used*100/datastore_total)"},function(data){
		$('#graph-cpu').highcharts().series[0].setData([data.result[0]]);
		$('#graph-mem').highcharts().series[0].setData([data.result[1]]);
		$('#graph-disk').highcharts().series[0].setData([data.result[2]]);
	});
	
	// Graph Consommation
        $('#graph-consommation').highcharts({
            chart: {type: 'spline',zoomType: 'x',borderRadius:5,borderwidth:0,shadow: true,marginLeft:100,marginRight:50}, title: {text: 'Consommation moyenne par jour'}, subtitle: {text: 'un point de mesure par jour'},
           credits: {enabled: false},
            xAxis: {type:'datetime',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false}},
            yAxis: {title: {text: 'cpu usage (%)'}, min: 0},
            tooltip: {headerFormat: '<b>{series.name}</b><br>',pointFormat: '{point.x:%e. %b}: {point.y:.2f} %'},
            series: [{name: 'CPU'}, { name: 'Memoire'}, {name: 'Disque'}]
        });
	

	getjson("WS_Stats.host_hist",{"moref":moref,"select":"unix_timestamp(date)*1000,round(cpu_usage*100/cpu_total)"},function(data){
		$('#graph-consommation').highcharts().series[0].setData(data.result);
	});
	getjson("WS_Stats.host_hist",{"moref":moref,"select":"unix_timestamp(date)*1000,round(mem_usage*100/mem_total)"},function(data){
		$('#graph-consommation').highcharts().series[1].setData(data.result);
	});
	getjson("WS_Stats.host_hist",{"moref":moref,"select":"unix_timestamp(date)*1000,round(datastore_used*100/datastore_total)"},function(data){
		$('#graph-consommation').highcharts().series[2].setData(data.result)
	});
}
