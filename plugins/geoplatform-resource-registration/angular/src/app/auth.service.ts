import { Injectable } from '@angular/core';
import { Observable, Observer, Subject, Subscription } from 'rxjs';

import {
    ngGpoauthFactory, AuthService, GeoPlatformUser
} from '@geoplatform/oauth-ng/angular';

import { environment } from '../environments/environment';
import { authServiceFactory } from './auth.factory';
// import { RPMService } from 'geoplatform.rpm/src/iRPMService'



@Injectable()
export class PluginAuthService {

    private user : GeoPlatformUser;
    private user$ : Subject<GeoPlatformUser>;
    private observers : { [key:string]: Observer<GeoPlatformUser> } =
        {} as { [key:string]: Observer<GeoPlatformUser> };
    private gpAuthSubscription : Subscription;
    private authService : AuthService;

    constructor(
        // private rpm: RPMService
    ) {
        this.authService = authServiceFactory();
        this.init();
    }


    init() {

        this.user$ = new Subject<GeoPlatformUser>();

        if(!this.authService) return;

        const sub = this.authService.getMessenger().raw();
        this.gpAuthSubscription = sub.subscribe(msg => {
            // console.log("Received Auth Message: " + msg.name);
            switch(msg.name){
                case 'userAuthenticated': this.onUserChange(msg.user); break;
                case 'userSignOut': this.onUserChange(null); break;
            }
        });


        //force check to make sure user is actually logged in and token hasn't expired/been revoked
        this.verifyToken(null)
        //then fetch user info
        .then( (jwt) => {
            if(!jwt) return null;   //if no jwt, no use getting user info
            return this.authService.getUser();
        })
        .then( user => { this.onUserChange(user); })
        .catch(e => {
            // console.log("AuthService.init() - Error retrieving user: " + e.message);
            this.onUserChange(null);
        });
    }

    onUserChange(user : GeoPlatformUser) {
        console.log("User: " + (user ? user.username : 'N/A'));
        // console.log('AuthService.onUserChange() returned ' +
        //     JSON.stringify(user, null, ' '));
        this.user = user;
        // this.rpm.setUserId( user ? user.id : null);
        this.user$.next(user);
    }


    isAuthenticated() : boolean {
        return !!this.user;
    }

    getUser() : GeoPlatformUser {
        return this.user;
    }

    getToken() : string {
        return this.authService ? this.authService.getJWT() : null;
    }

    /**
     * Check the underlying authentication mechanism endpoint to validate the
     * current JWT token (if one exists) is not expired or revoked.
     * @return GeoPlatformUser or null
     */
    check() : Promise<GeoPlatformUser> {
        if(!this.authService) {
            console.log("[WARN] No auth service to check token with...");
            return Promise.resolve(null);
        }
        // console.log("[DEBUG] Checking with auth service for token");
        return this.authService.check().then( user => {
            setTimeout( () => { this.onUserChange(user); },100 );
            return user;
        });
    }

    /**
     *
     */
    subscribe( callback : Observer<GeoPlatformUser> ) : Subscription {
        return this.user$.subscribe( callback );
    }

    verifyToken( token : string ) : Promise<string> {
        if('development' === environment.env || !this.authService) {
            return Promise.resolve(token);
        }
        return this.authService.checkWithClient(token);
    }



    dispose() {
        if(this.gpAuthSubscription) {
            this.gpAuthSubscription.unsubscribe();
            this.gpAuthSubscription = null;
        }
        this.user = null;
        this.user$ = null;
        this.observers = null;
        this.authService = null;
    }

}
