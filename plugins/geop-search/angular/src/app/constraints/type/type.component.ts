import { Component, OnInit, Input, Output, EventEmitter, OnChanges, SimpleChanges } from '@angular/core';

import { QueryParameters } from 'geoplatform.client';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { TypeCodec } from './codec';

@Component({
  selector: 'constraint-types',
  templateUrl: './type.component.html',
  styleUrls: ['./type.component.css']
})
export class TypeComponent implements OnInit, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : string;
    private codec : TypeCodec = new TypeCodec();

    constructor() { }

    ngOnInit() {
        this.value = this.codec.getValue(this.constraints);
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let constraint = this.codec.toConstraint(this.value);
        this.constraints.set(constraint);
    }


}
