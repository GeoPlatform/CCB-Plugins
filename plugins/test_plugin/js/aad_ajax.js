jQuery(document).ready(function($) {
  jQuery("#aad-form").submit(function() {
    var data = {
      action: "aad_get_results"
    };


    jQuery.post(ajaxurl, data, function(response){
      alert(response);
    });


    return false;
  });

});
