(function( $ ) {
	'use strict';







  jQuery(document).ready(function() {

    var geopoauth_login_bool = ""
    var intervalID = window.setInterval(geopoauth_continuous_cookie_check, 5000);

    function geopoauth_continuous_cookie_check()
    {
			var grab_val = geopoauth_cookie_parse();
			var decoded = "nill";
			var timeone = parseInt(Date.now().toString().substring(0, 10), 10);
			var timetwo = parseInt(Date.now().toString().substring(0, 10), 10);
			if (grab_val){
				decoded = JSON.parse(window.atob(((window.atob(grab_val)).split("."))[1]));
				timeone = parseInt(decoded.exp, 10);
			}


			var data = {
				action: 'geopoauth_ajax_function',
				security: MyAjax.security,
				timeone: timeone,
				timetwo: timetwo,
			}
			jQuery.post(MyAjax.ajaxurl, data, function(response){
				if (response == "die"){
					alert("Your session has expired and you have been logged out.");
					location.reload();
				}
		 	  console.log(response);
			});
    }


    function geopoauth_cookie_parse(){
      var name = "gpoauth-b=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var cookieArray = decodedCookie.split(";");
      for(var i = 0; i <cookieArray.length; i++) {
        var oneCookie = cookieArray[i];
        while (oneCookie.charAt(0) == ' ') {
          oneCookie = oneCookie.substring(1);
        }
        if (oneCookie.indexOf(name) == 0) {
          return oneCookie.substring(name.length, oneCookie.length);
        }
      }
      return "";
    }



  });




})( jQuery );
