import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { TemporalCodec } from './codec';

@Component({
  selector: 'constraint-temporal',
  templateUrl: './temporal.component.html',
  styleUrls: ['./temporal.component.css']
})
export class TemporalComponent implements OnInit {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public startDate : any;
    public endDate : any;
    private codec : TemporalCodec = new TemporalCodec();

    constructor() { }

    ngOnInit() {
        let value = this.codec.getValue(this.constraints);
        if(value) {
            this.startDate = value.startDate;
            this.endDate = value.endDate;
        }
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let constraint = this.codec.toConstraint({
            startDate: this.startDate, endDate: this.endDate
        });
        this.constraints.set(constraint);
    }

}
