<?php
/**
 * THIS FILE IS DEPRECATED AND NOT USED BY THE PLUGIN.
 * IT IS PRESENT ONLY FOR DOCUMENTATION.
 */

/**
 * This is a list of the API endpoints that need to be exposed by WordPress
 * in order for ng-gpoauth to opporate.
 */
// class WPGpoauth {

    /**
     * TODO: Set authtoken in URL
     *
     * We should setup the initalization code to set the users access token
     * in the requesting URL when coming to the page we want them at...
     *
     * This should be done in the setup WP code
     */


    /**
     * Given session information. Fetch the access token stored for the user.
     * The cookie variable is optional; it's not likely to see use, but may.
     */
    function getUserAccessToken($cookie = NULL){

        // $accessToken = NULL;
        // if (!empty(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token']))
        //   $accessToken = get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token'];
        //
        // if (!empty(get_user_meta(get_current_user_id(), 'wp_capabilities', true)['administrator']))
        //   $accessToken = get_user_meta(get_current_user_id(), 'wp_capabilities', true)['administrator'];
        //
        // return $accessToken;
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
// }
