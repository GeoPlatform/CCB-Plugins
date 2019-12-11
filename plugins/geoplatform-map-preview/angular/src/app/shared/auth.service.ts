import { Inject, Injectable } from '@angular/core';
import { Observable, Observer, Subject, Subscription } from 'rxjs';

import {
    ngGpoauthFactory, AuthService, GeoPlatformUser
} from '@geoplatform/oauth-ng/angular';

import { environment } from '../../environments/environment';
import { authServiceFactory } from './auth.factory';
import { rpmServiceFactory } from './service.provider';
import { RPMService } from '@geoplatform/rpm/src/iRPMService'
import { logger } from './logger';




@Injectable()
export class PluginAuthService {

    private user : GeoPlatformUser;
    private user$ : Subject<GeoPlatformUser>;
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
            // logger.debug("Received Auth Message: " + msg.name);
            switch(msg.name){
                case 'userAuthenticated': this.onUserChange(msg.user); break;
                case 'userSignOut': this.onUserChange(null); break;
            }
        });


        //force check to make sure user is actually logged in and token hasn't expired/been revoked
        this.authService.getUser().then( user => { this.onUserChange(user); });
    }

    onUserChange(user : GeoPlatformUser) {
        logger.debug("User: " + (user ? user.username : 'N/A'));
        // logger.debug('AuthService.onUserChange() returned ' +
        //     JSON.stringify(user, null, ' '));
        this.user = user;
        this.rpm.setUserId( user ? user.id : null);
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

    /**
     *
     */
    subscribe( callback : Observer<GeoPlatformUser> ) : Subscription {
        return this.user$.subscribe( callback );
    }

    dispose() {
        if(this.gpAuthSubscription) {
            this.gpAuthSubscription.unsubscribe();
            this.gpAuthSubscription = null;
        }
        this.user = null;
        this.user$ = null;
        this.authService = null;
    }

}
