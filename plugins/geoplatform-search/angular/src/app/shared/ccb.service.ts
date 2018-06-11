import { Injectable } from '@angular/core';
import {
    HttpClient, HttpRequest, HttpParams, HttpEvent, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";

import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

// const CCB_URL = 'https://sit-ccb.geoplatform.us/sit-ccb/wp-json/wp/v2/';

@Injectable()
export class CCBService {

    private baseUrl : string;
    private usersList : { id: number; label: string }[] = [] as { id: number; label: string }[];

    constructor(private http : HttpClient) {
        this.baseUrl = Config.wpUrl + '/wp-json/wp/v2';
        this.updateUserList();
    }

    search(query : Query) : Promise<any> {

        let reqs = [];
        let qry = query.clone();

        let types = query.getTypes();
        if(!types || !types.length) {
            //default to all 3 types supported so far
            types = ['pages','posts','media'];
        } else {
            //remove types set on query object
            qry.setTypes(null);
        }

        return Promise.all(
            types.map( type => {
                let request = this.buildRequest(qry, type);
                return this.execute(request).catch(e=>[]);
            })
        ).then(results => {
            //have to build unified list ordering the items by their modified date
            let hits = [];
            results.forEach( group => {
                if(group && Array.isArray(group)) {
                    hits = hits.concat(group);
                } //else it's an unexpected response, ignore that group
            });

            //WP api doesn't return total hits count, so we have to calculate
            // total counts using current paging info and current page of results
            let total = (query.getPage() * query.getPageSize()) + hits.length;

            hits.sort( (a,b) => a.modified < b.modified ? 1 : -1 );
            hits = hits.slice(0, query.getPageSize());
            hits.forEach( hit => {
                hit.author = { id: hit.author, label: this.usersList[hit.author] };
                if(!hit.author) this.updateUserList();
            });

            return {
                totalResults: total,
                results: hits
            };
        })
        .catch(e => Promise.reject(e));

    }

    buildRequest(query: Query, type: string) : HttpRequest<any> {
        let qobj = query.getQuery();

        if(qobj.q) {
            qobj.search = qobj.q;
            delete qobj.q;
        }

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
            let authors = Object.keys(this.usersList)
                .filter(id=>this.usersList[id]===qobj.createdBy);
            //if a resolvable WP user, use that, otherwise use GP user which
            // should result in 0 results (possibly through an error)
            qobj.author = authors.length ? authors[0] : qobj.createdBy;
            delete qobj.createdBy;
        }

        let params : HttpParams = new HttpParams({fromObject: qobj});
        return new HttpRequest<any>('GET', this.baseUrl + '/' + type, { params:params });
    }

    /**
     * @param {HttpRequest} request - Angular HttpRequest object
     * @return {Promise} resolving the response or an error
     */
    execute(request : HttpRequest<any>) {

        // let url = request.url + '?' + request.params.toString();
        // return this.http.jsonp(url, '_jsonp')  //'_jsonp' is WP callback param
        return this.http.request(request)
        .map( (event: HttpEvent<any>) => {
            if (event instanceof HttpResponse) {
                return (event as HttpResponse<any>).body;
            }
            return [];
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



    updateUserList() {
        //cache a list of users
        this.execute(new HttpRequest<any>('GET', this.baseUrl + '/users'))
        .then( users => {
            users.forEach( user => {
                this.usersList[user.id] = user.name;
            });
        });
    }
}
