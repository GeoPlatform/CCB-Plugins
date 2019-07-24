import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from '@geoplatform/client';
import { NG2HttpClient } from "@geoplatform/client/angular";

// import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { SimilarityCodec } from './codec';


@Component({
  selector: 'constraints-similarity',
  templateUrl: './similarity.component.html',
  styleUrls: ['./similarity.component.css']
})
export class SimilarityComponent implements OnInit {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : string;
    private codec : SimilarityCodec;

    constructor(private http : HttpClient) { }

    ngOnInit() {
        this.codec = new SimilarityCodec(this.http);
        this.value = this.codec.getValue(this.constraints);
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let constraint = this.codec.toConstraint(this.value);
        this.constraints.set(constraint);
    }


}
