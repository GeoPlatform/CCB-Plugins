import { Injectable } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { AuthService, GeoPlatformUser } from 'geoplatform.ngoauth/angular';

interface Observer {
    next: (value:any) => void;
    error: (value:Error)=>void;
}



@Injectable()
export class PluginAuthService {

    private user : any;
    private user$ : Observable<any>;
    private observers : Observer[] = [] as Observer[];
    private gpAuthSubscription : ISubscription;


    constructor( /*private authService : AuthService*/ ) {


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



        // const sub = authService.getMessenger().raw();
        // this.gpAuthSubscription = sub.subscribe(msg => {
        //     console.log("Received Auth Message: " + msg.name);
        //     switch(msg.name){
        //         case 'userAuthenticated':
        //         this.user = msg.user;
        //         this.observers.forEach( obs => obs.next(msg.user) );
        //         // this.user$.next(msg.user);
        //         break;
        //
        //         case 'userSignOut':
        //         this.user = null;
        //         this.observers.forEach( obs => obs.next(null) );
        //         // this.user$.next(null);
        //         break;
        //     }
        // })
    }


    isAuthenticated() : boolean {
        return !!this.user;
    }

    getUser() : any {
        return this.user;
    }

    subscribe( callback : Observer ) : ISubscription {
        return this.user$.subscribe( callback );
    }


}
