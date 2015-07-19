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
		item = $(this).attr('data-moref');
		url = '/?m='+type+'&moref='+item+'&madate='+encodeURI($('#madate').val());

		if ($('#side-menu').find(this).length ==1) {
			$('#side-menu .selected').removeClass('active').removeClass('selected');
			$(this).parent().addClass('active selected');
		}

		$('#page-wrapper').load(url,function(){
			update_url(url);
			eval('loadchart_'+type+'()');
			init();
		})	
	return false;
	});


	$('.dashboard td a,.ref-cluster a,.hostlist-stats td a,.hostlist-stats .btn,.ref-host a,.vmlist-stats td a,.vmlist-stats .btn').unbind();
	$('.dashboard td a,.ref-cluster a,.hostlist-stats td a,.hostlist-stats .btn,.ref-host a,.vmlist-stats td a,.vmlist-stats .btn').click(function(){
		type = $(this).attr('data-href');
		item = $(this).attr('data-moref');
		url = '/?m='+type+'&moref='+item+'&madate='+encodeURI($('#madate').val());
		$('#page-wrapper').load(url,function(){
			update_url(url);
			select_menu(item);
			eval('loadchart_'+type+'()');
			init();
		})	
	return false;		
	});
	
	$('.dashboardtable tbody,.searchcluster tbody,.searchhost tbody,.searchvm tbody,.hostlisttable tbody,.vmtoplisttable tbody,.vmlisttable tbody').unbind();
	$('.dashboardtable tbody,.searchcluster tbody,.searchhost tbody,.searchvm tbody,.hostlisttable tbody,.vmtoplisttable tbody,.vmlisttable tbody').on('click','tr',function(data){
		type = $(this).data('data-href');
		item = $(this).data('data-moref');
		url = '/?m='+type+'&moref='+item+'&madate='+encodeURI($('#madate').val());
		$('#page-wrapper').load(url,function(){
			update_url(url);
			select_menu(item);
			eval('loadchart_'+type+'()');
			init();
		});
	return false;
	});

	$('#madate').unbind();
	$('#madate').change(function(){
		type = $.urlParam('m')?$.urlParam('m'):'dashboard';
		item = $.urlParam('moref');
		url = '/?m='+type+'&moref='+item+'&madate='+encodeURI($('#madate').val());
		$('#page-wrapper').load(url,function(){
			update_url(url);
			select_menu(item);
			eval('loadchart_'+type+'()');
			init();
		});
	return false;
	});
	
	$('#search').unbind();
	$('#search').change(function(){
		loadchart_search();
	return false;	
	});
	
	$('#search-btn').unbind();
	$('#search-btn').click(function(){
		loadchart_search();
	return false;
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
		  url:'#',
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

function loadchart_settings() {
	$('#admin_form').unbind();
	$('#admin_form').submit(function(){
		$form = $(this).toObject();
		getjson("WS_Admin.set",$form,message);
	return false;
	});
}

function loadchart_search() {
	if ($('#search').val().length==0){
		q= $.urlParam('search');
	} else {
		q = $('#search').val();
	}
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
		init();
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

function select_menu(item){
	if (item && $('.sidebar [data-moref='+item+']').length==1) {
	$click = $('.sidebar [data-moref='+item+']');
	$('.sidebar .active').removeClass('active').removeClass('selected');
	$('.sidebar .in').removeClass('in');
	if (type=='host') {
		$click.parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active');
		$click.parent('li').parent('ul').parent('li').parent('ul').collapse('show');
	}
	$click.parent().parent().parent().addClass('active');
	$click.parent().parent('ul').collapse('show');
	$click.parent().addClass('active selected');
	}
}

$(document).ready(function(){
	//Load init	
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

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}

