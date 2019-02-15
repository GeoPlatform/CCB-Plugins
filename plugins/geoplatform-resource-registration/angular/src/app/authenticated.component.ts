import { Observable, Subject }          from 'rxjs';
import { ISubscription }                from "rxjs/Subscription";
import { AuthService, GeoPlatformUser } from 'geoplatform.ngoauth/angular';
import { authServiceFactory }           from './auth.factory';


/**
 * Base class that can be used to hook authentication notifications into
 * Angular @Component instances.
 */
export abstract class AuthenticatedComponent {

    public user : GeoPlatformUser;
    private gpAuthSubscription : ISubscription;
    protected authService : AuthService;

    constructor() {
        this.authService = authServiceFactory();
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

        this.gpAuthSubscription = this.authService.getMessenger().raw().subscribe(msg => {
            // console.log("AuthService() - Received Auth Message: " + msg.name);
            switch(msg.name){
                case 'userAuthenticated':
                this.user = msg.user;
                this.onUserChange(msg.user);
                // this.user$.next(msg.user);
                break;

                case 'userSignOut':
                this.user = null;
                this.onUserChange(null);
                break;
            }
        });

        this.authService.getUser().then( user => {
            // console.log('USER: ' + JSON.stringify(user));
            this.user = user;
            this.onUserChange(user);
        })
        .catch(e => {
            console.log("Error retrieving user: " + e.message);
        })
    }

    /**
     * Sub-classes must invoke this method in order to de-register listeners
     * for authentication events and clean up internals
     */
    destroy() {
        this.gpAuthSubscription.unsubscribe();
        this.gpAuthSubscription = null;
        this.user = null;
        this.authService = null;
    }

    /** @return {boolean} */
    isAuthenticated() : boolean { return !!this.user; }

    /** @return {GeoPlatformUser} */
    getUser() : GeoPlatformUser { return this.user; }

    /**
     * @param {GeoPlatformUser} user - authenticated user object or null if not authed
     */
    protected onUserChange(user) { /* implement in sub-classes */ }


}
