import { Observable, Observer, Subject }          from 'rxjs';
import { ISubscription }                from "rxjs/Subscription";
import { AuthService, GeoPlatformUser } from '@geoplatform/oauth-ng/angular';

import { PluginAuthService } from './auth.service';


const EDIT_ROLE = 'gp_editor';


/**
 * Base class that can be used to hook authentication notifications into
 * Angular @Component instances.
 */
export abstract class AuthenticatedComponent {

    public user : GeoPlatformUser;
    private gpAuthSubscription : ISubscription;
    // protected authService : AuthService;

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
     * @param item - optional object the user may be able to edit
     * @return boolean indicating whether user can edit the requested item or is an editor if no item was specified
     */
    canUserEdit(item ?: any) {
        if(!this.user) return false;
        if(this.user.isAuthorized(EDIT_ROLE)) return true;
        return this.isAuthorOf(item);
    }

    /**
     * @param item - object the user may be the owner of
     * @return boolean indicating if the user is the associated creator/owner of the item
     */
    isAuthorOf(item ?: any) {
        if(!this.user || !item) return false;
        return item.createdBy && item.createdBy === this.user.username;
    }

    /**
     * @param {GeoPlatformUser} user - authenticated user object or null if not authed
     */
    protected onUserChange(user) { /* implement in sub-classes */ }


}
