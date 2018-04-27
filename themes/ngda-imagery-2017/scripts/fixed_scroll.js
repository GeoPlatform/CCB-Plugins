
/*
 * Because position:fixed elements created problems with scroll-to-anchor, 
 * the following JS attempts to find the target of the scroll-to and 
 * scroll based on the element's position and the offset needed to compensate
 * for the fixed element(s)
 
 DISABLING THIS:
    1) there's an offset issue if the page is not loaded from scratch (ie: jumping to anchor on same page)
    2) removing fixed from header and megamenu to remove the need for this scroll fix.

function scroll_if_anchor(href) {
    if(!href) return;
    
    href = (typeof(href) == 'string') ? href : $(this).attr('href');

    // if no href to target, ignore
    if(!href) return;

    var fromTop = $('header').height() + 24;

    // if href points to anchor on this page
    // Legacy jQuery and IE7 may have issues: http://stackoverflow.com/q/1593174
    var $target = $(href);

    if($target.length) {
        $('html,body').animate({scrollTop: $target.offset().top - fromTop});
    }
}

// When our page loads, check to see if it contains and anchor
scroll_if_anchor(window.location.hash);

*/