
var currentUser = null;

/**
 *
 */
function login() {

    // var target = window.location.href;
    // var handler = window.location.protocol + "//" + window.location.host + '/login';
    // var current = handler + "?redirectTo=" + encodeURIComponent(target);

    var current = window.location.href;
    window.location = 'https://sp.geoplatform.gov/module.php/core/as_login.php?AuthId=geosaml&ReturnTo=' + encodeURIComponent(current);
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



