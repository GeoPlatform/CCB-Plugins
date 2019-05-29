import { Observable, Observer, Subject }          from 'rxjs';
import { ISubscription }                from "rxjs/Subscription";
import { AuthService, GeoPlatformUser } from 'geoplatform.ngoauth/angular';
import { authServiceFactory }           from './auth.factory';

import { PluginAuthService } from './auth.service';


/**
 * Base class that can be used to hook authentication notifications into
 * Angular @Component instances.
 */
export abstract class AuthenticatedComponent {

    public user : GeoPlatformUser;
    // private gpAuthSubscription : ISubscription;
    // protected authService : AuthService;

    constructor(private authService : PluginAuthService) {
        // this.authService = authServiceFactory();
    }

    //facade methods to mimic @Component lifecycle methods in case sub-classes
    // want to use consistent names
    ngOnInit() { this.init(); }
    ngOnDestroy() { this.destroy(); }

    /**
     * Sub-classes must invoke this method in order to register listeners
     * for authentication events
     */
    init() {

        let obs : Observer<GeoPlatformUser> = {
            next : function(value: GeoPlatformUser) {
                this.user = value;
                this.onUserChange(this.user);
            },

            error : function(err: any) {
                console.log("Unable to get authenticated user info: " +
                    (err as Error).message);
            },

            complete : function() {
                //TODO ???
            }
        };

        this.authService.subscribe( obs );



        // this.gpAuthSubscription = this.authService.getMessenger().raw().subscribe(msg => {
        //     console.log("AuthService() - Received Auth Message (" +
        //         msg.name + ") : " + JSON.stringify(msg.user, null, ' '));
        //     switch(msg.name){
        //         case 'userAuthenticated':
        //         this.user = msg.user;
        //         this.onUserChange(msg.user);
        //         break;
        //
        //         case 'userSignOut':
        //         this.user = null;
        //         this.onUserChange(null);
        //         break;
        //     }
        // });
        //
        // //force check to make sure user is actually logged in and token hasn't expired/been revoked
        // this.authService.checkWithClient(null)
        // //then fetch user info
        // .then( (jwt) => {
        //     if(!jwt) return null;   //if no jwt, no use getting user info
        //     return this.authService.getUser();
        // })
        // .then( user => {
        //     console.log('AuthService.getUser() returned ' +
        //         JSON.stringify(user, null, ' '));
        //     this.user = user;
        //     this.onUserChange(user);
        // })
        // .catch(e => {
        //     console.log("Error retrieving user: " + e.message);
        // })
    }

    /**
     * Sub-classes must invoke this method in order to de-register listeners
     * for authentication events and clean up internals
     */
    destroy() {
        // this.gpAuthSubscription.unsubscribe();
        // this.gpAuthSubscription = null;
        this.user = null;
        this.authService = null;
    }

    /** @return {boolean} */
    isAuthenticated() : boolean { return !!this.user; }

    /** @return {GeoPlatformUser} */
    getUser() : GeoPlatformUser { return this.user; }

    /** @return {string} JWT token associated with the current user or null */
    getAuthToken() : string {
        return this.authService.getToken();
        // return this.authService.getJWT();
    }

    checkAuth() : Promise<GeoPlatformUser> {
        return this.authService.check();
    }

    /**
     * @param {GeoPlatformUser} user - authenticated user object or null if not authed
     */
    protected onUserChange(user) { /* implement in sub-classes */ }





}
