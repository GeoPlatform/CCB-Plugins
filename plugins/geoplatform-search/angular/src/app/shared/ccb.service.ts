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
        this.baseUrl = '/wp-json/wp/v2';
        this.updateUserList();
    }

    search(query : Query) : Promise<any> {

        let types = query.getTypes();
        if(!types || !types.length)
            return Promise.resolve({totalResults:0, results:[]});

        let type = types[0];
        let request = this.buildRequest(query, type);
        return this.execute(request);

        // let reqs = [];
        // let qry = query.clone();
        //
        // let types = query.getTypes();
        // if(!types || !types.length) {
        //     //default to all 3 types supported so far
        //     types = ['pages','posts','media'];
        // } else {
        //     //remove types set on query object
        //     qry.setTypes(null);
        // }
        //
        // return Promise.all(
        //     types.map( type => {
        //         let request = this.buildRequest(qry, type);
        //         return this.execute(request).catch(e=>{
        //             return { totalResults: 0, results: [] }
        //         });
        //     })
        // ).then(responses => {
        //
        //     //have to build unified list ordering the items by their modified date
        //     let totalResults = 0;
        //     let hits = [];
        //     responses.forEach( (group:{totalResults:number;results:any[]}) => {
        //         let total = group.totalResults || 0;
        //         totalResults += total;
        //         let results = group.results || [];
        //         if(results && Array.isArray(results)) {
        //             hits = hits.concat(results);
        //         } //else it's an unexpected response, ignore that group
        //     });
        //
        //     hits.sort( (a,b) => a.modified < b.modified ? 1 : -1 );
        //     hits = hits.slice(0, query.getPageSize());
        //     hits.forEach( hit => {
        //         hit.author = { id: hit.author, label: this.usersList[hit.author] };
        //         if(!hit.author) this.updateUserList();
        //     });
        //
        //     return {
        //         totalResults: totalResults,
        //         results: hits
        //     };
        // })
        // .catch(e => Promise.reject(e));

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
        items.forEach( item => {
            item.author = {
                id: item.author,
                label: this.usersList[item.author]
            };
            if(!item.author) this.updateUserList();
        });
        return items;
    }

    updateUserList() {
        //cache a list of users
        this.execute(new HttpRequest<any>('GET', this.baseUrl + '/users', {
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
