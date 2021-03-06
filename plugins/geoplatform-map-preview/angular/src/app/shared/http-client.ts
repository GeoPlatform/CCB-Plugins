
/*
 * DEPRECATED.  Use: import { NG2HttpClient } from '@geoplatform/client/angular';
 */

import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpHeaders, HttpParams,
    HttpResponse, HttpEvent, HttpErrorResponse
} from '@angular/common/http';
import { Observable, Subject, Subscription, of } from 'rxjs';
import { catchError, map, tap } from 'rxjs/operators';

import { logger } from './logger';
import { ItemDetailsError } from './item-details-error';


export class NG2HttpClient {


    private timeout : number = 10000;
    private token : Function;

    /**
     * @param {integer} options.timeout
     * @param {string} options.token - the bearer token or a function to retrieve it
     * @param {Object} options.$http - angular $http service instance
     */
    constructor(private http: HttpClient, options?: any) {
        options = options || {};
        this.setTimeout(options.timeout||10000);
        this.setAuthToken(options.token);
    }



    setTimeout(timeout: number) {
        this.timeout = timeout;
    }

    /**
     * @param {string|Function} arg - specify the bearer token or a function to retrieve it
     */
    setAuthToken(arg: any) {
        if(arg && typeof(arg) === 'string')
            this.token = function() { return arg; };
        else if(arg && typeof(arg) === 'function')
            this.token = arg;
        //else do nothing
    }



    createRequestOpts(options: any) : HttpRequest<any> {

        let opts : any = {};

        if(options.options && options.options.responseType) {
            opts.responseType = options.options.responseType;
        } else opts.responseType = 'json';  //default response type

        if(options.params) {
            opts.params = new HttpParams({fromObject: options.params});
        }

        if(options.data) {
            opts.body = options.data;
        }

        let headers = {};

        //set authorization token if one was provided
        if(this.token) {
            let token = this.token();
            if(token) {
                //remember headers.set returns a NEW instance of Headers
                headers['Authorization'] = 'Bearer ' + token;
            }
        }
        //set any remaining headers...

        opts.headers = new HttpHeaders(headers);
        // logger.debug("HttpClient headers: ", opts.headers.keys().map(key => opts.headers.get(key)) );

        if(opts.body) {
            return new HttpRequest<any>(options.method, options.url, opts.body, opts);
        } else {
            return new HttpRequest<any>(options.method, options.url, opts);
        }

    }

    /**
     * @param {HttpRequest} request - Angular HttpRequest object
     * @return {Promise} resolving the response or an error
     */
    execute(request : HttpRequest<any>) {
        return this.http.request(request)
        .pipe(
            map( (event: HttpEvent<any>) => {
                if (event instanceof HttpResponse) {
                    return (event as HttpResponse<any>).body;
                }
                return {};
            })
        )
        .toPromise()
        .catch( err => {
            // console.log("NG2HttpClient.catch() - " + JSON.stringify(err));
            if (err instanceof HttpErrorResponse) {
                let msg = "An error occurred communicating with the GeoPlatform API";
                if(err.error && err.error.error && err.error.error.message) {
                    msg = err.error.error.message;
                } else if (err.error && err.error.message) {
                    msg = err.error.message;
                } else if(err.message) {
                    msg = err.message;
                }
                throw new ItemDetailsError(msg, err.status);
            }
            return {};
        });
    }

}


export default NG2HttpClient;
