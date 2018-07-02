import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { Constants, TemporalCodec } from './codec';

@Component({
  selector: 'constraint-temporal',
  templateUrl: './temporal.component.html',
  styleUrls: ['./temporal.component.css']
})
export class TemporalComponent implements OnInit {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public startDate : string;
    public endDate : string;
    private codec : TemporalCodec = new TemporalCodec();

    constructor() { }

    ngOnInit() {
        let value = this.codec.getValue(this.constraints);
        if(value) {
            if(value.begins) {
                let d = new Date(value.begins);
                this.startDate = d.toISOString().split('T')[0];
            }
            if(value.ends) {
                let d = new Date(value.ends);
                this.endDate = d.toISOString().split('T')[0];
            }
        }
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let value = {};
        value[Constants.BEGINS] = this.startDate;
        value[Constants.ENDS] = this.endDate;
        let constraint = this.codec.toConstraint(value);
        this.constraints.set(constraint);
    }


}
