import { Observable, Observer, Subject, Subscription } from 'rxjs';
import { AuthService, GeoPlatformUser } from '@geoplatform/oauth-ng/angular';
import { authServiceFactory }           from './auth.factory';
import { PluginAuthService } from './auth.service';
import { logger } from './logger';

/**
 * Base class that can be used to hook authentication notifications into
 * Angular @Component instances.
 */
export abstract class AuthenticatedComponent {

    public user : GeoPlatformUser;
    private gpAuthSubscription : Subscription;

    constructor( private authService : PluginAuthService ) {

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
                logger.error("Unable to get authenticated user info: ",
                    (err as Error).message);
            },
            complete : () => { }
        };

        this.gpAuthSubscription = this.authService.subscribe( obs );

        //in case the component is initialized after a user event has happened
        this.user = this.authService.getUser();
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

    /** @return {string} JWT token associated with the current user or null */
    getAuthToken() : string { return this.authService.getToken(); }

    /** @return Promise containing current user or null */
    checkAuth() : Promise<GeoPlatformUser> { return this.authService.check(); }

    /**
     * @param {GeoPlatformUser} user - authenticated user object or null if not authed
     */
    protected onUserChange(user) { /* implement in sub-classes */ }


}
