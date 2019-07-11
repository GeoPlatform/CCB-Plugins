import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { Config, Query, QueryParameters, ItemService } from '@geoplatform/client';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { itemServiceFactory } from '../../shared/service.provider';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';

export class ThemeCodec implements Codec {

    private service : ItemService;

    constructor(private http : HttpClient) {
        this.service = itemServiceFactory(http);
        // this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
    }

    getKey() : string { return QueryParameters.THEMES_ID; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        if(params && params.themes) {
            let ids = params.themes.split(',');
            if(ids && ids.length) {
                //have to get theme objects for ids provided
                this.resolveItems(ids).then( themes => {
                    constraint = new MultiValueConstraint(QueryParameters.THEMES_ID, themes, "Themes");
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.THEMES_ID);
        if(constraint && constraint.value.length)
            params['themes'] = constraint.value.map(v=>v.id).join(',');
        else delete params['themes'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.THEMES_ID);
        if(constraint) {
            return constraint.value||[];
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let themes = value as [{id:string}];
        return new MultiValueConstraint(QueryParameters.THEMES_ID, themes, "Themes");
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
