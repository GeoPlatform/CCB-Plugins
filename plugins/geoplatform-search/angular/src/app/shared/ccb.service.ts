import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

@Injectable()
export class CCBService {

    private baseUrl : string;
    private usersList : { id: number; label: string }[] = [] as { id: number; label: string }[];

    constructor(private http : HttpClient) {
        this.baseUrl = Config.wpUrl;
        //this.updateUserList();
    }

    search(query : Query) : Promise<any> {

        let types = query.getTypes();
        if(!types || !types.length)
            return Promise.resolve({totalResults:0, results:[]});

        let type = types[0];
        let request = this.buildRequest(query, type);
        return this.execute(request);

    }

    getType(type : string) {
        if(!type) return 'page';
        switch(type) {
        case 'pages': return 'page';
        case 'posts': return 'post';
        default: return type;
        }
    }

    buildRequest(query: Query, type: string) : HttpRequest<any> {
        let qobj = query.getQuery();

        qobj.type = this.getType(type);
        qobj.page++;    //1-index in WP

        //rewrite page 'size' to 'per_page'
        // doubling requested page size since we are federating each type
        // and need to fill in the full page of combined results
        qobj['per_page'] = (query.getPageSize() || 10)*2;
        delete qobj['size'];

        //rewrite sort parameter to 'orderBy' and 'order'
        qobj['order'] = query.getSortOrder() ? 'asc':'desc';
        let field = query.getSortField();
        if('label' === field) field = 'title';
        else if('created' === field) field = 'date';
        qobj['orderby'] = field;
        delete qobj['sort'];

        //rewrite 'createdBy' param to 'author'
        //also note: value of param is the user's label which needs to be
        // resolved down to an id using the usersList cache
        if(qobj.createdBy) {
            qobj.author = qobj.createdBy;
            delete qobj.createdBy;
        }

        delete qobj.fields;
        delete qobj.includeFacets;

        let params : HttpParams = new HttpParams({fromObject: qobj});
        let url = Config.wpUrl + '/wp-json/geoplatform-search/v1/gpsearch';
        return new HttpRequest<any>('GET', url, { params:params });
    }

    /**
     * @param {HttpRequest} request - Angular HttpRequest object
     * @return {Promise} resolving the response or an error
     */
    execute(request : HttpRequest<any>) {

        let url = request.url + '?' + request.params.toString();

        return this.http.request(request)
        .map( (event: HttpEvent<any>) => {
            if (event instanceof HttpResponse) {
                let res : HttpResponse<any> = event as HttpResponse<any>;
                let wpTotal : any = res.headers.get('X-WP-Total');
                let total : number = isNaN(wpTotal) ? 0 : wpTotal*1;
                return {
                    results: this.fixAuthors(res.body),
                    totalResults: total as number
                };
            }
            return { totalResults: 0, results: [] };
        })
        .toPromise()
        .catch( err => {
            // console.log("NG2HttpClient.catch() - " + JSON.stringify(err));
            if (err instanceof HttpErrorResponse) {
                throw new Error(err.error.message);
            }
            return { totalResults: 0, results: [] };
        });
    }

    fixAuthors(items) : any[] {
    /*
        items.forEach( item => {
            item.author = {
                id: item.author,
                label: this.usersList[item.author]
            };
            if(!item.author) this.updateUserList();
        });
    */
        return items;
    }

    updateUserList() {
        //cache a list of users
        let url = Config.wpUrl + '/wp-json/wp/v2/users';
        this.execute(new HttpRequest<any>('GET', url, {
            params: { "per_page": 100 }
        }))
        .then( response => {
            let users = response.results;
            users.forEach( user => {
                this.usersList[user.id] = user.name;
            });
        });
    }
}
