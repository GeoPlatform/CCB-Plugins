import { Component, OnInit, Input, Output, EventEmitter, OnChanges, SimpleChanges } from '@angular/core';

import { QueryParameters } from 'geoplatform.client';

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
        // if(this.constraints) {
        //     let constraint = this.constraints.get(QueryParameters.KEYWORDS);
        //     if(constraint) {
        //         this.value = (constraint.values||[]).join(', ');
        //     }
        // }
    }

    getCodec() : Codec { return this.codec; }


    apply() {
        let constraint = this.codec.toConstraint(this.value);

        this.constraints.set(constraint);

        // let keywords : any = this.value;
        // if(keywords) {
        //     keywords = keywords.split(',').map(k=>k.trim());
        // }
        // let constraint = new Constraint(QueryParameters.KEYWORDS, keywords, "Keywords");
        // this.onConstraintEvent.emit(constraint);
    }

}
