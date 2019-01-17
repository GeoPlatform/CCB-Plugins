jQuery(document).ready( function() {

    jQuery('[data-html]').each( function(idx, el) {
        let $el = jQuery(el), src = $el.attr('data-html');
        jQuery.ajax({ url: src, dataType: "html" })
        .done( function(html) { $el.html(html) });
    });

    jQuery('[data-outer-html]').each( function(idx, el) {
        let $el = jQuery(el),
        src = $el.attr('data-outer-html'),
        title = $el.attr('data-page-title'),
        communityTitle = $el.attr('data-community-title');
        jQuery.ajax({ url: src, dataType: "html" })
        .done( function(html) {
            var $newEl = jQuery(html);
            $el.replaceWith($newEl);
            if(title) {
                $newEl.find('.a-page__title').text(title);
            }
            if(communityTitle) {
                $newEl.find('.a-brand > span').text(communityTitle);
            }
        });
    });



    //sticky header scroll hook to toggle shrunked look
    setTimeout( function() {
        var stickyHeader = jQuery(".o-header--sticky");
        if(stickyHeader.length) {
            jQuery(document).on("scroll", function() {
                var scroll = jQuery(document).scrollTop();
                if(scroll > 70){
                    stickyHeader.addClass("is-shrunk");
                } else {
        			stickyHeader.removeClass("is-shrunk");
        		}
        	});
        }
    }, 3000);

});

function toggleClass(selector, className) {
    jQuery(selector).toggleClass(className)
}

function showSource(selector) {
    jQuery(selector).toggleClass('is-hidden');
}

function onInputFieldChange(field) {
    var $el = jQuery(field);
    if($el.val()) {
        $el.siblings('.fa-times').removeClass('is-hidden');
    } else {
        $el.siblings('.fa-times').addClass('is-hidden');
    }
}
function onInputFieldClear(btn) {
    var $el = jQuery(btn);
    $el.siblings('input').val('');
    $el.addClass('is-hidden');
}

jQuery(document).ready( function() {
  var wpAdminBar = jQuery('#wpadminbar');
  if(wpAdminBar.length) {   //if admin bar is present...

    var stickyHeader = jQuery('.o-header--sticky');
    var primaryHeader = stickyHeader.find('.o-header__primary');
    var menu = stickyHeader.find('.m-megamenu');

    if(primaryHeader.length) {
      primaryHeader.css({ top: '32px' }); //drop header by height of wp admin bar
      menu.css({ top: '102px' }); //drop megamenu top by same amount
    }

    /* When user scrolls the page while wpadminbar is visible,
     * adjust the megamenu top based upon whether the sticky header
     * is shrunk or not
    */
    jQuery(document).on( 'scroll', function() {
      if(stickyHeader.hasClass('is-shrunk')) {
        if(parseInt(menu.css('top')) !== 82) {
          menu.css({ top: '82px' });
        }
      } else if (parseInt(menu.css('top')) !== 102){
        menu.css({ top: '102px' });
      }
    });
  }
});

function cycleCarouselTo(selector, slideNo) {
    jQuery(selector).carousel( slideNo );
}
