import { Injectable } from '@angular/core';
import { Observable, Observer, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import {
    ngGpoauthFactory, AuthService, GeoPlatformUser
} from 'geoplatform.ngoauth/angular';

import { environment } from '../../environments/environment';
import { authServiceFactory } from './auth.factory';
import { RPMService } from 'geoplatform.rpm/src/iRPMService'



@Injectable()
export class PluginAuthService {

    private user : GeoPlatformUser;
    private user$ : Observable<GeoPlatformUser>;
    private observers : Observer<GeoPlatformUser>[] = [] as Observer<GeoPlatformUser>[];
    private gpAuthSubscription : ISubscription;
    private authService : AuthService;

    constructor(private rpm: RPMService) {
        this.authService = authServiceFactory();
        this.init();
    }


    init() {

        this.user$ = new Observable( (observer:Observer<GeoPlatformUser>) => {
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
                    if(msg.user) this.rpm.setUserId(msg.user.id)
                break;
                case 'userSignOut':
                    this.onUserChange(null);
                break;
            }
        });


        //force check to make sure user is actually logged in and token hasn't expired/been revoked
        this.authService.checkWithClient(null)
        //then fetch user info
        .then( (jwt) => {
            if(!jwt) return null;   //if no jwt, no use getting user info
            return this.authService.getUser();
        })
        .then( user => {
            console.log('AuthService.getUser() returned ' +
                JSON.stringify(user, null, ' '));
            this.onUserChange(user);
        })
        .catch(e => {
            console.log("AuthService.init() - Error retrieving user: " + e.message);
            this.onUserError(e);
        });
    }

    onUserChange(user : GeoPlatformUser) {
        this.user = user;
        this.rpm.setUserId( user ? user.id : null);
        this.observers.forEach( obs => obs.next(user) );
    }

    onUserError(e) {
        this.observers.forEach( obs => {
            try { obs.error(e); } catch(e) { }
        });
    }

    isAuthenticated() : boolean {
        return !!this.user;
    }

    getUser() : GeoPlatformUser {
        return this.user;
    }

    getToken() : string {
        return this.authService.getJWT();
    }

    /**
     * Check the underlying authentication mechanism endpoint to validate the
     * current JWT token (if one exists) is not expired or revoked.
     * @return GeoPlatformUser or null
     */
    check() : Promise<GeoPlatformUser> {
        return this.authService.checkWithClient(null)
        .then( token => this.authService.getUser() )
        .then( user => {
            this.onUserChange(user);
            return user;
        });
    }

    /**
     *
     */
    subscribe( callback : Observer<GeoPlatformUser> ) : ISubscription {
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
