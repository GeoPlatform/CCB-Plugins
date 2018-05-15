
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export class KeywordCodec implements Codec {

    constructor() {
    }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let keywords = params.keywords||params.keyword;
        if(keywords) constraint = this.toConstraint(keywords);
        if(constraints && constraint) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.KEYWORDS);
        if(constraint && constraint.value.length)
            params['keywords'] = constraint.value.join(',');
        else delete params['keywords'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.KEYWORDS);
        if(constraint) {
            return (constraint.value||[]).join(', ');
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        let keywords : any = value ? value.split(',').map(k=>k.trim()) : null;
        return new MultiValueConstraint(QueryParameters.KEYWORDS, keywords, "Keywords");
    }

}
