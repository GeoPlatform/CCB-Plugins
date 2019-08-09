import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from '@geoplatform/client';
import { NG2HttpClient } from "@geoplatform/client/angular";

// import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { PublisherCodec } from './codec';

import { ItemListConstraint } from '../list';

@Component({
  selector: 'constraint-publishers',
  templateUrl: './publisher.component.html',
  styleUrls: ['./publisher.component.css']
})
export class PublisherComponent extends ItemListConstraint
implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;

    constructor(ngZone: NgZone, http : HttpClient) {
        super(ngZone, http);
    }

    ngOnInit() {
        this.types = [ItemTypes.ORGANIZATION];
        this.codec = new PublisherCodec(this.http);
        this.initialize(this.constraints);
    }

    ngOnDestroy() {
        this.destroy();
    }

    getCodec() : Codec { return this.codec; }

    /**
     * Override or extend the query used in the super-class
     */
    configureQuery(query : Query) {
        query.sort('_score,desc');
    }


    apply() {
        super.apply(this.constraints);
    }


    getPageStart() {
        return this.listQuery.getPage() * this.listQuery.getPageSize()+1;
    }
    getPageEnd() {
        return Math.min(
            this.listQuery.getPage() * this.listQuery.getPageSize() + this.listQuery.getPageSize(),
            this.totalResults
        );
    }
    hasNext() {
        return this.totalResults > this.getPageEnd();
    }
}
