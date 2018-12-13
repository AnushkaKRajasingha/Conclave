(function($){
	function init(){
		$('.post-item').click(function(){
			$userid = $(this).data('id');
			$sharedfrom = $(this).data('sharedfrom');
			$('#post_share_button').hide();
			$('#loading-img').show();
			$.ajax({
		        type: "POST",
		        url: CI.base_url+'posts/getsinglepost/'+$(this).data('id'),
		        data:{csrf_token_name: CI.csrf_cookie_name},
		        // contentType: "application/json",
		        dataType: "json",
		        success: function (data) {
		        	$('#post-data #post-title').html(data.post_title);
		        	$('#post-data #post-meta').html('Create by '+data.first_name+' on '+data.post_date);
		        	$('#post-data #post-content').html(data.post_content);
		        	$('#post-data #post-shareby').val($userid);
		        	$('#post-data #post-sharedfrom').val($sharedfrom);
		        	list_contoshare(data.id);
		        	
		        	$('#loading-img').hide();
		        }
		    });
		});
	}
	
	function list_contoshare($postid){
		$('ul#con-check-list').empty();
		$('#loading-img-con').show();
		$.ajax({
	        type: "POST",
	        url: CI.base_url+'sharecenter/conectstopostshare/'+$postid,
	        data:{csrf_token_name: CI.csrf_cookie_name},
	        // contentType: "application/json",
	        dataType: "json",
	        success: function (data) {	        	
	        	$.each(data,function(){
	        		var $imgsrc = $(this)[0].profile_img == 'members_generic.png' ? CI.base_url+'assets/img/members/'+$(this)[0].profile_img : CI.base_url+'assets/img/members/'+$(this)[0].username+'/'+$(this)[0].profile_img;
	        		$('ul#con-check-list').append(
	        				$('<li/>').html(
	        						'<div class="item-con">'+
	        						'<label class="item-con-meta" >'+
	        						'<img src="'+$imgsrc+'" alt="'+$(this)[0].first_name+'" titel="'+$(this)[0].first_name+'" class="item-con-img"/>'+	        						
	        						$(this)[0].first_name +
	        						'<input type="checkbox" name="contoshare" value="'+$(this)[0].user_id+'"  data-postid="'+$postid+'" data-shareby="'+$('#post-data #post-shareby').val()+'" data-sharedfrom="'+$('#post-data #post-sharedfrom').val()+'" />'+
	        						'</label>'+
	        						'</div>'
	        						).addClass("col-md-4 item-con-li")
	        				);
	        	});
	        	$('#loading-img-con').hide();
	        	if(data.length > 0)
	        	$('#post_share_button').show();
	        }
	    });
	}
	
	function share_posts(){
		$.each($('input[name=contoshare]:checked'),function(){
			$_currUser = $(this); $_currUser.closest("li").remove();
			$.ajax({
		        type: "POST",
		        url: CI.base_url+'sharecenter/sharepost/'+$(this).data('postid')+'/'+$(this).data('shareby')+'/'+$(this).val()+'/'+$(this).data('sharedfrom'),
		        data:{csrf_token_name: CI.csrf_cookie_name},
		        // contentType: "application/json",
		        dataType: "json",
		        success: function (data) {	
		        //	$_currUser.remove();
		        	$_currUser.closest("li").remove();
		        	
		        }
		    });		
			
		});
	} 
	
	$(document).ready(function(){
		init();
		$('#post_share_button').click(function(){
			share_posts();
		});
	});	
})(jQuery);