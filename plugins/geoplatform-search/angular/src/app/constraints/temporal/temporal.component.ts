import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { NgbDateAdapter, NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { Constants, TemporalCodec } from './codec';





/**
 * Custom adapter to convert from NG Boostrap internal date struct into Native JS Date
 */
export class UTCDatepickerAdapter {

    fromModel(value: Date) : NgbDateStruct {
        if(!value || typeof(value.getUTCFullYear) === 'undefined') return null;
        return {
            year: value.getUTCFullYear(),
            month: value.getUTCMonth() + 1, //ngdp is index 1
            day: value.getUTCDate()
        };
    }

    toModel(date: NgbDateStruct) : Date {
        if(!date) return null;
        const jsDate = new Date(Date.UTC(date.year, date.month - 1, date.day));
        // avoid 30 -> 1930 conversion
        jsDate.setUTCFullYear(date.year);
        return jsDate;
    }

}









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
    public startDateMessage : string;
    public endDateMessage : string;
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

    validateStartDate($event) {
        if(!this.validateDate($event)) {
            this.startDateMessage = "Date must be in format 'yyyy-MM-dd'";
        } else this.startDateMessage = null;
    }

    validateEndDate($event) {
        if(!this.validateDate($event)) {
            this.endDateMessage = "Date must be in format 'yyyy-MM-dd'";
        } else this.endDateMessage = null;
    }

    validateDate($event) {
        let dateStr = $event.target.value;
        if(!dateStr || !dateStr.length) return true;

        let expr = /^\d{4}\-\d{2}\-\d{2}$/;
        return expr.test(dateStr);
    }


}
