
import { Params } from '@angular/router';
import { Query, QueryParameters } from '@geoplatform/client';
import { Constraint, CompoundConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';


export const Constants = {
    KEY: "temporal",
    BEGINS: "begins",
    ENDS: "ends"
};

export class TemporalCodec implements Codec {

    constructor() { }

    getKey() : string { return Constants.KEY; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let start = params[Constants.BEGINS];
        let end = params[Constants.ENDS];
        let value = start || end ? {} : null;
        if(start) value[Constants.BEGINS] = start;
        if(end) value[Constants.ENDS] = end;
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

        return new CompoundConstraint(Constants.KEY, [start,end], "Date Range");

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
