import { Injectable } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import {
    ngGpoauthFactory, AuthService, GeoPlatformUser
} from 'geoplatform.ngoauth/angular';

import { environment } from '../../environments/environment';


interface Observer {
    next: (value:GeoPlatformUser) => void;
    error: (value:Error)=>void;
}


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


const authServiceFactory = function() {

    let authSettings : AuthConfig = {
        APP_BASE_URL: environment.wpUrl || ''
    };
    //if run-time environment variables specified, add those (overwriting any duplicates)
    if((<any>window).GeoPlatformPluginEnv && (<any>window).GeoPlatformPluginEnv.wpUrl) {
        authSettings.APP_BASE_URL = (<any>window).GeoPlatformPluginEnv.wpUrl;
    }
    //auth library settings made available through WP via 'GeoPlatform' global
    //https://geoplatform.atlassian.net/browse/DT-2307
    if( (<any>window).GeoPlatform ) {
        const gp = (<any>window).GeoPlatform;
        AUTH_KEYS.forEach( key => { if(gp[key]) authSettings[key] = gp[key]; });
    }

    console.log("Configuring OAuth using: ");
    console.log(authSettings);

    return ngGpoauthFactory(authSettings);
};



@Injectable()
export class PluginAuthService {

    private user : GeoPlatformUser;
    private user$ : Observable<GeoPlatformUser>;
    private observers : Observer[] = [] as Observer[];
    private gpAuthSubscription : ISubscription;
    private authService : AuthService;

    constructor() {

        this.authService = authServiceFactory();

        this.user$ = new Observable( (observer:Observer) => {
            // Get the next and error callbacks. These will be passed in when
            // the consumer subscribes.
            const { next, error } = observer;

            let idx = this.observers.length;
            this.observers.push(observer);

            // When the consumer unsubscribes, clean up data ready for next subscription.
            return {
                unsubscribe() {
                    this.observers.splice(idx, 1);
                }
            };
        });


        const sub = this.authService.getMessenger().raw();
        this.gpAuthSubscription = sub.subscribe(msg => {
            console.log("Received Auth Message: " + msg.name);
            switch(msg.name){
                case 'userAuthenticated':
                this.onUserChange(msg.user);
                // this.user$.next(msg.user);
                break;

                case 'userSignOut':
                this.onUserChange(null);
                break;
            }
        });


        this.authService.getUser().then( user => {
            console.log('USER: ' + JSON.stringify(user));
            this.onUserChange(user);
        })
        .catch(e => {
            console.log("Error retrieving user: " + e.message);
        })
    }

    onUserChange(user) {
        this.user = user;
        this.observers.forEach( obs => obs.next(user) );
    }

    isAuthenticated() : boolean {
        return !!this.user;
    }

    getUser() : GeoPlatformUser {
        return this.user;
    }

    subscribe( callback : Observer ) : ISubscription {
        return this.user$.subscribe( callback );
    }


}
