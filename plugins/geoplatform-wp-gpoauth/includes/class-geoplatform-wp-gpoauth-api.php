<?php
/**
 * This is a list of the API endpoints that need to be exposed by WordPress
 * in order for ng-gpoauth to opporate.
 */
class WPGpoauth {

    /**
     * TODO: Set authtoken in URL
     *
     * We should setup the initalization code to set the users access token
     * in the requesting URL when coming to the page we want them at...
     *
     * This should be done in the setup WP code
     */


    /**
     * Given session information. Fetch the access token stored for the user
     */
    public function getUserAccessToken($cookie){

        // TODO: do magic trick ( cookie -> access token! )

        // Hint: I would start here:
        // https://github.com/daggerhart/openid-connect-generic/blob/dev/includes/openid-connect-generic-client-wrapper.php#L417-L440

        return $accessToken = null;
    }


    /**
     * Checktoken
     *
     * Need to return with the HTTP 'Authorize' header set to current access token.
     * Expected format:
     *      `Authorize`: 'Bearer [full access token]'
     *
     * @path https://[url root]/checktoken
     */
    function setupChecktokenEndpoint(){
       /*
        * All you should need to know for setting up a custom API endpoint:
        * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
        */

        $token = self.getUserAccessToken($cookie);

        // set in auth header

        // return to browser
    }


    //********************* INIT  *********************//
    function __construct(){
        setupChecktokenEndpoint();
    }
}
