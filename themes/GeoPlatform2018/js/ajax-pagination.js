//https://premium.wpmudev.org/blog/load-posts-ajax/?utm_expid=3606929-101._J2UGKNuQ6e7Of8gblmOTA.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
// (function($) {
// 	$(document).on( 'click', '.pager li', function( event ) {
// 		event.preventDefault();
//     $.ajax({
//   		url: ajaxpagination.ajaxurl,
//   		type: 'post',
//   		data: {
//   			action: 'ajax_pagination'
//   		},
//   		success: function( result ) {
//   			alert( result );
//   		}
//   	})
//   })
// })(jQuery);
//OR
//https://code.tutsplus.com/articles/getting-started-with-ajax-wordpress-pagination--wp-23099
jQuery(document).ready(function () {
	var GreetingAll = jQuery("#GreetingAll").val();
	jQuery("#PleasePushMe").click(function () {
		jQuery.ajax({
			type: 'POST',
			url: 'http://localhost/wp-admin/admin-ajax.php',
			data: {
				action: 'MyAjaxFunction',
				GreetingAll: GreetingAll,
			},
			success: function (data, textStatus, XMLHttpRequest) {
				jQuery("#test-div1").html('');
				jQuery("#test-div1").append(data);
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	});
});









//alert( 'Script Is Enqueued' )