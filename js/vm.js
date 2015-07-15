function loadchart_vm() {

	moref = $('#moref').html();
	//Graph CPU
	$('#graph-cpu').highcharts(Highcharts.merge(gaugeOptions,{
	title:{ text: 'Compute'},
        yAxis: { min: 0, max: 100,},
        credits: {enabled: false},
	series: [{	name: 'CPU', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:20px">{y}</span><span style="font-size:20px"> %</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));	
	//Graph MEM
	$('#graph-mem').highcharts(Highcharts.merge(gaugeOptions,{
	title:{ text: 'Memory'},
        yAxis: { min: 0, max: 100},
        credits: {enabled: false},
	series: [{	name: 'Memoire', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:20px">{y}</span><span style="font-size:20px"> %</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));		
	//Graph Disk
	$('#graph-disk').highcharts(Highcharts.merge(gaugeOptions,{
	title:{ text: 'Disk'},
        yAxis: { min: 0, max: 100},
        credits: {enabled: false},
	series: [{	name: 'Disques', 
			data: [0], 
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:20px">{y}</span><span style="font-size:20px"> %</span></div>'},
			tooltip: {valueSuffix: ' %'}
		   }]
	}));	
	
	// Load series for Graph below
	getjson("WS_Stats.vm_serie",{"moref":moref,"select":"round(vm_cpu_usage*100/vm_cpu_total),round(vm_mem_usage*100/vm_mem_total)"},function(data){
			$('#graph-cpu').highcharts().series[0].setData([data.result[0]]);
			$('#graph-mem').highcharts().series[0].setData([data.result[1]]);
	});

	//Graph Consommation
        $('#graph-consommation').highcharts({
            chart: {type: 'spline',zoomType: 'x',borderRadius:5,borderwidth:0,shadow: true,marginLeft:100,marginRight:150}, title: {text: 'Consommation moyenne par jour'}, subtitle: {text: 'un point de mesure par jour'},
	    credits: {enabled: false},
            xAxis: {type:'datetime',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false}},
            yAxis: [
		{labels: {format: '{value} Mhz',style: {color: Highcharts.getOptions().colors[0]}},title: {text: 'CPU Usage (Mhz)', style: {color: Highcharts.getOptions().colors[0]}}, min: 0},
		{labels: {format: '{value} Mo',style: {color: Highcharts.getOptions().colors[1]}}, title: {text: 'Mem Usage (Mo)', style: {color: Highcharts.getOptions().colors[1]}}, min: 0,opposite: true}
		],
            tooltip: {shared:true},
	   series: [{name: 'CPU',data:[0]}, {name: 'RAM',data:[0],yAxis: 1}]
        });
	getjson("WS_Stats.vm_hist",{"moref":moref,"select":"unix_timestamp(vm_date)*1000,vm_cpu_usage"},function(data){
		$('#graph-consommation').highcharts().series[0].setData(data.result);
	});
	getjson("WS_Stats.vm_hist",{"moref":moref,"select":"unix_timestamp(vm_date)*1000,vm_mem_usage"},function(data){
		$('#graph-consommation').highcharts().series[1].setData(data.result);
	});
	
}
