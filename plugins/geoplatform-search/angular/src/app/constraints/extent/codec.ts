
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export class ExtentCodec implements Codec {

    constructor() {
    }

    getKey() : string { return QueryParameters.EXTENT; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let bbox = params.bbox;
        if(bbox) {
            constraint = this.toConstraint(bbox);
            if(constraints) {
                constraints.set(constraint);
            }
        }
        let place = params.place;
        if(place) {
            //TODO get coordinates for placename
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.EXTENT);
        if(constraint) params['bbox'] = constraint.value;
        else delete params['bbox'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.EXTENT);
        return (constraint) ? constraint.value : null;
    }

    toConstraint(value : any) : Constraint {
        return new Constraint(QueryParameters.EXTENT, value, "Geographic Extent");
    }

}
