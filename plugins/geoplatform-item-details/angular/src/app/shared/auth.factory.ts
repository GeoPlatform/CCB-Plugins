import { ngGpoauthFactory, AuthService } from 'geoplatform.ngoauth/angular';
import { environment } from '../../environments/environment';

//should be exported by gp-ngoauth but isn't so we are declaring it here...
interface AuthConfig {
    AUTH_TYPE?: 'grant' | 'token'
    IDP_BASE_URL?: string
    APP_BASE_URL?: string
    ALLOW_SSO_LOGIN?: boolean
    APP_ID?: boolean
    ALLOW_IFRAME_LOGIN?: boolean
    FORCE_LOGIN?: boolean
    CALLBACK?: string
    LOGIN_URL?: string
    LOGOUT_URL?: string
    ALLOW_DEV_EDITS?: boolean
};
const AUTH_KEYS = [
    'AUTH_TYPE', 'IDP_BASE_URL', 'APP_BASE_URL', 'ALLOW_SSO_LOGIN',
    'APP_ID', 'ALLOW_IFRAME_LOGIN', 'FORCE_LOGIN', 'CALLBACK',
    'LOGIN_URL', 'LOGOUT_URL', 'ALLOW_DEV_EDITS'
];




var authService : AuthService = null;


export function authServiceFactory() {

    //once service has been built, keep using it
    if(authService) return authService;

    //
    //but the first time it's requested, it has to be built using env settings
    let authSettings : AuthConfig = {};

    let gpGlobal = (<any>window).GeoPlatform;
    if(gpGlobal && gpGlobal.config && gpGlobal.config.auth) {
        //auth library settings made available through WP via 'GeoPlatform' global
        //https://geoplatform.atlassian.net/browse/DT-2307
        authSettings = gpGlobal.config.auth;
    } else {
        authSettings.APP_BASE_URL = environment.wpUrl || '';
        AUTH_KEYS.forEach( key => {
            let v = environment[key];
            if(typeof(v) !== 'undefined') {
                if(~key.indexOf('ALLOW') || ~key.indexOf('FORCE')) {
                    v = (v === true || v === 'true');
                }
                authSettings[key] = v;
            }
        });
    }

    console.log("Configuring OAuth using: ");
    console.log(authSettings);

    authService = ngGpoauthFactory(authSettings);
    return authService;
};
