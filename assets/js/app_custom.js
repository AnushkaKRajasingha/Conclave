(function($){
	function SearchTypeahead(){
		
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
		


		$('#navbar-form-input').typeahead(
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
	                		{ return '<div><span class="name"><a href="'+CI.base_url+'membership/profile/publicprofile/'+data.data+'" >'+data.value+'</a></span></div>';  }
	                	else
	                		return '<div><span class="name">'+data+'</span></div>';
	                }, 
	                empty: function(){ return '<div class="tt-suggestion"><span class="name">No connection found</span></div>';}
	            }	
			}
				
		);
		
	}
	
	
$(document).ready(function(){
	SearchTypeahead();
});
})(jQuery);