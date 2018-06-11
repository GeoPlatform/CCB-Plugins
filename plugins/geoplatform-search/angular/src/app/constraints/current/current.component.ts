import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { QueryParameters } from 'geoplatform.client';

import { Constraints, Constraint, MultiValueConstraint } from '../../models/constraint';


@Component({
  selector: 'constraints-current',
  templateUrl: './current.component.html',
  styleUrls: ['./current.component.css']
})
export class CurrentComponent implements OnInit {

    @Input() constraints : Constraints;

    public isClosed: boolean = false;

    constructor() { }

    ngOnInit() { }

    remove(constraint : Constraint) {
        this.constraints.unset(constraint);
    }

    removeValue(constraint : MultiValueConstraint, value : any) {
        this.constraints.removeValue(constraint, value);
    }

    clear() {
        this.constraints.clear();
    }

    isEmpty() {
        let constraints = this.constraints.list();
        if( !constraints.length ||
            (constraints.length === 1 && QueryParameters.QUERY === constraints[0].name)) {
            return true;
        }
        return false;
    }

    isMultiValue(constraint: Constraint) : boolean {
        return constraint instanceof MultiValueConstraint;
    }

    getValueDisplay(value : any, constraint:Constraint) : string {

        if(QueryParameters.EXTENT === constraint.name && value) {
            let bbox = value.split(',');
            return `<div>
                <div>North: ${bbox[3]}</div>
                <div>South: ${bbox[1]}</div>
                <div>East: ${bbox[2]}</div>
                <div>West: ${bbox[0]}</div>
                </div>`;
        }

        if(value) return value.label ? value.label : value;
        return '';
    }

}
