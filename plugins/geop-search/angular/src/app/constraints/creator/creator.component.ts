import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { CreatorCodec } from './codec';

@Component({
  selector: 'constraint-creator',
  templateUrl: './creator.component.html',
  styleUrls: ['./creator.component.css']
})
export class CreatorComponent implements OnInit, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : string;
    private codec : CreatorCodec = new CreatorCodec();

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
