function loadchart_dashboard() {

	$('#datacenter_vms_total .label, #datacenter_hosts_total .label').hover(function() {
		$(this).css('cursor','pointer');
	});	

	$('#datacenter_vms_total').unbind();
	$('#datacenter_vms_total').click(function(){
		url ='/?m=vms';
		update_url(url);
		$('#page-wrapper').load(url,function(){
			$('.searchvm').dataTable({
				jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: false,deferRender: true,processing: true,serverSide: true,scrollX:true,pagingType: "simple_numbers",
				"ajax": function ( request, drawCallback, settings ) {
					getjson("WS_Search.search_vm",request,function(data){
						drawCallback(data.result);
						init();
					});
				}
			});
			
		});	
	return false;		
	});

	$('#datacenter_hosts_total').unbind();
	$('#datacenter_hosts_total').click(function(){
		url ='/?m=hosts';
		update_url(url);
		$('#page-wrapper').load(url,function(){
			$('.searchhost').dataTable({
				jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: false,deferRender: true,processing: true,serverSide: true,scrollX:true,pagingType: "simple_numbers",
				"ajax": function ( request, drawCallback, settings ) {
					getjson("WS_Search.search_host",request,function(data){
						drawCallback(data.result);
						init();
					});
				}
			});
		});	
	return false;
	});
	// Load Numbers Vms Label
	getjson("WS_DashboardDetail.vms_total",null,function(data){
		$('#datacenter_vms_total .label').html(data.result[0]);
	});
	// Load Numbers Hosts Label
	getjson("WS_DashboardDetail.hosts_total",null,function(data){
		$('#datacenter_hosts_total .label').html(data.result[0]);
	});	
	// Load DataTable Cluster
	$('.dashboardtable').dataTable({
		jQueryUI:true,searching:true,scrollCollapse: true,paging: true,info:false,"order": [],stateSave: true,deferRender: true,processing: true,serverSide: true,scrollX:true,pagingType: "simple_numbers",
		"columns":[{"data":0,name:"clustername"},{"data":1,"searchable":false},{"data":2,"searchable":false},{"data":3,"searchable":false},{"data":4,"searchable":false},{"data":5,"searchable":false},{"data":6,"searchable":false},{"data":7,"searchable":false},{"data":8,"searchable":false}],
		"ajax": function ( request, drawCallback, settings ) {
			getjson("WS_DashboardDetail.get_clusterlistres",request,function(data){
				drawCallback(data.result);
			});
		}
	});	
	// Load HeatMap VM
	getjson("WS_DashboardDetail.datacenter_view",null,function(data){
		var data = data.result,points = [],region_p,region_val,region_i,country_p,country_i,cause_p,cause_i,region,country,cause;
		region_i = 0;
		    for (region in data) {
			if (data.hasOwnProperty(region)) {
			  if (data[region]) {
			    region_val = 0;
//			    region_p = { id: "id_" + region_i, name: region};
			    region_p = { id: "id_" + region_i, name: region, color: Highcharts.getOptions().colors[region_i]};
			    country_i = 0;
			    for (country in data[region]) {
				if (data[region].hasOwnProperty(country)) {
				 if (data[region][country]) {
				    country_p = { id: region_p.id + "_" + country_i, name: country, parent: region_p.id};
				    points.push(country_p);
				    cause_i = 0;
				    for (cause in data[region][country]) {
				        if (data[region][country].hasOwnProperty(cause)) {
					  if (data[region][country][cause]) {
					    if (Math.round(+data[region][country][cause]) < 500) {
						vmcolor='green';
					    } else if (Math.round(+data[region][country][cause]) < 2000) {
						vmcolor='yellow';
					    } else {
						vmcolor='red';
					    }
				            cause_p = { id: country_p.id + "_" + cause_i, name: cause, parent: country_p.id, value: Math.round(+data[region][country][cause]),color:vmcolor};
				            region_val += cause_p.value;
				            points.push(cause_p);
				            cause_i = cause_i + 1;
					   }
				        }
				    }
				    country_i = country_i + 1;
				 }
				}
			    }
			    region_p.value = Math.round(region_val / country_i);
			    points.push(region_p);
			    region_i = region_i + 1;
			  }
			}
		    }

			$('#datacenter-heatmap').highcharts({
			series: [{
			    type: "treemap",
			    layoutAlgorithm: 'squarified',
			    allowDrillToNode: true,
			    dataLabels: {
				enabled: false
			    },
			    levelIsConstant: false,
			    levels: [{
				level: 1,
				dataLabels: {
				    enabled: true
				},
				borderWidth: 1
			    }],
			    data: points
			}],
			subtitle: {
			    text: 'Click on map for zoom.'
			},
			title: {
			    text: 'Usage memory capacity'
			}
			});
	});	
}

