import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { Config, Query, QueryParameters, ItemService } from 'geoplatform.client';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';

export class SimilarityCodec implements Codec {

    private service : ItemService;
    private key : string = 'similarTo';

    constructor(private http : HttpClient) {
        this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
    }

    getKey() : string { return this.key; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        if(params && params.similarTo) {
            let ids = params.similarTo.split(',');
            if(ids && ids.length) {
                //have to get theme objects for ids provided
                this.resolveItems(ids).then( similarTo => {
                    constraint = new MultiValueConstraint(this.key, similarTo, "Similar To");
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(this.key);
        if(constraint && constraint.value.length)
            params[this.key] = constraint.value.map(v=>v.id).join(',');
        else delete params[this.key];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(this.key);
        if(constraint) {
            return (constraint.value||[]).slice(0);
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let similarTo = value as [{id:string}];
        return new MultiValueConstraint(this.key, similarTo, "Similar To");
    }


    resolveItems(ids) {
        return this.service.getMultiple(ids)
        .catch( e => console.log("An error occurred: " + e.message) )
    }

}
