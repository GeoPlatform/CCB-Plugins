import { Inject, Injectable } from '@angular/core';
import { Observable, Observer, Subject, Subscription } from 'rxjs';

import {
    ngGpoauthFactory, AuthService, GeoPlatformUser
} from '@geoplatform/oauth-ng/angular';

import { RPMService } from '@geoplatform/rpm/src/iRPMService'
import { Config } from '@geoplatform/client';

import { environment } from '../../environments/environment';
import { authServiceFactory } from './auth.factory';


@Injectable()
export class PluginAuthService {

    private user : GeoPlatformUser;
    private user$ : Subject<GeoPlatformUser>;
    private observers : { [key:string]: Observer<GeoPlatformUser> } =
        {} as { [key:string]: Observer<GeoPlatformUser> };
    private gpAuthSubscription : Subscription;
    private authService : AuthService;
    private rpm: RPMService;



    constructor( @Inject(RPMService) rpm : RPMService ) {
        this.authService = authServiceFactory();
        this.rpm = rpm;
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

        // if( Config.env.indexOf('dev') === 0 ) {
        //     console.log("[WARN] WARNING!!! - Using 'test' user because environment is configured as dev*");
        //     let user = new GeoPlatformUser({
        //         username: "tester",
        //         sub      : 'test',
        //         name    : "Test User",
        //         email   : "test@geoplatform.us",
        //         orgs     : [{_id: "test", name:"GeoPlatform"}],
        //         roles   : "gp_editor",
        //         groups  : [{_id: "test", name: "gp_editor"}],
        //         exp     : new Date().getTime() + (1000*60*60),
        //         scope   : null,
        //         iss     : null,
        //         aud     :null,
        //         nonce   : null,
        //         iat     : null
        //     });
        //     this.onUserChange(user);
        //
        // }

        this.authService.getUser().then( user => { this.onUserChange(user); });
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
         return this.authService.checkWithClient()
         .then( token => this.authService.getUser() )
         .then( user => {
             setTimeout( () => { this.onUserChange(user); },100 );
             return user;
         });
     }

    // /**
    //  *
    //  */
    // verifyToken( token : string ) : Promise<string> {
    //     if('development' === environment.env || !this.authService) {
    //         return Promise.resolve(token);
    //     }
    //     return this.authService.checkWithClient().catch(e => {
    //         console.log(e);
    //     });
    // }

    /**
     *
     */
    subscribe( callback : Observer<GeoPlatformUser> ) : Subscription {
        if(this.user) {
            setTimeout( () => { callback.next(this.user); },100);
        }
        return this.user$.subscribe( callback );
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
