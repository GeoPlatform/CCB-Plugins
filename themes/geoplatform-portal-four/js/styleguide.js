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
