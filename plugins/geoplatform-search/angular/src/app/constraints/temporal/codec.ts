
import { Params } from '@angular/router';
import { Query, QueryParameters } from 'geoplatform.client';
import { Constraint, CompoundConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export const Constants = {
    KEY: "temporal",
    BEGINS: "begins",
    ENDS: "ends"
};

export class TemporalCodec implements Codec {

    constructor() { }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let value = {};
        value[Constants.BEGINS] = params[Constants.BEGINS];
        value[Constants.ENDS] = params[Constants.ENDS];
        let constraint = this.toConstraint(value);
        if(constraints) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {

        delete params[Constants.BEGINS];
        delete params[Constants.ENDS];

        let constraint = constraints.get(Constants.KEY);
        if(constraint) {
            if(constraint.value[0].value) {
                params[Constants.BEGINS] = this.formatDateStr(constraint.value[0].value);
            }
            if(constraint.value[1].value) {
                params[Constants.ENDS] = this.formatDateStr(constraint.value[1].value);
            }
        }
        // else {
        //     constraint = constraints.get(QueryParameters.BEGINS);
        //     if(constraint) params[Constants.BEGINS] = this.formatDateStr(constraint.value);
        //     else {
        //         constraint = constraints.get(QueryParameters.ENDS);
        //         if(constraint) params[Constants.ENDS] = this.formatDateStr(constraint.value);
        //     }
        // }
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;

        let constraint = constraints.get(Constants.KEY);
        if(constraint) {
            let value = {};
            if(constraint.value[0].value) {
                value[Constants.BEGINS] = this.formatDateStr(constraint.value[0].value);
            }
            if(constraint.value[1].value) {
                value[Constants.ENDS] = this.formatDateStr(constraint.value[1].value);
            }
            return value;
        }

        // constraint = constraints.get(QueryParameters.BEGINS);
        // if(constraint) {
        //     let value = {};
        //     value[Constants.BEGINS] = this.formatDateStr(constraint.value);
        //     return value;
        // }
        //
        // constraint = constraints.get(QueryParameters.ENDS);
        // if(constraint) {
        //     let value = {};
        //     value[Constants.ENDS] = this.formatDateStr(constraint.value);
        //     return value;
        // }

        return null;
    }

    toString(constraints: Constraints) : string {
        let result = '';
        let value = this.getValue(constraints); //will format timestamps as strings
        if(value && value[Constants.BEGINS])
            result += '<div>Beginning ' + value[Constants.BEGINS] + ' </div>';
        if(value && value[Constants.ENDS])
            result += '<div>Ending ' + value[Constants.ENDS] + '</div>';
        return result;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;

        let start = new Constraint(
            QueryParameters.BEGINS,
            value && value[Constants.BEGINS] ? Date.parse(value[Constants.BEGINS]) : null,
            "Begins"
        );
        let end = new Constraint(
            QueryParameters.ENDS,
            value && value[Constants.ENDS] ? Date.parse(value[Constants.ENDS]) : null,
            "Ends"
        );

        // if(start && end) {
        return new CompoundConstraint(Constants.KEY, [start,end], "Temporal Extent");
        // } else if(start) {
        //     return start;
        // } else if(end) {
        //     return end;
        // }
        // return null;
    }


    formatDateStr(dateValue) {
        let date = dateValue;
        if(!date) return '';

        if(typeof(date) === 'number') date = new Date(date);
        else if(typeof(date.toISOString()) === 'undefined') {
            date = Date.parse(date);
        }
        if(!date) return '';
        return date.toISOString().split('T')[0];
    }
}
