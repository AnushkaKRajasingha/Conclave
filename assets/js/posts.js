(function($){
	function loadposts(){
		$.ajax({
	        type: "POST",
	        url: CI.base_url+'posts/getallpostsbyuser',
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
	 	                          { "sTitle": "Action","sName":"id" ,"mData":"id" ,"sClass" :"action col-md-1"},
	 	                          ];
	 	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	 	    	  $('td.action',nRow).empty().append(_getActionButtons_v1(aData.id,CI.base_url+'posts/','copypost','deletepost','table-list-posts',loadposts));
	 	    	   $('td.status',nRow).empty().append(_statusLable(aData.post_status));
	 	       	};
	 	        _setupDataTable($('#table-list-posts'),$aoColumns,data,$fnRowCallback,[ [ 3, "desc" ] ],function(){$('#postCount').html(arguments[0].aoData.length)});
	        }
	    });
	}
	
	var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
	              'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
	              'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
	              'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
	              'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
	              'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
	              'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
	              'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
	              'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
	            ];
	
	function initTaginput(){

		/*var connections = new Bloodhound({
		    datumTokenizer:function(d) {
		        return Bloodhound.tokenizers.whitespace(d.value);
		    },
		    queryTokenizer: Bloodhound.tokenizers.whitespace,
		    remote: {
		        url:CI.base_url+'connections/getallconnections/',
		       cache: false,
		        filter: function (_connections) {
		        //	console.log(_connections);
		            // Map the remote source JSON array to a JavaScript object array
		        	return $.map(_connections, function (_connection) {
		        		console.log(_connection);
		                return {
		                	data : _connection.user_id,
		                	value:_connection.first_name
		                };
		            });
		        }
		    }
		});*/
		
	/*	var connections = new Bloodhound({
		    datumTokenizer:function(d) {
		        return Bloodhound.tokenizers.whitespace(d.value);
		    },
		    queryTokenizer: Bloodhound.tokenizers.whitespace,
		    remote: {
		       url:'https://twitter.github.io/typeahead.js/data/countries.json',
		       cache: false,
		       filter: function (_connections) {
		        //	console.log(_connections);
		            // Map the remote source JSON array to a JavaScript object array
		        	return $.map(_connections, function (_connection) {
		        		console.log(_connection);
		                return {		                	
		                	value:_connection
		                };
		            });
		        }
		    }
		});*/
		
		var Tempuser = null;; 
		
		var connections = new Bloodhound({
			datumTokenizer:function(d) {
		        return Bloodhound.tokenizers.whitespace([d.value,d.data]);
		    },
		    queryTokenizer: Bloodhound.tokenizers.whitespace,
			//  prefetch : 'https://twitter.github.io/typeahead.js/data/countries.json',
			  remote: {
			       url:CI.base_url+'connections/getallconnections/%query',
			       wildcard:'%query',
			       cache: true,
			       filter: function (_connections) {			    	  
				        //	console.log(_connections);
				            // Map the remote source JSON array to a JavaScript object array
				        	return $.map(_connections, function (_connection) {
				        		localStorage.setItem('tempuser'+_connection.user_id,JSON.stringify(_connection));
				                return {		                	
				                	value:_connection.first_name,
				                	data : _connection.user_id
				                };
				            });
				        }
			  }
		});
		connections.initialize();
		
		function autocompleteDevices(q, sync, async) {
		    if (q === '') {
		        async(connections.all());
		    } else {
		    	connections.search(q, async);
		    }
		}
		
		/*$('.tags').typeahead({
			  hint: true,
			  highlight: true,
			  minLength: 1
			},
			{
				name: 'connections',
				displayKey: 'value',
			    valueKey: 'value',
			    source:connections.ttAdapter(),
			    templates: {
	                suggestion: function(data) {
	                    return '<div><span class="name">'+data.value+'</span></div>';
	                }, 
	                empty: function(){ return '<div><span class="name">No connection found</span></div>';}
	            }				  
			}
			);*/
		$('.tags').tagsinput({
			maxTags : 3,	
			itemValue: function(item) {
			    return item;
			  },
			  itemText: function(item) {
				  var  obj = JSON.parse(localStorage.getItem('tempuser'+item));
				  return obj.first_name;
				  },
				  itemTitle:function(item) {
					    return item;
				  },
				  
			typeaheadjs:[
			null,
			{
				name: 'connections',
				minlength: 1,
			    source:connections.ttAdapter(),
			    displayKey: 'value',
			    valueKey: 'data',
			    hint: false,
			    highlight: true,
			    templates: {
	                suggestion: function(data) {
	                	if(typeof(data) == 'object')
	                		{ return '<div><span class="name">'+data.value+'</span></div>';  }
	                	else
	                		return '<div><span class="name">'+data+'</span></div>';
	                }, 
	                empty: function(){ return '<div><span class="name">No connection found</span></div>';}
	            }	
			}
		]
		}		
		);
		
		$('.tags').on('itemRemoved',function(event){
			$('input[type=hidden][value='+event.item+']').remove();
		});
		$('.tags').on('itemAdded',function(event){
			 var  obj = JSON.parse(localStorage.getItem('tempuser'+event.item));
			 if(obj.connected == 0){
				 bootbox.confirm({
					 message: "This user not connected with you ,Do you want to connect? If 'Yes' that action will cost you "+CCSET.pointtocon+" point(s)" ,
					 buttons: {
					        confirm: {
					            label: 'Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: 'No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					       if(result){
					    	   if(CCSET.pointtocon > CCSET.avail_points){
					    		   bootbox.alert("You don't have enough points to make new connection(s)"); 
					    		   $('.tags').tagsinput('remove',event.item);
					    	   }
					    	   else{
					    	   //console.log('Read');
					    		   CCSET.avail_points =   CCSET.avail_points - CCSET.pointtocon;
					    		   $('span#avail_points').html(CCSET.avail_points);
					    	   $('#share_user_cntr').append($('<input/>').attr({'type':'hidden','name':'shareusers[]','value':event.item}));
						    	   $.post(CI.base_url+'connectfriend/'+event.item, {csrf_token_name: CI.csrf_cookie_name}, function(response) {
										console.log('connect request sent');
									});
					    	   }
					       }
					       else{
					    	   $('.tags').tagsinput('remove',event.item);
					       }
					    }
				});
			 }
			
		});
		
		console.log('end');
		
	}
	
$(document).ready(function(){
	$("form#add_post_form").bind("keypress", function (e) {
	    if (e.keyCode == 13) {
	        return false;
	    }
	});
	
	loadposts();
	 CKEDITOR.replace( 'post_content' );
	 initTaginput();
	
});
})(jQuery);