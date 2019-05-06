import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { environment } from "../../environments/environment";
import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

const V1_SEARCH_ENDPOINT = Config.wpUrl + '/wp-json/geoplatform-search/v1/gpsearch';
const V2_USERS_ENDPOINT = Config.wpUrl + '/wp-json/wp/v2/users?per_page=100';


@Injectable()
export class CCBService {

    private baseUrl : string;
    private usersList : any = {};

    constructor(private http : HttpClient) {
        this.baseUrl = Config.wpUrl;
        this.updateUserList();
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
        //qobj.page++;    //1-index in WP

        //rewrite page 'size' to 'per_page'
        qobj['per_page'] = query.getPageSize() || 10;
        delete qobj['size'];

        //rewrite sort parameter to 'orderBy' and 'order'
        qobj['order'] = query.getSortOrder() ? 'asc':'desc';
        let field = query.getSortField();
        if('label' === field) field = 'title';
        else if('created' === field) field = 'date';
        qobj['orderby'] = field;
        delete qobj['sort'];

        //rewrite 'createdBy' param to 'author'
        if(qobj.createdBy) {
            qobj.author = qobj.createdBy;
            delete qobj.createdBy;
        }

        delete qobj.fields;
        delete qobj.includeFacets;

        let params : HttpParams = new HttpParams({fromObject: qobj});
        let url = V1_SEARCH_ENDPOINT;
        return new HttpRequest<any>('GET', url, { params:params });
    }

    /**
     * @param {HttpRequest} request - Angular HttpRequest object
     * @return {Promise} resolving the response or an error
     */
    execute(request : HttpRequest<any>) {

        //note if this is a users request, don't 'fix' those results
        let isUsersReq = null === request.params.get('type');

        return this.http.request(request)
        .map( (event: HttpEvent<any>) => {
            if (event instanceof HttpResponse) {
                let res : HttpResponse<any> = event as HttpResponse<any>;

                let wpTotal : any = (isUsersReq) ?
                    res.headers.get('X-WP-Total') :
                    res.body.totalResults;
                let total : number = isNaN(wpTotal) ? 0 : wpTotal*1;

                let results = (isUsersReq) ? res.body||[] :
                    (res.body.results||[]).map( it => { return this.fixResult(it) });
                return {
                    results: results,
                    totalResults: total as number
                };
            }
            return { totalResults: 0, results: [] };
        })
        .toPromise()
        .catch( err => {
            // console.log("CCBService.catch() - " + JSON.stringify(err));
            if (err instanceof HttpErrorResponse) {
                // throw new Error(err.error.message);
            }
            return { totalResults: 0, results: [] };
        });
    }

    updateUserList() {
        //cache a list of users
        let url = V2_USERS_ENDPOINT;
        this.execute( new HttpRequest<any>('GET', url) )
        .then( response => {
            let users = response.results;
            users.forEach( user => { this.usersList[user.id+''] = user.name; });
        });
    }

    fixResult(item) {

        item.id = item.ID;
        delete item.ID;

        item.type = item.post_type;
        delete item.post_type;

        item.link = item.guid;

        item.title = item.post_title;
        delete item.post_title;

        item.media_type = item.post_mime_type;
        delete item.post_mime_type;

        item.author = {
            id: item.post_author,
            label: this.usersList[item.post_author+''] || ""
        };
        delete item.post_author;

        item.date = item.post_date;
        delete item.post_date;

        item.modified = item.post_modified;
        delete item.post_modified;

        item.description = item.post_content_filtered;
        delete item.post_content_filtered;

        return item;
    }
}
