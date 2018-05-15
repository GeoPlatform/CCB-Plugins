
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export class CreatorCodec implements Codec {

    constructor() {
    }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let createdBy = params.createdBy;
        if(createdBy) {
            constraint = new Constraint(QueryParameters.CREATED_BY, createdBy, "Creator");
            if(constraints) {
                constraints.set(constraint);
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.CREATED_BY);
        if(constraint) params['createdBy'] = constraint.value;
        else delete params['createdBy'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.CREATED_BY);
        return (constraint) ? constraint.value : null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        return new Constraint(QueryParameters.CREATED_BY, value, "Creator");
    }

}
