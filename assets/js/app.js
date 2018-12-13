// waitForFinalEvent: set individual timers based on a randomly generated ID
// -------------------------------------------------------------------------------------------------------------
var waitForFinalEvent = (function() {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) {
			uniqueId = "Don't call this twice without a uniqueId";
		}
		if (timers[uniqueId]) {
			clearTimeout (timers[uniqueId]);
		}
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

!function ($) {

	$(function() {

		'use strict';

		// scoped vars
		var $app                 = $(".wrap");                    // outter div
		var $navWrap             = $("#demo1");                   // sidemenu wrapper
		var $narrowTrigger       = $("#js-narrow-menu");          // button that makes the menu narrow or regular size
		var $hiddenTrigger       = $("#js-showhide-menu");        // button which hides or shows the sidemenu
		var $narrowNotIcon       = "fa-bars";             // icon to display when sidemenu is not narrow
		var $narrowIcon          = "fa-ellipsis-v";            // icon to display when sidemenu is narrow
		var $hiddenNotIcon       = "fa-eye";                      // icon to display when sidemenu is not hidden
		var $hiddenIcon          = "fa-eye-slash";                // icon to display when sidemenu is hidden
		var $breakPoint          = 768;                           // breakpoint for sidemenu to show or hide on window.resize
		var $extraMenuTrigger    = $("#js-extramenu");            // button for the extra menu that pops over content on the right (or left on rtl design)
		var $extraMenu           = $("#extramenu");               // the extra menu container that pops over content on the side

		var $infoContent         = $("#info-content");
		var $infoContentSelector = $('.js-info-tab-selector');


		// --> LocalStorage checks
		// -------------------------------------------------------------------------------------------------------------
		// check localStorage for aside
		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			if (localStorage.getItem("asideNarrow") == "yep") {
				$app.addClass("aside-narrow");
				$narrowTrigger.find("i").removeClass($narrowNotIcon).addClass($narrowIcon);
			}

			if (localStorage.getItem("asideHidden") == "yep") {
				$app.addClass("aside-hidden");
				$hiddenTrigger.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
				$narrowTrigger.hide();
			}
		}

		// check localStorage for extramenu
		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			if (localStorage.getItem("extraMenu") == "yep") {
				$extraMenu.addClass("shown");
			}
		}


		// narrow menu trigger
		// -------------------------------------------------------------------------------------------------------------
		$narrowTrigger.on("click", function() {

			var $_this = $(this);

			if ($app.hasClass("aside-narrow")) {
				// add aside-narrow class to wrapper
				$app.removeClass("aside-narrow");
				// update localStorage with new asideNarrow value
				localStorage.setItem("asideNarrow", "nope");
				// change the icon on the trigger itself
				$_this.find("i").removeClass($narrowIcon).addClass($narrowNotIcon);
				// turn off all open menus if any
				$navWrap.navgoco('toggle', false);
				// rebuild navigation: makes sure proper events are set
				$navWrap.navgoco('reset');
			}else{
				// remove aside-narrow class from wrapper
				$app.addClass("aside-narrow");
				// update localStorage with new asideNarrow value
				localStorage.setItem("asideNarrow", "yep");
				// change the icon on the trigger itself
				$_this.find("i").removeClass($narrowNotIcon).addClass($narrowIcon);
				// turn off all open menus if any
				$navWrap.navgoco('toggle', false);
				// rebuild navigation: makes sure proper events are set
				$navWrap.navgoco('reset');
			}
		});

		// hidden menu trigger
		// -------------------------------------------------------------------------------------------------------------
		$hiddenTrigger.on("click", function() {

			var $_this = $(this);

			if ($app.hasClass("aside-hidden")) {
				// remove aside-hidden class from wrapper
				$app.removeClass("aside-hidden");
				// switch icon
				$_this.find("i").removeClass($hiddenNotIcon).addClass($hiddenIcon);
				// hide narrow trigger
				$narrowTrigger.show();
				// update localstorage
				localStorage.setItem("asideHidden", "nope");
			}else{
				// add aside-hidden class to wrapper
				$app.addClass("aside-hidden");
				// switch icon
				$_this.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
				// hide narrow trigger
				$narrowTrigger.hide();
				// update localstorage
				localStorage.setItem("asideHidden", "yep");
			}
		});


		// extramenu trigger
		// -------------------------------------------------------------------------------------------------------------
		$extraMenuTrigger.on("click", function() {
			if ($extraMenu.hasClass("shown")) {
				$("#extramenu-content").hide();
				// do not show menu because it's visible and we need to hide it
				$extraMenu.removeClass("shown");
				localStorage.removeItem("extraMenu");
			}else{
				// menu is hidden, show it
				$extraMenu.addClass("shown");
				localStorage.setItem("extraMenu", "yep");
			}

			// Transition-end trigger for boxed layouts, is not triggered with fluid layouts because we don't use the html container in those themes.
			$extraMenu.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
				function(e) {
					if ($extraMenu.hasClass("shown")) {
						$("#extramenu-content").show();
					}
				}
			);
		});

		// show extramenu content when visible
		if ($extraMenu.hasClass('shown')) {
			$("#extramenu-content").show();
		}else{
			$("#extramenu-content").hide();
		}


		// change triggers depending on screen width (used below)
		// -------------------------------------------------------------------------------------------------------------
		function hiddenOnBreakPoint() {
			//console.log($(window).width());
			if ($('body').width() < $breakPoint) {

				// check localStorage and adjust triggers accordingly
				//if (typeof localStorage !== 'undefined' && localStorage !== null) {
				if (localStorage.getItem("asideHidden") == "nope" || typeof localStorage !== 'undefined') {
					// add aside-hidden to wrapper
					$app.addClass('aside-hidden');
					// trigger
					$hiddenTrigger.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
					$narrowTrigger.hide();
					// storage
					localStorage.setItem("asideHidden", "yep");
				}
			}else{

				// check localStorage and adjust triggers accordingly
				if (localStorage.getItem("asideHidden") == "yep") {
					// remove class aside-hidden from wrapper
					$app.removeClass('aside-hidden');
					// trigger
					$hiddenTrigger.find("i").removeClass($hiddenNotIcon).addClass($hiddenIcon);
					$narrowTrigger.show();
					// storage
					localStorage.setItem("asideHidden", "nope");
				}
			}
		}


		// resize verification with hammer protection
		// -------------------------------------------------------------------------------------------------------------
		$(window).on('resize', function() {
			waitForFinalEvent(function() {
				//console.log("resize");
				hiddenOnBreakPoint();
			}, 250, "hiddenOnBreakPoint");
		});


		// launch the navigation menu (custom Navgoco version)
		// -------------------------------------------------------------------------------------------------------------
		if ($navWrap.length > 0) {
			$navWrap.navgoco({
				caretClassCollapsed: 'fa fa-angle-down',
				caretClassExpanded: 'fa fa-angle-up',
				accordion: true,
				openClass: 'open',
				save: true,
				cookie: {
					name: 'sidemenuCiMembership',
					expires: false,
					path: '/'
				},
				slide: {
					duration: 200,
					easing: 'swing'
				}
			});
		}


		// tab menu switch control for sidebar (generic: add/remove menu tabs as you please)
		// -------------------------------------------------------------------------------------------------------------
		$infoContentSelector.on('show.bs.tab', function (e) {
			$infoContentSelector.removeClass('active in'); // remove all active and in classes from them tabs
			$(this).addClass('active in'); // set the current tab active

			localStorage.setItem("js-aside-info-active-tab", $(this).attr('id'));
		});

		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			$infoContentSelector.removeClass('active in');
			$("#" + localStorage.getItem("js-aside-info-active-tab")).addClass('active in');

			$infoContent.find(".tab-pane.active").removeClass('active in');

			$infoContent.find(".tab-pane." + localStorage.getItem("js-aside-info-active-tab")).addClass('active in');
		}


		// select all functionality for manipulating multiple items at one go
		// -------------------------------------------------------------------------------------------------------------
		$(".js-select-all-members").on('change', function() {
			var c = this.checked;
			$(':checkbox').prop('checked',c);
		});
		// verify on each checkbox change whether everything is checked or not and change the select-all button accordingly
		$(".list_members_checkbox").on('change', function() {
			$(".js-select-all-members").prop("checked", $('.list_members_checkbox:checked').length == $('.list_members_checkbox').length);
		});


		// launch the Slimscroll plugin for dropdowns
		// -------------------------------------------------------------------------------------------------------------
		if ($('.scroll').length > 0) {
			$('.scroll').slimscroll(
				{
					distance: '0',
					color: '#b9b9b9',
					railVisible: true,
					railColor: '#ccc',
					size: '5px',
					height: '220px'
				}
			);
		}


		/* ---------- Adminpanel bootbox (alerts when clicking buttons on list members page)
		 ------------------------------------------------------------------------------------------------ */

		var myBootbox = function($target) {
			$($target).click(function() {
				$("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
				$(this).attr("clicked", "true");
			});
		};

		myBootbox("form#mass_action_form input[type=submit]");

		$("form#mass_action_form button").on("click", function(evt) {

			var $target = $(this).attr('name');

			evt.preventDefault();
			bootbox.confirm("Warning: <br>" + $(this).data("title"), function(confirmed) {
				if (confirmed) {
					$("input#mass_action").val($target);
					$("#mass_action_form").submit();
				}
			});
		});


		// Parsley frontend validation
		// -------------------------------------------------------------------------------------------------------------
		var parsleyFactory = function($form, $button) {
			var $parsley = $form.parsley();

			$button.on('click', function(evt) {

				if ($parsley.isValid()) {

					$parsley.destroy();
					$form.submit();

					$(this).button('loading');
				}else{
					$(this).button("reset");
				}
                if (typeof(tinyMCE) != "undefined") {
                    tinyMCE.triggerSave();
                }
			});
		};

		// Parsley generic form

        $(".js-parsley").each(function(i, obj) {
            var $form = $(this);
            if ($form.length) {
                parsleyFactory( $form, $("." + $form.data('parsley-submit')) );
            }
        });


		// profile picture upload
		// -------------------------------------------------------------------------------------------------------------
        var $fileUpload = $('#fileupload');
		if ($fileUpload.length) {
            $fileUpload.fileupload({
                dropZone: $('#dropzone'),
				url: CI.base_url + $fileUpload.attr("data-path"),
				dataType: 'json',
				disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
				maxFileSize: CI.picture_max_upload_size * 1000,
				acceptFileTypes: /^image\/(jpe?g|png)$/i,
				formData:
					{'csrf_token_name': $('body').find('input[name=csrf_token_name]').first().val()},
				submit: function () {
					$('#progress').removeClass('hidden');
					$('#files').text('');
				},
				add: function(e, data) {
					var uploadErrors = [];
					var acceptFileTypes = /^image\/(jpe?g|png)$/i;
					if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
						uploadErrors.push('Not an accepted file type.');
					}
					if(data.originalFiles[0]['size'].toString().length > 0 && data.originalFiles[0]['size'] > CI.picture_max_upload_size * 1000) {
						uploadErrors.push('Filesize is too big.');
					}
					if(uploadErrors.length > 0) {
						$('#files').text(uploadErrors.join("\n"));
					} else {
						data.submit();
						//console.log(data); // if you want to troubleshoot this use your network tab in browser developer tools
					}
				},
				done: function (e, data) {
					$.each(data.result.files, function (index, file) {
						var $output = '';
						if (file.error != 0) {
							$output = file.error;
						}
						var d = new Date();
						//var extension = file.name.substr( (file.name.lastIndexOf('.') +1) );
						$('#progress').addClass('hidden');
						$('#progress .progress-bar').css('width', '0%');
						$('.js_profile_image').attr('src', CI.base_url + 'assets/img/members/'+ $('#profile_username').val() + '/' + data.result.new_name + '?' + d.getTime());
						$('#files').text($output);
					});
					// cookie is removed in controller, could not get it to work with Cookies plugin
					Cookies.set('csrf_cookie_name', $('body').find('input[name=csrf_token_name]').val(), { domain: '' }, { path: '/' });

					var csrf_cookie_val = Cookies.get('csrf_cookie_name');
					$('body').find('input[name=csrf_token_name]').val(csrf_cookie_val);
				},
				progressall: function (e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#progress .progress-bar').css(
						'width',
						progress + '%'
					);
				},
				fail: function (e, data) {
					$('#files').text('File upload is down. Please try again later.');
				}
			}).prop('disabled', !$.support.fileInput)
				.parent().addClass($.support.fileInput ? undefined : 'disabled');
		}

        $fileUpload.on('click', function(){
			$("#files").text('');
		});


		// adminpanel text on search button -> list members page
		// -------------------------------------------------------------------------------------------------------------
		$("#js-search").on('click', function() {
			if ($("#search_wrapper").hasClass("in")) {
				$("#js-search-text").html('<i class="fa fa-expand pd-r-5"></i> ' + CI.expand);
			}else{
				$("#js-search-text").html('<i class="fa fa-compress pd-r-5"></i> ' + CI.collapse);
			}
		});


		// confirm delete
        // -------------------------------------------------------------------------------------------------------------
		$(".js-confirm-delete").on('click', function(){
			if(confirm(CI.confirm_delete)) {
				$(this).button('loading');
				return true;
			}else{
				return false;
			}
		});

        // catch CSRF timeout
        // -------------------------------------------------------------------------------------------------------------
        // value must be same as $config['csrf_expire'] = 7200; in config.php
        // DISABLED FOR NOW: could be annoying or inconvenient to some
        /*setInterval(function () {
            if(alert('Your session has expired - click ok to continue')){
            }else{
                window.location.reload();
            }
        }, 7200000);*/

	});

}(window.jQuery);

$(window).on('load', function(e){

	// let's show our hidden body after everything is done
	var $pageContainer = $('body');
	$pageContainer.css('display', 'none');
	$pageContainer.css('visibility', 'inherit');
	$pageContainer.fadeIn(400);
	$pageContainer.removeClass("notransition");

	// workaround for removing #_-_ from url during Facebook authentication
	if (window.location.hash == '#_=_') {
		window.location.hash = ''; // for older browsers, leaves a # behind
		history.pushState('', document.title, window.location.pathname); // nice and clean
		e.preventDefault(); // no page reload
	}
});