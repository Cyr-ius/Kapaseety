
function loadslider_cluster(json){
	val1 = json.result.bronze;
	val2 = json.result.silver+json.result.bronze;
	$("#slider_rp").slider({ id: "slider_rpc", min: 0, max: 7, range: true, value: [val1, val2],tooltip_split:'true' });

	//Change Ressource Pool Slider
	$('#slider_rp').on('slideStop',function(data){
	   jsonObj = {"moref":moref,"setting":{"gold":(7 - data.value[1]),"silver":(data.value[1]-data.value[0]),"bronze":data.value[0]}};
	   getjson("WS_ClusterDetail.set_pool",jsonObj,function(){
			rpstable.DataTable().ajax.reload();
	   });
	});
}

function loadchart_cluster(){
	
	moref = $('#moref').html();

	//Graph Left VM			
	$('#graph-vm-left').highcharts(Highcharts.merge(gaugeOptions,{
	chart:{shadow:true},
        yAxis: { min: 0, max: 50, title: { text: 'VM Restante '}},
	title: {text:'Cluster Capacity'},
	series: [{	name: 'Qt', 
			data: [0],
			dataLabels: {format: '<div class="dataLabels" style="text-align:center"><span style="font-size:12px">{y} </span><span style="font-size:12px">Machines</span></div>'}
		   }]
	}));
	getjson("WS_Stats.cluster_serie",{"moref":moref,"select":"DISTINCT LEAST(cluster_vmcpu_left,cluster_vmmem_left) as cluster_vm_left,cluster_vms_total as vm_num"},function(data){
		$('#graph-vm-left').highcharts().yAxis[0].setExtremes(0,(data.result[0]+data.result[1]));
		$('#graph-vm-left').highcharts().series[0].setData([data.result[1]]);
		vm_left = data.result[0];
	});

	//Graph Ratio CPU
	$('#graph-ratio-cpu').highcharts(Highcharts.merge(gaugeOptionsSmall,{
	title: null,
        yAxis: { min: 0, max: 8, title: { text: 'Ratio vCpu/pCpu'}},
	series: [{	name: 'Qt', 
			data: [0],
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y} </span></div>'},
			tooltip: {valueSuffix: ' Qt'}
		   }]
	}));
	//Graph Ratio VM
	$('#graph-ratio-vm').highcharts(Highcharts.merge(gaugeOptionsSmall,{
	title: null,
        yAxis: { min: 60, max: 0, title: {text: 'Ratio VM/Host'}},
	series: [{	name: 'Qt', 
			data: [0],
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y} </span></div>'},
			tooltip: {valueSuffix: ' Qt'}
		   }]
	}));
	// Graph HA Cluster
	$('#graph-ha').highcharts(Highcharts.merge(graphOptions,{
        chart: { type: 'bar',marginLeft:100,marginRight:50}, title: {text: 'HA cluster'},subtitle: {text:'avec HA'},
        xAxis: { categories: ['']},
        yAxis: { title: {text: 'Qantity (unit)'},min:0},
	tooltip: {valueSuffix: ' Qt'},
        series: [{name: 'HA Memory',data:[0]},{name: 'HA CPU',data:[0]},{name: 'Hypervisors',data:[0]}]
	}));	
	//Graph Disk space
	$('#graph-disk').highcharts(Highcharts.merge(gaugeOptions,{
	title: {text: 'Disk Usage'},
        yAxis: { min: 0, max: 1000,title: {text: 'Free space'}},
	series: [{	name: 'Go', 
			data: [0],
			dataLabels: {format: '<div style="text-align:center"><span style="font-size:14px">{y} Go</span></div>'},
			tooltip: {valueSuffix: ' Go'}
		   }]
	}));	
		
	// Load series for Graph below
	getjson("WS_Stats.cluster_serie",{"moref":moref,"select":"cluster_vmcpu_average,cluster_vmmem_average,cluster_vcpu_ratio,cluster_vmhost_ratio,cluster_failover_mem,cluster_failover_cpu,cluster_hosts_total,cluster_datastore_used,cluster_datastore_total,cluster_datastore_free"},function(data){
		vm_cpu_average = data.result[0];
		vm_mem_average =data.result[1];
		$('#graph-vm-left').highcharts().yAxis[0].axisTitle.attr({text:'VM Restante :'+vm_left+' (moy. '+vm_cpu_average+'Mhz - '+vm_mem_average+'Mo)'});
		$('#graph-ratio-cpu').highcharts().series[0].setData([data.result[2]]);
		$('#graph-ratio-vm').highcharts().series[0].setData([data.result[3]]);
		$('#graph-ha').highcharts().series[0].setData([data.result[4]]);
		$('#graph-ha').highcharts().series[1].setData([data.result[5]]);
		$('#graph-ha').highcharts().series[2].setData([data.result[6]]);
		$('#graph-ha').highcharts().yAxis[0].setExtremes(0,data.result[6]);
		$('#graph-disk').highcharts().yAxis[0].axisTitle.attr({text:'Free space :'+data.result[9]+'<br/>Total space :'+data.result[8]});
		$('#graph-disk').highcharts().yAxis[0].setExtremes(0,data.result[8]);
		$('#graph-disk').highcharts().series[0].setData(([data.result[7]]));
	})
	
			
	//Graph Consommation
	$('#graph-consommation').highcharts(Highcharts.merge(graphOptions,{
        chart: { type: 'bar',marginLeft:100,marginRight:50}, title: {text: 'Consommation cluster'},subtitle: {text:'avec HA'},
        xAxis: { categories: ['CPU', 'Mémoire']},
        yAxis: { title: {text: 'Consommation (%)'},min:0,max:100},
	tooltip: {valueSuffix: ' %'},
        series: [{name: 'Puissance consommée',data:[0]}, {name: 'Capacite totale',data:[0]}]
	}));	
	getjson("WS_Stats.cluster_serie",{"moref":moref,"select":"round(cluster_cpu_usage*100/cluster_cpu_total) as cpu,round(cluster_mem_usage*100/cluster_mem_total) as mem"},function(data){
		$('#graph-consommation').highcharts().series[0].setData(data.result);
	});
	getjson("WS_Stats.cluster_serie",{"moref":moref,"select":"round(cluster_cpu_realcapacity*100/cluster_cpu_total) as cpur,round(cluster_mem_realcapacity*100/cluster_mem_total) as memr"},function(data){
		$('#graph-consommation').highcharts().series[1].setData(data.result);
	});
	
	// Initialize Ressource Pool Slider
	rpstable = $('.table-rp').dataTable({
		jQueryUI:true,searching:false,scrollCollapse: true,paging: false,info:false,"order": [],"serverSide": true,stateSave: true,
		"columns":[{"data":"name"},{"data":"cpushare"},{"data":"memshare"}],
		"ajax": function ( request, drawCallback, settings ) {
				getjson("WS_ClusterDetail.compute_rp",{"moref":moref},function(data){
					drawCallback(data.result)
				});
		}
	});
	getjson("WS_ClusterDetail.get_pool",{"moref":moref},loadslider_cluster);

	// Graph Historic Consommation 
        $('#graph-consommation-hist').highcharts(Highcharts.merge(graphOptions,{
            chart: {type: 'spline',zoomType: 'x',marginLeft:150,marginRight:150}, title: {text: 'Consommation moyenne des vms par jour'}, subtitle: {text: 'un point de mesure par jour'},
            xAxis: {type:'datetime',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false},labels:{rotation: -45}},
            yAxis: [
		{labels: {format: '{value} Mhz',style: {color: Highcharts.getOptions().colors[0]}},title: {text: 'CPU Usage (Mhz)', style: {color: Highcharts.getOptions().colors[0]}}, min: 0},
		{labels: {format: '{value} Mo',style: {color: Highcharts.getOptions().colors[1]}}, title: {text: 'Mem Usage (Mo)', style: {color: Highcharts.getOptions().colors[1]}}, min: 0,opposite: true}
		],
            tooltip: {shared:true},
	   series: [{name: 'CPU',data:[0]}, {name: 'RAM',data:[0],yAxis: 1}]
        }));
	getjson("WS_Stats.cluster_hist",{"moref":moref,"select":"unix_timestamp(cluster_date)*1000,cluster_vmcpu_average"},function(data){
		$('#graph-consommation-hist').highcharts().series[0].setData(data.result);
	});
	getjson("WS_Stats.cluster_hist",{"moref":moref,"select":"unix_timestamp(cluster_date)*1000,cluster_vmmem_average"},function(data){
		$('#graph-consommation-hist').highcharts().series[1].setData(data.result);
	});
		
	// Graph Historic VM Numbers
        $('#graph-nombrevm-hist').highcharts(Highcharts.merge(graphOptions,{
            chart: {type: 'area',zoomType: 'x',marginLeft:100,marginRight:50},title: {text: 'Nombre de machines virtuelles'},subtitle: {text: 'un point de mesure par jour'},
	    legend: {layout: 'vertical',align: 'left',verticalAlign: 'top',x: 100,y: 50,floating: true,borderWidth: 1,backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'},
            xAxis: {type:'datetime',zoomType: 'x',title: {text: 'Date'}, tickmarkPlacement: 'on', title: {enabled: false}},
            yAxis: {title: {text: 'Unit'}},
            tooltip: {shared: true,valueSuffix: ' Unit'},
            plotOptions: {area: {stacking: 'normal',lineColor: '#666666',lineWidth: 1,marker: {lineWidth: 1,lineColor: '#666666'}}},
            series: [{name: 'VM Restante',data:[0]}, {name: 'VM Totale',data:[0]}]
        }));
	getjson("WS_Stats.cluster_hist",{"moref":moref,"select":"unix_timestamp(cluster_date)*1000,cluster_vmmem_left"},function(data){
		$('#graph-nombrevm-hist').highcharts().series[0].setData(data.result)
	});
	getjson("WS_Stats.cluster_hist",{"moref":moref,"select":"unix_timestamp(cluster_date)*1000,cluster_vms_total"},function(data){
		$('#graph-nombrevm-hist').highcharts().series[1].setData(data.result)
	});	
}
