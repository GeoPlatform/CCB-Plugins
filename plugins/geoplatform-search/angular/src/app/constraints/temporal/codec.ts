
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, CompoundConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


const KEY = "temporal";
const BEGINS = "begins";
const ENDS = "ends";

export class TemporalCodec implements Codec {

    constructor() { }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let value = {};
        value[BEGINS] = params[BEGINS];
        value[ENDS] = params[ENDS];
        let constraint = this.toConstraint(value);
        if(constraints) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {

        delete params[BEGINS];
        delete params[ENDS];

        let constraint = constraints.get(KEY);
        if(constraint) {
            params[BEGINS] = constraint.value[0].value;
            params[ENDS] = constraint.value[1].value;
        } else {
            constraint = constraints.get(QueryParameters.BEGINS);
            if(constraint) params[BEGINS] = constraint.value;
            else {
                constraint = constraints.get(QueryParameters.ENDS);
                if(constraint) params[ENDS] = constraint.value;
            }
        }
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;

        let constraint = constraints.get(KEY);
        if(constraint) {
            let value = {};
            value[BEGINS] = constraint.value[0].value;
            value[ENDS] = constraint.value[1].value;
            return value;
        }

        constraint = constraints.get(QueryParameters.BEGINS);
        if(constraint) {
            let value = {};
            value[BEGINS] = constraint.value;
            return value;
        }

        constraint = constraints.get(QueryParameters.ENDS);
        if(constraint) {
            let value = {};
            value[ENDS] = constraint.value;
            return value;
        }

        return null;
    }

    toString(constraints: Constraints) : string {
        let result = '';
        let value = this.getValue(constraints);
        if(value && value[BEGINS])  result += 'Beginning ' + value[BEGINS] + ' ';
        if(value && value[ENDS])    result += 'Ending ' + value[ENDS];
        return result;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;

        let start = value && value[BEGINS] ?
            new Constraint(QueryParameters.BEGINS, value[BEGINS], "Begins") : null;
        let end = value && value[ENDS] ?
            new Constraint(QueryParameters.ENDS, value[ENDS], "Ends") : null;

        if(start && end) {
            return new CompoundConstraint(KEY, [start,end], "Temporal Extent");
        } else if(start) {
            return start;
        } else if(end) {
            return end;
        }

        return null;
    }

}
