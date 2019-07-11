import {
    Component, OnInit, OnChanges,
    Input, Output, EventEmitter, SimpleChanges 
} from '@angular/core';

import { QueryParameters } from '@geoplatform/client';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { KeywordCodec } from './codec';

@Component({
  selector: 'constraint-keywords',
  templateUrl: './keywords.component.html',
  styleUrls: ['./keywords.component.css']
})
export class KeywordsComponent implements OnInit, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : string;
    private codec : KeywordCodec = new KeywordCodec();

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
