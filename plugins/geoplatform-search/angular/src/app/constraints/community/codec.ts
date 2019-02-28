import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { Config, Query, QueryParameters, ItemService } from 'geoplatform.client';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { itemServiceFactory } from '../../shared/service.provider';

export class CommunityCodec implements Codec {

    private service : ItemService;

    constructor(private http : HttpClient) {
        this.service = itemServiceFactory(http);
        // this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
    }

    getKey() : string { return QueryParameters.USED_BY_ID; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        if(params && params.communities) {
            let ids = params.communities.split(',');
            if(ids && ids.length) {
                //have to get theme objects for ids provided
                this.resolveItems(ids).then( communities => {
                    constraint = new MultiValueConstraint(QueryParameters.USED_BY_ID, communities, "Communities");
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.USED_BY_ID);
        if(constraint && constraint.value.length)
            params['communities'] = constraint.value.map(v=>v.id).join(',');
        else delete params['communities'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.USED_BY_ID);
        if(constraint) {
            return (constraint.value||[]).slice(0);
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let communities = value as [{id:string}];
        return new MultiValueConstraint(QueryParameters.USED_BY_ID, communities, "Communities");
    }


    resolveItems(ids) {
        return this.service.getMultiple(ids)
        .catch( e => console.log("An error occurred: " + e.message) )
    }

}
