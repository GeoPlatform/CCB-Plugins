
import { Injectable } from '@angular/core';

type JWT = {
    sub     : string
    name    : string
    email   : string
    username: string
    roles   : string
    groups  : [{_id: string, name: string}]
    orgs    : [{_id: string, name: string}]
    scope   : [string]
    iss     : string
    aud     : string
    nonce   : string
    iat     : number
    exp     : number
    implicit?: boolean
};


export class GeoPlatformUser {

    id      : string;
    username: string;
    name    : string;
    email   : string;
    org     : string;
    roles   : string;
    groups  : [{_id: string, name: string}];
    exp     : number;

    constructor( opts : JWT ) {
        this.id = opts.sub
        this.username = opts.username
        this.name = opts.name
        this.email = opts.email
        this.org = opts.orgs[0] && opts.orgs[0].name
        this.groups = opts.groups
        this.roles = opts.roles
        this.exp = opts.exp
    }

    toJSON() {
        return JSON.parse(JSON.stringify(Object.assign({}, this)));
    }

    clone() {
        return Object.assign({}, this)
    }

    compare( arg: any ) {
        if (arg instanceof GeoPlatformUser) {
          return this.id === arg.id;
        } else if (typeof(arg) === 'object') {
          return typeof(arg.id) !== 'undefined' &&
            arg.id === this.id;
        }
        return false;
    }

    isAuthorized(role: string) {
        return false;
        // let env = Config.env || Config.ENV || Config.NODE_ENV;
        // if((env === 'dev' || env === 'development') &&
        //     typeof(Config.ALLOW_DEV_EDITS) !== 'undefined')
        //     return true;
        //
        // return this.groups &&
        //         !!this.groups
        //                 .map(g => g.name)
        //                 .filter(n => n === role)
        //                 .length;
    }


    static getTestUser() {
        let opts : JWT = {
            sub     : '',
            name    : 'Test User',
            email   : 'test@tester.ts',
            username: 'tester',
            roles   : null,
            groups  : [{_id: 't', name: 'test'}],
            orgs    : [{_id: 't', name: 'test'}],
            scope   : ['test'],
            iss     : null,
            aud     : null,
            nonce   : null,
            iat     : 1,
            exp     : new Date().getTime(),
            implicit: false
        };
        return new GeoPlatformUser(opts);
    }
}





const TOKEN_NAME = 'gpoauthJWT';



@Injectable()
export class AuthService {

    constructor() {}



    getUserFromJWT(jwt: string) {
        const user = this.parseJwt(jwt)
        return user ?
              new GeoPlatformUser(Object.assign({}, user, { id: user.sub })) :
              null;
    }

    // /**
    //  * If the callback parameter is specified, this method
    //  * will return undefined. Otherwise, it returns the user (or null).
    //  *
    //  * Side Effects:
    //  *  - Will redirect users if no valid JWT was found
    //  *
    //  * @param callback optional function to invoke with the user
    //  * @return object representing current user
    //  */
    // getUser() : Promise<GeoPlatformUser> {
    //     const jwt = this.getJWT();
    //     // If callback provided we can treat async and call server
    //     if(callback && typeof(callback) === 'function'){
    //         // this.check().then(user => callback(user));
    //         callback(this.getUserFromJWT(jwt));
    //
    //         // If no callback we have to provide a sync response (no network)
    //     } else {
    //         // We allow front end to get user data if grant type and expired
    //         // because they will recieve a new token automatically when
    //         // making a call to the client(application)
    //         return this.isImplicitJWT(jwt) && this.isExpired(jwt) ?
    //             null : this.getUserFromJWT(jwt);
    //     }
    // }

    /**
     * Promise version of get user.
     *
     * Below is a table of how this function handels this method with
     * differnt configurations.
     *  - FORCE_LOGIN : Horizontal
     *  - ALLOWIFRAMELOGIN : Vertical
     *
     *
     * getUserQ | T | F (FORCE_LOGIN)
     * -----------------------------
     * T        | 1 | 2
     * F        | 3 | 4
     * (ALLOWIFRAMELOGIN)
     *
     * Cases:
     * 1. Delay resolve function till user is logged in
     * 2. Return null (if user not authorized)
     * 3. Force the redirect
     * 4. Return null (if user not authorized)
     *
     * NOTE:
     * Case 1 above will cause this method's promise to be a long stall
     * until the user completes the login process. This should allow the
     * app to forgo a reload is it should have waited till the entire
     * time till the user was successfully logged in.
     *
     * @method getUserQ
     *
     * @returns {Promise<User>} User - the authenticated user
     */
    getUser(): Promise<GeoPlatformUser | null> {

        const jwt = this.getJWT();
        let user = jwt ? this.getUserFromJWT(jwt) : null;
        return Promise.resolve(user);

        // const self = this;
        // const q = $q.defer<GeoPlatformUser | null>()
        //
        // this.check().then(user => {
        //     if(user) { q.resolve(user) }
        //     else {
        //         // Case 1 - ALLOWIFRAMELOGIN: true | FORCE_LOGIN: true
        //         if(Config.ALLOWIFRAMELOGIN && Config.FORCE_LOGIN){
        //             // Resolve with user once they have logged in
        //             $rootScope.$on('userAuthenticated', (event: ng.IAngularEvent, user: User) => {
        //                 q.resolve(user)
        //             })
        //         }
        //         // Case 2 - ALLOWIFRAMELOGIN: true | FORCE_LOGIN: false
        //         if(Config.ALLOWIFRAMELOGIN && !Config.FORCE_LOGIN){
        //             q.resolve(null)
        //         }
        //         // Case 3 - ALLOWIFRAMELOGIN: false | FORCE_LOGIN: true
        //         if(!Config.ALLOWIFRAMELOGIN && Config.FORCE_LOGIN){
        //             addEventListener('message', (event: any) => {
        //                 // Handle SSO login failure
        //                 if(event.data === 'iframe:ssoFailed'){
        //                     q.resolve(self.getUser())
        //                 }
        //             })
        //             q.resolve(null)
        //         }
        //         // Case 4 - ALLOWIFRAMELOGIN: false | FORCE_LOGIN: false
        //         if(!Config.ALLOWIFRAMELOGIN && !Config.FORCE_LOGIN){
        //             q.resolve(null) // or reject?
        //         }
        //     }
        // })
        // .catch((err: Error) => console.log(err))
        //
        // return q.promise;
    }


    /**
     * Attempt and pull JWT from the following locations (in order):
     *  - URL query parameter 'access_token' (returned from IDP)
     *  - Browser local storage (saved from previous request)
     *
     * @method getJWT
     *
     * @return {sting | undefined}
     */
    getJWT(): string {
        const jwt = /* this.getJWTFromUrl() || */ this.getJWTfromLocalStorage();
        // Only deny implicit tokens that have expired
        if(!jwt || (jwt && this.isImplicitJWT(jwt) && this.isExpired(jwt))) {
            return null;
        } else {
            return jwt;
        }
    }



    private saveToLocalStorage(value: any) {
        localStorage.setItem(TOKEN_NAME, btoa(value));
    }

    private getJWTfromLocalStorage() {
        return this.getFromLocalStorage(TOKEN_NAME);
    }

    private getFromLocalStorage(key:string) {
        const raw = localStorage.getItem(key);
        try{
            return raw ? atob(raw) : undefined;
        } catch (e) { // Catch bad encoding or formally not encoded
            return undefined;
        }
    }


    /**
     * Remove the JWT saved in local storge.
     *
     * @method clearLocalStorageJWT
     *
     * @return  {undefined}
     */
    private clearLocalStorageJWT(): void {
        localStorage.removeItem(TOKEN_NAME);
    }

    /**
     * Is a token expired.
     *
     * @method isExpired
     * @param {JWT} jwt - A JWT
     *
     * @return {boolean}
     */
    isExpired(jwt: string): boolean {
        const parsedJWT = this.parseJwt(jwt)
        if(parsedJWT){
            const now = (new Date()).getTime() / 1000;
            return now > parsedJWT.exp;
        }
        return true;
    }

    /**
     * Is the JWT an implicit JWT?
     * @param jwt
     */
    private isImplicitJWT(jwt: string): boolean {
        const parsedJWT = this.parseJwt(jwt)
        return parsedJWT && parsedJWT.implicit;
    }

    /**
     * Unsafe (signature not checked) unpacking of JWT.
     *
     * @param {string} token - Access Token (JWT)
     *
     * @return {Object} the parsed payload in the JWT
     */
    private parseJwt(token: string): JWT {
        var parsed;
        if (token) {
            try {
                var base64Url = token.split('.')[1];
                var base64 = base64Url.replace('-', '+').replace('_', '/');
                parsed = JSON.parse(atob(base64));
            } catch(e) { /* Don't throw parse error */ }
        }
        return parsed;
    }

    /**
     * Simple front end validion to verify JWT is complete and not
     * expired.
     *
     * Note:
     *  Signature validation is the only truly save method. This is done
     *  automatically in the node-gpoauth module.
     */
    private validateJwt(token: string) : boolean {
        var parsed = this.parseJwt(token);
        var valid = (parsed && parsed.exp && parsed.exp * 1000 > Date.now()) ? true : false;
        return valid;
    }
}
