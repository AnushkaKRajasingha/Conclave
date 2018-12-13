(function($){

	
	
	function disconnectlink(aData,tableid,reload_callbackfunc){
		var _span = $('<span/>').addClass('tools text-center');
		
		if(aData.request == 0 && aData.constatus == 'pending' ){
			var _accept = $('<a/>').attr({
				'title' : 'Accept',
				'href' : 'javascript:;',
					'data-uniqueid': aData.id,
					'id' : aData.id
			}).addClass('fa fa-check-square-o');
			
			_accept.click(function() {
				bootbox.confirm("Do you want to accept the connect request from "+aData.first_name+" ?", function(result) {
					if (result) {					
						$.post(CI.base_url+'acceptfriend/'+aData.id, {csrf_token_name: CI.csrf_cookie_name}, function(response) {
							reloadTable(tableid,reload_callbackfunc);
							reloadTable('table-list-availablecon',loadavailableconnections);
						});
					}
				});
			});
			
			
			var _reject = $('<a/>').attr({
				'title' : 'Reject',
				'href' : 'javascript:;',
					'data-uniqueid': aData.id,
					'id' : aData.id
			}).addClass('fa fa-window-close-o');
			
			_reject.click(function() {
				bootbox.confirm("Do you want to reject the connect request from "+aData.first_name+" ?", function(result) {
					if (result) {					
						$.post(CI.base_url+'disconnectfriend/'+aData.id, {csrf_token_name: CI.csrf_cookie_name}, function(response) {
							reloadTable(tableid,reload_callbackfunc);
							reloadTable('table-list-availablecon',loadavailableconnections);
						});
					}
				});
			});
			
			_span.append(_accept);
			_span.append(_reject);
		}
		else{
			var _msgtext = aData.constatus == 'pending' ? "Do you want to withdraw request from "+aData.first_name+"?" : "Do you want to disconnect from "+aData.first_name+"?";
				var _disconnect = $('<a/>').attr({
					'title' : aData.constatus == 'pending' ? 'Withdraw' : 'Disconnect',
					'href' : 'javascript:;',
						'data-uniqueid': aData.user_id,
						'id' : aData.user_id
				}).addClass('fa fa-unlink');
				
				_disconnect.click(function() {
					bootbox.confirm(_msgtext, function(result) {
						if (result) {					
							$.post(CI.base_url+'disconnectfriend/'+aData.id, {csrf_token_name: CI.csrf_cookie_name}, function(response) {
								reloadTable(tableid,reload_callbackfunc);
								reloadTable('table-list-availablecon',loadavailableconnections);
							});
						}
					});
				});
				_span.append(_disconnect);
		}
		return _span;
	}
	
	function connectlink(aData,tableid,reload_callbackfunc){
		var _span = $('<span/>').addClass('tools text-center');
		var _connect = $('<a/>').attr({
			'title' : 'Connect',
			'href' : 'javascript:;',
				'data-uniqueid': aData.user_id,
				'id' : aData.user_id
		}).addClass('fa fa-link');
		
		_connect.click(function() {
			bootbox.confirm("Do you want to connect to "+aData.first_name+"?", function(result) {
				if (result) {					
					$.post(CI.base_url+'connectfriend/'+aData.user_id, {csrf_token_name: CI.csrf_cookie_name}, function(response) {
						reloadTable(tableid,reload_callbackfunc);
						reloadTable('table-list-mycon',loadconnections);
					});
				}
			});
		});
		
		_span.append(_connect);
		return _span;
	}
	
	
	
	function loadconnections(){
	$.ajax({
        type: "POST",
        url: CI.base_url+'connections/getmyconnections',
        data:{csrf_token_name: CI.csrf_cookie_name},
        // contentType: "application/json",
        dataType: "json",
        success: function (data) {
        	 var $aoColumns = [ 
        	                   	{ "sTitle": "Image","sName":"profile_img" ,"mData":"profile_img" ,"sClass" :"col-md-1 profile_img"},
        	                   { "sTitle": "Name","sName":"first_name" ,"mData":"first_name" ,"sClass" :"col-md-1"},
  	                          { "sTitle": "Email","sName":"email" ,"mData":"email" ,"sClass" :"col-md-2"},
  	                          { "sTitle": "No of Posts","sName":"noofposts" ,"mData":"noofposts" ,"sClass" :"col-md-1"},
  	                          { "sTitle": "Reg Date","sName":"date_registered" ,"mData":"date_registered" ,"sClass" :"hidden-xs col-md-1 date", render : function(data, type, row){return data.substring(0,10);} }, 
  	                          { "sTitle": "Status","sName":"constatus" ,"mData":"constatus" ,"sClass" :"col-md-2 status"},
  	                        { "sTitle": "Action","sName":"user_id" ,"mData":"user_id" ,"sClass" :"col-md-1 action"},
 	                          ];
 	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
 	    	  $('td.action',nRow).empty().append(disconnectlink(aData,'table-list-mycon',loadconnections));
 	    	 $('td.status',nRow).empty().append(_statusLable(aData.constatus));
 	    	 $imgsrc = aData.profile_img == 'members_generic.png' ? CI.base_url+'assets/img/members/'+aData.profile_img : CI.base_url+'assets/img/members/'+aData.username+'/'+aData.profile_img;
 	    	$('td.profile_img',nRow).empty().append('<img class="col-md-12" src="'+$imgsrc+'" alt="'+aData.first_name+'" />');
 	       	};
 	        _setupDataTable($('#table-list-mycon'),$aoColumns,data,$fnRowCallback,[ [ 1, "desc" ] ],function(){$('#myconnects').html(arguments[0].aoData.length)});
        }
    });
	}
	
	function loadavailableconnections(){
		$.ajax({
	        type: "POST",
	        url: CI.base_url+'connections/getavailableconnections',
	        data:{csrf_token_name: CI.csrf_cookie_name},
	        // contentType: "application/json",
	        dataType: "json",
	        success: function (data) {
	        	 var $aoColumns = [ 
	        	                   	{ "sTitle": "Image","sName":"profile_img" ,"mData":"profile_img" ,"sClass" :"col-md-1 profile_img"},
	 	                          { "sTitle": "Name","sName":"first_name" ,"mData":"first_name" ,"sClass" :"col-md-2"},
	 	                          { "sTitle": "Email","sName":"email" ,"mData":"email" ,"sClass" :"col-md-2"},
	 	                          { "sTitle": "No of Posts","sName":"noofposts" ,"mData":"noofposts" ,"sClass" :"col-md-1"},
	 	                          { "sTitle": "Reg Date","sName":"date_registered" ,"mData":"date_registered" ,"sClass" :"hidden-xs col-md-1 date", render : function(data, type, row){return data.substring(0,10);} }, 	
	 	                         { "sTitle": "Action","sName":"user_id" ,"mData":"user_id" ,"sClass" :"col-md-1 action"},
	 	                          ];
	 	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	 	    	  $('td.action',nRow).empty().append(connectlink(aData,'table-list-availablecon',loadavailableconnections));
	 	    	 $imgsrc = aData.profile_img == 'members_generic.png' ? CI.base_url+'assets/img/members/'+aData.profile_img : CI.base_url+'assets/img/members/'+aData.username+'/'+aData.profile_img;
	  	    	$('td.profile_img',nRow).empty().append('<img class="col-md-12" src="'+$imgsrc+'" alt="'+aData.first_name+'" />');
	 	       	};
	 	        _setupDataTable($('#table-list-availablecon'),$aoColumns,data,$fnRowCallback,[ [ 1, "desc" ] ],function(){$('#available_connects').html(arguments[0].aoData.length)});
	        }
	    });
		}
$(document).ready(function(){		
	
	
	loadconnections();
	loadavailableconnections();

	
});
})(jQuery);