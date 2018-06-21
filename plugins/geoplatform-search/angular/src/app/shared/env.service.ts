
import { Injectable, OnInit } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { Observable, Subject } from 'rxjs';

import { Config } from 'geoplatform.client';
import { ServerRoutes } from '../server-routes.enum'


/**
 * Environment Settings service
 *
 * This service loads environment settings from 'assets/env.json' in order
 * to populate GeoPlatformClient.Config for usage throughout this app.
 * Use of this service instead of environments/whatever.ts is done to allow
 * dynamic configuration without recompiling bundles.
 */
@Injectable()
export class EnvironmentSettings {

    public envNull: Config = null;
    private envSubject: Subject<Config> = new Subject<Config>();

    constructor(private http: HttpClient) { }

    public load() : Promise<void> {
        return new Promise<void>((resolve, reject) => {
            this.http.get(`.${ServerRoutes.ASSETS}/env.json`).toPromise()
            .then((response : Response) => {
                // console.log("Loaded env: " + JSON.stringify(response));
                Config.configure(response);
                this.envSubject.next(Config);
                resolve();
            }).catch((response: any) => {
                reject(`Could not load env file: ${response.message}`);
            });
        });
    }

    public subscribe(caller: any, callback: (caller: any, es: Config) => void) {
        this.envSubject.subscribe( (env) => {
            if (env === null) return;
            callback(caller, env);
        });
    }
}
