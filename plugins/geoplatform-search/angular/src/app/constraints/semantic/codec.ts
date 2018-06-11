
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export class SemanticCodec implements Codec {

    private param : string = "concepts";

    constructor() {

    }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let value = params[this.param];
        if(value) constraint = this.toConstraint(value.split(','));
        if(constraints && constraint) constraints.set(constraint);
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

    // resolveItems(uris) {
    //     return this.service.exists(uris)
    //     .then(response => )
    //     .catch( e => console.log("An error occurred: " + e.message) )
    // }
}
