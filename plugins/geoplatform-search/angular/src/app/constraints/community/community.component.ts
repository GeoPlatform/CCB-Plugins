import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from 'geoplatform.client';

import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { CommunityCodec } from './codec';

import { ItemListConstraint } from '../list';


@Component({
  selector: 'constraint-communities',
  templateUrl: './community.component.html',
  styleUrls: ['./community.component.css']
})
export class CommunityComponent extends ItemListConstraint
implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;

    constructor(ngZone: NgZone, http : HttpClient) {
        super(ngZone, http);
    }

    ngOnInit() {
        this.types = [ItemTypes.COMMUNITY];
        this.codec = new CommunityCodec(this.service);
        this.initialize(this.constraints);
    }

    ngOnDestroy() {
        this.destroy();
    }

    /**
     * Override or extend the query used in the super-class
     */
    configureQuery(query : Query) {
        query.addField('title');
    }

    getCodec() : Codec { return this.codec; }

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
