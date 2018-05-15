
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export class TypeCodec implements Codec {

    constructor() {
    }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let types = params.types;
        if(types) constraint = this.toConstraint(types.split(','));
        if(constraints && constraint) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.TYPES);
        if(constraint && constraint.value.length)
            params['types'] = constraint.value.join(',');
        else delete params['types'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.TYPES);
        if(constraint) {
            return (constraint.value||[]).join(', ');
        }
        return null;
    }

    toString(constraints: Constraints) : string {
        return this.getValue(constraints) || '';
    }

    toConstraint(value : any) : Constraint {
        return new MultiValueConstraint(QueryParameters.TYPES, value, "Types");
    }

}
