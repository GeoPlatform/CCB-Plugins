import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { Config, Query, QueryParameters, ItemTypes } from '@geoplatform/client';


@Injectable()
export class CkanService {

    constructor(private http: HttpClient) {
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
        let url = Config.ualUrl + '/api/ckan';
        let obj = query.getQuery();
        if(obj.keyword) {
            obj.keywords = obj.keyword;
            delete obj.keyword;
        }
        let params : HttpParams = new HttpParams({fromObject: obj});
        return new HttpRequest<any>('GET', url, { params: params });
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
