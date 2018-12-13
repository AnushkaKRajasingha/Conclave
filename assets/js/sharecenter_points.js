(function($){
	function loadhistory(){
		$.ajax({
	        type: "POST",
	        url: CI.base_url+'sharecenter/getpinthistory',
	        data:{csrf_token_name: CI.csrf_cookie_name},
	        // contentType: "application/json",
	        dataType: "json",
	        success: function (data) {
	        	 var $aoColumns = [ 
	 	                          { "sTitle": "Reason","sName":"reason" ,"mData":"reason" ,"sClass" :"col-md-1"},
	 	                         { "sTitle": "By","sName":"first_name" ,"mData":"first_name" ,"sClass" :"col-md-2"},
	 	                          { "sTitle": "Amount","sName":"amount" ,"mData":"amount" ,"sClass" :"col-md-2"},
	 	                        
	 	                          { "sTitle": "Earn Date","sName":"earn_date" ,"mData":"earn_date" ,"sClass" :"hidden-xs col-md-1 date", render : function(data, type, row){return data.substring(0,10);}},
	 	                      	 	                        { "sTitle": "Is Cancelled","sName":"iscancelled" ,"mData":"iscancelled" ,"sClass" :"col-md-1",render : function(data, type, row){return data == '1' ? 'Yes' : 'No';} },
	 	                     
	 	                          
	 	                          ];
	 	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	 	    	//  $('td.action',nRow).empty().append(_getActionButtons_v1(aData.id,CI.base_url+'posts/','copypost','deletepost','table-list-posts',loadposts));
	 	    	   $('td.status',nRow).empty().append(_statusLable(aData.post_status));
	 	       	};
	 	        _setupDataTable($('#table-list-points'),$aoColumns,data,$fnRowCallback,[ [ 3, "desc" ] ],function(){});
	        }
	    });
	}
	
$(document).ready(function(){
	
	loadhistory();
	
});
})(jQuery);