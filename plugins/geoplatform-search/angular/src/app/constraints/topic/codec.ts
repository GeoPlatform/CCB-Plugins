import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { Config, Query, QueryParameters, ItemService } from 'geoplatform.client';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { itemServiceFactory } from '../../shared/service.provider';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';

export class TopicCodec implements Codec {

    private service : ItemService;

    constructor(private http : HttpClient) {
        this.service = itemServiceFactory(http);
        // this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
    }

    getKey() : string { return QueryParameters.TOPICS_ID; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        if(params && params.topics) {
            let ids = params.topics.split(',');
            if(ids && ids.length) {
                //have to get topic objects for ids provided
                this.resolveItems(ids).then( topics => {
                    constraint = new MultiValueConstraint(QueryParameters.TOPICS_ID, topics, "Topics");
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.TOPICS_ID);
        if(constraint && constraint.value.length)
            params['topics'] = constraint.value.map(v=>v.id).join(',');
        else delete params['topics'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.TOPICS_ID);
        if(constraint) {
            return constraint.value||[];
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let topics = value as [{id:string}];
        return new MultiValueConstraint(QueryParameters.TOPICS_ID, topics, "Topics");
    }

    toString(constraints: Constraints) : string {
        let values = this.getValue(constraints);
        return values.map()
    }

    /**
     * @param {Array<string>} ids - one or more identifiers to fetch in one request
     * @return {Promise<Array<object>>} resolving the items or an exception
     */
    resolveItems(ids) {
        return this.service.getMultiple(ids)
        .catch( e => console.log("An error occurred: " + e.message) )
    }

}
