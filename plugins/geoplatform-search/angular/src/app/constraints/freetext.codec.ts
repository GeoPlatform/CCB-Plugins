import { Params } from '@angular/router';
import { QueryParameters } from "geoplatform.client";

import { Constraint, Constraints } from '../models/constraint';
import { Codec } from '../models/codec';


/**
 *
 */
export class FreeTextCodec implements Codec {

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let text = params.q;
        if(text) constraint = this.toConstraint(text);
        if(constraints && constraint) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.QUERY);
        if(constraint) params['q'] = constraint.value;
        else delete params['q'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.QUERY);
        return constraint ? constraint.value : null;
    }

    toConstraint(value : any) : Constraint {
        return new Constraint(QueryParameters.QUERY, value, "Free Text");
    }
}
