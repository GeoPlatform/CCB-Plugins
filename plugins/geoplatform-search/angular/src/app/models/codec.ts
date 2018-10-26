import { Params } from '@angular/router';
import { Constraint, Constraints } from '../models/constraint';

export interface Codec {

    getKey() : string;

    parseParams(params: Params, constraints?: Constraints) : Constraint;

    setParam(params: Params, constraints: Constraints);

    getValue(constraints: Constraints) : any;

    toConstraint(value : any) : Constraint;
}
