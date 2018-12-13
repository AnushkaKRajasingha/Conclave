(function($){
	function loadhistory(){
		$.ajax({
	        type: "POST",
	        url: CI.base_url+'sharecenter/getsharehistory',
	        data:{csrf_token_name: CI.csrf_cookie_name},
	        // contentType: "application/json",
	        dataType: "json",
	        success: function (data) {
	        	 var $aoColumns = [ 
	 	                          { "sTitle": "Post Title","sName":"post_title" ,"mData":"post_title" ,"sClass" :"col-md-1"},
	 	                          { "sTitle": "Post Content","sName":"post_content" ,"mData":"post_content" ,"sClass" :"col-md-2"},
	 	                          { "sTitle": "Author","sName":"first_name" ,"mData":"first_name" ,"sClass" :"col-md-2"},
	 	                          { "sTitle": "Create Date","sName":"post_date" ,"mData":"post_date" ,"sClass" :"hidden-xs col-md-1 date", render : function(data, type, row){return data.substring(0,10);}},
	 	                          { "sTitle": "Status","sName":"post_status" ,"mData":"post_status" ,"sClass" :"status col-md-1"},
	 	                        { "sTitle": "Shared with","sName":"sharedto_uname" ,"mData":"sharedto_uname" ,"sClass" :"col-md-2"},
	 	                        { "sTitle": "Is Read","sName":"isread" ,"mData":"isread" ,"sClass" :"col-md-1",render : function(data, type, row){return data == '1' ? 'Yes' : 'No';} },
	 	                        { "sTitle": "Is Re-shared","sName":"isreshared" ,"mData":"isreshared" ,"sClass" :"col-md-1" , render : function(data, type, row){return data == '1' ? 'Yes' : 'No';} },
	 	                          
	 	                          ];
	 	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	 	    	//  $('td.action',nRow).empty().append(_getActionButtons_v1(aData.id,CI.base_url+'posts/','copypost','deletepost','table-list-posts',loadposts));
	 	    	   $('td.status',nRow).empty().append(_statusLable(aData.post_status));
	 	       	};
	 	        _setupDataTable($('#table-list-history'),$aoColumns,data,$fnRowCallback,[ [ 3, "desc" ] ],function(){});
	        }
	    });
	}
	
$(document).ready(function(){
	
	loadhistory();
	
});
})(jQuery);