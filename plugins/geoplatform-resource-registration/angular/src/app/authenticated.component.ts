import { Observable, Observer, Subject }  from 'rxjs';
import { ISubscription }        from "rxjs/Subscription";
import { GeoPlatformUser }      from 'ng-gpoauth/angular';
import { authServiceFactory }   from './auth.factory';
import { PluginAuthService }    from './auth.service';


/**
 * Base class that can be used to hook authentication notifications into
 * Angular @Component instances.
 */
export abstract class AuthenticatedComponent {

    public user : GeoPlatformUser;
    private gpAuthSubscription : ISubscription;

    constructor(private authService : PluginAuthService) {
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
            next : (value: GeoPlatformUser) => {
                this.user = value;
                this.onUserChange(this.user);
            },
            error : (err: any) => {
                console.log("Unable to get authenticated user info: " +
                    (err as Error).message);
            },
            complete : () => { }
        };

        this.gpAuthSubscription = this.authService.subscribe( obs );
    }

    /**
     * Sub-classes must invoke this method in order to de-register listeners
     * for authentication events and clean up internals
     */
    destroy() {
        if(this.gpAuthSubscription) {
            this.gpAuthSubscription.unsubscribe();
            this.gpAuthSubscription = null;
        }
        this.user = null;
        this.authService = null;
    }

    /** @return {boolean} */
    isAuthenticated() : boolean { return !!this.user; }

    /** @return {GeoPlatformUser} */
    getUser() : GeoPlatformUser { return this.user; }

    /** @return {string} JWT token associated with the current user or null */
    getAuthToken() : string {
        return this.authService ? this.authService.getToken() : null;
    }

    /** @return Promise containing current user or null */
    checkAuth() : Promise<GeoPlatformUser> {
        if(this.authService) return this.authService.check();
        return Promise.resolve({username:'tester'} as GeoPlatformUser);
    }

    /**
     * @param {GeoPlatformUser} user - authenticated user object or null if not authed
     */
    protected onUserChange(user) { /* implement in sub-classes */ }


}
