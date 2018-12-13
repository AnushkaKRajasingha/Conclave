
	function sharepostaction($this){
		bootbox.confirm({
			 message: 'Do you want to read this post or delete it ?',
			 buttons: {
			        confirm: {
			            label: 'Read',
			            className: 'btn-success'
			        },
			        cancel: {
			            label: 'Delete',
			            className: 'btn-danger'
			        }
			    },
			    callback: function (result) {
			       if(result){
			    	   //console.log('Read');
			    	   window.location.href =  CI.base_url+'posts/readsharedpost/'+jQuery($this).data('id');
			       }
			       else{
			    	   (function($){
			    		   $.ajax({
			    		        type: "POST",
			    		        url: CI.base_url+'sharecenter/deletesharepost/'+$($this).data('sharedfrom'),
			    		        data:{csrf_token_name: CI.csrf_cookie_name},
			    		        // contentType: "application/json",
			    		        dataType: "json",
			    		        success: function (data) {
			    		        	$($this).remove();
			    		        }
			    		    });
			    	   })(jQuery);
			       }
			    }
		});
	}
