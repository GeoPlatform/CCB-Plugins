import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from 'geoplatform.client';

import { NG2HttpClient } from '../../shared/NG2HttpClient';

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
        this.codec = new ContactCodec(this.service);
        this.initialize(this.constraints);
    }

    ngOnDestroy() {
        this.destroy();
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        super.apply(this.constraints);
    }

}
