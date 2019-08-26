import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from '@geoplatform/client';
import { NG2HttpClient } from "@geoplatform/client/angular";

// import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { ContactCodec } from './codec';

import { ItemListConstraint } from '../list';

@Component({
  selector: 'constraint-contacts',
  templateUrl: './contact.component.html',
  styleUrls: ['./contact.component.css']
})
export class ContactComponent extends ItemListConstraint
implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;

    constructor(ngZone: NgZone, http : HttpClient) {
        super(ngZone, http);
    }

    ngOnInit() {
        this.types = [ItemTypes.CONTACT];
        this.codec = new ContactCodec(this.http);
        this.initialize(this.constraints);
    }

    ngOnDestroy() {
        this.destroy();
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
