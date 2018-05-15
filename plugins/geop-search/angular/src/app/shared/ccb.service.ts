import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse 
} from '@angular/common/http';
import { ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { Query, QueryParameters, ItemTypes } from 'geoplatform.client';

const CCB_URL = 'http://www.geoplatform.gov/api/json';

@Injectable()
export class CCBService {

    constructor(private http : HttpClient) {

    }

    search(query : Query) : Promise<any> {

        //TODO
        // 1. convert query to suitable WP query params
        let request = this.buildRequest(query);

        // 2. issue query to WP JSON API endpoint
        // 2a. handle response (including errors)
        // 2b. return response consistent with API Client ItemService
        return this.execute(request);

    }

    buildRequest(query: Query) : HttpRequest<any> {
        let params : HttpParams = new HttpParams({fromObject: query.getQuery()});
        return new HttpRequest<any>('GET', CCB_URL, params);
    }

    /**
     * @param {HttpRequest} request - Angular HttpRequest object
     * @return {Promise} resolving the response or an error
     */
    execute(request : HttpRequest<any>) {
        return this.http.request(request)
        .map( (event: HttpEvent<any>) => {
            if (event instanceof HttpResponse) {
                return (event as HttpResponse<any>).body;
            }
            return {};
        })
        .toPromise()
        .catch( err => {
            // console.log("NG2HttpClient.catch() - " + JSON.stringify(err));
            if (err instanceof HttpErrorResponse) {
                throw new Error(err.error.message);
            }
            return {};
        });
    }

}
