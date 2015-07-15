 var gaugeOptions = {
	chart: { type: 'solidgauge', backgroundColor:'white',borderRadius:5,borderwidth:0,shadow: false},
	pane: { center: ['50%', '85%'],size: '140%',startAngle: -90,endAngle: 90, background: {backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',innerRadius: '60%',outerRadius: '100%',shape: 'arc'}},
	yAxis: {stops: [[0.1, '#55BF3B'], [0.5, '#DDDF0D'],[0.9, '#DF5353']],lineWidth: 0, minorTickInterval: null, tickPixelInterval: 400, tickWidth: 0, title: { y: 70},labels: {y: 0}},
	plotOptions: {solidgauge: {dataLabels: { y: 5, borderWidth: 0,  useHTML: true }}},
	credits: {enabled: false}
	};
	
 var gaugeOptionsSmall = {
	chart: { type: 'solidgauge', backgroundColor:'white',borderRadius:5,borderwidth:0,shadow: false},
	pane: { center: ['50%', '85%'],size: '140%',startAngle: -90,endAngle: 90, background: {backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',innerRadius: '60%',outerRadius: '100%',shape: 'arc'}},
	yAxis: {stops: [[0.1, '#55BF3B'], [0.5, '#DDDF0D'],[0.9, '#DF5353']],lineWidth: 0, minorTickInterval: null, tickPixelInterval: 400, tickWidth: 0, title: { y: 40},labels: {y: 0}},
	plotOptions: {solidgauge: {dataLabels: { y: -20, borderWidth: 0,  useHTML: true }}},
	credits: {enabled: false}
	};	

var graphOptions = {  
	chart: {borderRadius:5,borderwidth:0,shadow: false}, 
	exporting: {enabled: true},
	credits: {enabled: false}
	};

var rpstable;

function init() {
	$('.table-paging').dataTable({jQueryUI:true,searching:false,scrollX: true,scrollCollapse: true,"order": []})
	$('.table-simple').dataTable({jQueryUI:true,searching:false,scrollX: true,scrollCollapse: true,paging: false,info:false,"order": []})
	$('.table-paging,table-simple').on( 'draw.dt',links);
	links();
}

function links(){

	$('#topmenu .item').unbind();
	$('#topmenu .item').click(function(event){

		type = $(this).attr('data-href');
		url = '/?m='+type+'&moref='+$(this).attr('data-moref')+'&madate='+encodeURI($('#madate').val());
		event.preventDefault();
		update_url(url);

		if ($('#side-menu').find(this).length ==1) {
			$('#side-menu .selected').removeClass('active').removeClass('selected');
			$(this).parent().addClass('active selected');
		}

		$('#page-wrapper').load(url,function(){
			if (type=='cluster') {
				loadchart_cluster();}
			if (type=='host') {
				loadchart_host();}
			if (type=='vm') {
				loadchart_vm();}
			if (type=='datastore_usage') {
				loadchart_datastore_usage();}
			if (type=='datastore_hist') {
				loadchart_datastore_hist();}
			if (type=='dashboard') {
				loadchart_dashboard();}	
			if (type=='settings') {
				loadchart_settings();}			
			init();
		});	
	})
	
	$('.dashboard td a,.ref-cluster a,.hostlist-stats td a,.hostlist-stats .btn,.ref-host a,.vmlist-stats td a,.vmlist-stats .btn').unbind();
	$('.dashboard td a,.ref-cluster a,.hostlist-stats td a,.hostlist-stats .btn,.ref-host a,.vmlist-stats td a,.vmlist-stats .btn').click(function(){
		type = $(this).attr('data-href');
		url = '/?m='+type+'&moref='+$(this).attr('data-moref');
		$click = $('.sidebar [data-moref='+$(this).attr('data-moref')+']');
		$('.sidebar .active').removeClass('active').removeClass('selected');
		$('.sidebar .in').removeClass('in');
		if (type=='host') {
		$click.parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active');
		$click.parent('li').parent('ul').parent('li').parent('ul').collapse('show');
		}
		$click.parent().parent().parent().addClass('active');
		$click.parent().parent('ul').collapse('show');
		$click.parent().addClass('active selected');
		update_url(url);
		$('#page-wrapper').load(url,function(){
			eval('loadchart_'+type+'()');
			init();
		});	
	return false;		
	});
	
	$('.dashboardtable tbody,.searchcluster tbody,.searchhost tbody,.searchvm tbody').unbind();
	$('.dashboardtable tbody,.searchcluster tbody,.searchhost tbody,.searchvm tbody').on('click','tr',function(data){
		type = $(this).data('data-href');
		url = '/?m='+type+'&moref='+$(this).data('data-moref');
		$click = $('.sidebar [data-moref='+$(this).data('data-moref')+']');
		$('.sidebar .active').removeClass('active').removeClass('selected');
		$('.sidebar .in').removeClass('in');
		if (type=='host') {
		$click.parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active');
		$click.parent('li').parent('ul').parent('li').parent('ul').collapse('show');
		}
		$click.parent().parent().parent().addClass('active');
		$click.parent().parent('ul').collapse('show');
		$click.parent().addClass('active selected');
		update_url(url);
		$('#page-wrapper').load(url,function(){
			eval('loadchart_'+type+'()');
			init();
		});
	return false;
	});

	$('#madate').unbind();
	$('#madate').change(function(){
//		 location.reload(); 
		$('.selected .item').trigger('click');
	return false;
	});
	
	$('#search').unbind();
	$('#search').change(function(){
		$('#search-btn').trigger('click');	
	});
	
	$('#search-btn').unbind();
	$('#search-btn').click(function(){
		q = $('#search').val();
		url ='/?m=search&search='+q;
		update_url(url);

		$('#page-wrapper').load(url,function(){
		
			$('.searchcluster').dataTable({
				jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: false,deferRender: true,scrollX:true,pagingType: "simple_numbers",
				"columns":[{data:0,searchable:true}],
				"ajax": function ( request, drawCallback, settings ) {
					query= {"draw":1,"columns":[{"data":0,"name":"clustername","searchable":true,"orderable":true,"search":{"value":q,"regex":false}}]};
					getjson("WS_Search.search_cluster",query,function(data){
						 drawCallback(data.result);
					});
				}
			});

			$('.searchhost').dataTable({
				jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: false,deferRender: true,scrollX:true,pagingType: "simple_numbers",
				"columns":[{data:0,searchable:true},{"data":1,"searchable":false}],
				"ajax": function ( request, drawCallback, settings ) {
					query= {"draw":1,"columns":[{"data":0,"name":"hostname","searchable":true,"orderable":true,"search":{"value":q,"regex":false}}]};
					getjson("WS_Search.search_host",query,function(data){
						 drawCallback(data.result);
					});
				}
			});

			$('.searchvm').dataTable({
				jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: false,deferRender: true,scrollX:true,pagingType: "simple_numbers",
				"columns":[{data:0,searchable:true},{"data":1,"searchable":false}],
				"ajax": function ( request, drawCallback, settings ) {
					query= {"draw":1,"columns":[{"data":0,"name":"vmname","searchable":true,"orderable":true,"search":{"value":q,"regex":false}}]};
					getjson("WS_Search.search_vm",query,function(data){
						 drawCallback(data.result);
					});
				}
			});
			//$('#search').val(null);
			init();
			
		});	
	});	
}

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

function getjson(method,params,callback){
	 	defaultOptions = {
	 		"ignoreErrors" : [],
	 		"username" : "",
	 		"password" : ""
	 	}
		o = $.extend({},defaultOptions);
	 	err = function (code,msg,fullmsg){
		if ($.inArray(code,this.o.ignoreErrors) < 0){
				alert(code + "::" + msg + "::" + fullmsg);
				console.log(code + "::" + msg + "::" + fullmsg);
			}
	 	}
 		request = {};
 		request.jsonrpc = "2.0";
 		request.method = method;
	 	request.params = [params];
		currId = 0;
 		if (typeof(callback) != "undefined"){
 			currId += 1;
 			request.id = currId;
 		}
 		$.ajax({
		  url:'',
		  type:"POST",
		  data:JSON.stringify(request),
		  contentType:"application/json",
		  dataType:"json",
		  error: function(jqXHR,textStatus){
		  	//Don't throw an error if we don't expect any results
		  	if (typeof(callback) != "undefined"){
		  		message('error:' + textStatus);
		  		return false;
		  	}
		  },
		  success: function(r,textStatus,XMLHttpRequest){
		  	var sessionId = XMLHttpRequest.getResponseHeader("x-RPC-Auth-Session");
		  	if (typeof(sessionId) == "string"){
		  		o['sessionId'] = sessionId;
		  	}
 			if (r.error != null){
 				err(r.error.code,r.error.message,r.error.data.fullMessage)
 				return false;
 			} else if (typeof r.id != "undefined"){
 				if (r.id == request.id){
 					callback(r);
 				} else {
 					err("jsonrpc2Error","NO_ID_MATCH","Given Id and recieved Id does not match");
 					return false;
 				}
 			} else {
 				return true;
 			}
 		 }
		});

 	}

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


function loadchart_settings() {
	$('#admin_form').unbind();
	$('#admin_form').submit(function(){
		$form = $(this).toObject();
		getjson("WS_Admin.set",$form,message);
	return false;
	});
}

function update_url(url,name){
	if(typeof history.pushState == 'function') { 
		var stateObj = {};
		history.pushState(stateObj, "KapaSeeTy - " + name, url+"&my");
	}
}

function message(txt){
	if (txt) {
		$('#filinfo').html(txt).show();
		setInterval(function(){$('#filinfo').fadeOut()}, 3000);
	}
}

$(document).ready(function(){
	//Load init
	
//	$('#side-menu [data-href="dashboard"]').parent('li').addClass('active');
//	$('#side-menu [data-href="dashboard"]').parent('li').addClass('selected');	
	$('#side-menu').metisMenu();
	init();
});

$(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height-1) + "px");
        }
})

