import { Params } from '@angular/router';
import { HttpClient } from '@angular/common/http';
// import { Observable, Subject } from 'rxjs';
// import {
//     catchError, debounceTime, distinctUntilChanged, map, tap, switchMap, merge
// } from 'rxjs/operators';
// import { fromPromise } from 'rxjs/observable/fromPromise';

import { NG2HttpClient } from '../../shared/NG2HttpClient';
import * as Q from 'q';
import { Config, Query, QueryParameters, KGService } from 'geoplatform.client';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';




let CACHED_CONCEPTS : any = { };



export class SemanticCodec implements Codec {

    private param : string = "concepts";
    private service : KGService;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.service = new KGService(Config.ualUrl, client);
    }

    getKey() : string { return this.param; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        // let constraint : Constraint = null;
        // let value = params[this.param];
        // if(value) constraint = this.toConstraint(value.split(','));
        // if(constraints && constraint) constraints.set(constraint);
        // return constraint;

        let constraint : Constraint = null;
        if(params && params[this.param]) {
            let uris = params[this.param].split(',');
            if(uris && uris.length) {
                //have to get theme objects for uris provided
                this.resolveItems(uris).then( concepts => {
                    constraint = new MultiValueConstraint(this.param, concepts, "Concepts");
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;



    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(this.param);
        if(constraint && constraint.value.length)
            params[this.param] = constraint.value.map(v=>v.uri||v).join(',');
        else delete params[this.param];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(this.param);
        if(constraint && constraint.value)
            return constraint.value.slice(0);
        return null;
    }

    toString(constraints: Constraints) : string {
        return (this.getValue(constraints) || []).map(v=>v.uri||v).join(', ');
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let concepts = value as [{uri:string}];
        return new MultiValueConstraint(this.param, concepts, "Concepts");
    }

    resolveItems(uris) {

        //look at cache to see if concepts have been resolved already
        let cached = [];
        let needResolving = uris.filter(uri => {
            if(CACHED_CONCEPTS[uri]) {
                cached.push(CACHED_CONCEPTS[uri]);
                return false;
            }
            return true;
        });

        if(!needResolving.length) return Q.resolve(cached);

        let query = { uri: needResolving.join(',') }
        return this.service.suggest(query)
        .then( response => {
            //cached the ones we had to resolve
            response.results.forEach(hit => {
                CACHED_CONCEPTS[hit.uri] = hit;
            });
            return cached.concat(response.results);
        })
        .catch( e => console.log("An error occurred: " + e.message) )
    }
}
