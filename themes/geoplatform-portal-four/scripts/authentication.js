
var currentUser = null;

/**
 *
 */
function login() {

    // var target = window.location.href;
    // var handler = window.location.protocol + "//" + window.location.host + '/login';
    // var current = handler + "?redirectTo=" + encodeURIComponent(target);

    var current = window.location.href;
    //window.location = 'https://sp.geoplatform.gov/module.php/core/as_login.php?AuthId=geosaml&ReturnTo=' + encodeURIComponent(current);
    window.location = '/wp-login.php';
}

/**
 *
 */
function logout() {

    //remove cookie
    // document.cookie = 'AUTHENTICATED=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

    // var target = window.location.href;
    // var handler = window.location.protocol + "//" + window.location.host + '/logout';
    // var current = handler + "?redirectTo=" + encodeURIComponent(target);

    var current = window.location.href;
    window.location = 'https://sp.geoplatform.gov/module.php/core/as_logout.php?AuthId=geosaml&ReturnTo=' + encodeURIComponent(current);

}

/**
 * Check if the user is already authenticated via IDM
 * @param callback function to invoke upon successful or failed authentication
 */
function checkAuthenticated(callback) {

    // if(currentUser) {
    //     callback(currentUser);
    //     return;
    // }

    var hostname = window.location.hostname;
    // if(hostname === 'localhost') {
    //    callback({username: "test", name: "John Doe", org: "Acme, Inc.", email: "test@geoplatform.us"});
    //    return;
    // }

    $.ajax({
        url: 'https://sp.geoplatform.gov/authenticategeosaml.php?as=geosaml',
        method: 'GET',
        dataType: 'JSON',
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(response){
            var result = response;
            if(result.Success){

                //get expires header value

                var user = {};
                for(var k in result) {
                    if(typeof(result[k].push) != 'undefined')
                        user[k] = result[k][0];
                    else
                        user[k] = result[k];
                }

                // currentUser = {
                //     username : result.name[0],
                //     name : result.first_name[0] + ' ' + result.last_name[0],
                //     email: result.mail,
                //     org : result.organization[0] //get org value when added
                // };
                // callback(currentUser);
                callback(user);

            }else{
                callback(null);
            }
        },
        error: function(error){
            callback(null);
        }
    });

}

function userDataLoad(user) {

    var url = jQuery('#userInfoSection [data-load-url]').data('load-url');
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var section = jQuery('#userInfoSection');

    var html = [];
    $.getJSON(url)
        .success(function(json) {
            if(json.results.length) {
                for(var i=0; i<json.results.length; ++i) {
                    var map = json.results[i];
                    var url = map.thumbnailUrl;

                    var url = '<%= wmvUrl %>/#?id=' + map._id;
                    if(map.referenceUrl) {
                        url = map.referenceUrl;
                    }
                    var thumb = map.thumbnailUrl;
                    if(!thumb) thumb = '<%= registryUrl %>/api/maps/' + map._id + '/thumbnail?format=image';
                    else if(thumb.indexOf("http")!==0) thumb = '<%= registryUrl %>/' + thumb;

                    var date = new Date(map.updated),
                        hours = date.getHours(),
                        ampm = hours > 12 ? 'pm' : 'am',
                        dateStr = months[date.getMonth()] + ' ' +
                                    date.getDate() + ', ' + date.getFullYear() + ' ' +
                                    (hours > 12 ? hours-12:hours) + ':' + date.getMinutes() + ampm;

                    html.push(
                        '<div class="media">',
                        '  <a href="' + url + '" class="media-left media-middle">',
                        '    <img class="media-object bordered" src="' + thumb + '" alt="' + map.label + '" height="48">',
                        '  </a>',
                        '  <div class="media-body">',
                        '    <div class="media-heading"><a href="' + url + '">' + map.label + '</a></div>',
                        '    <small class="text-muted">' + dateStr + '</small>',
                        '  </div>',
                        '</div>'
                    );
                }

            } else {
                html.push('<em>You have no recent items. You should create some content!</em>');
            }

            section.append(html.join(' '));
        })
        .error(function(xhr, status, message) {
            //do we need to do anything here?
        });
};
