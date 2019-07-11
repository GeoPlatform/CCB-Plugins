import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from '@geoplatform/client';
import { NG2HttpClient } from "@geoplatform/client/angular";

// import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';
import { ThemeCodec } from './codec';
import { ItemListConstraint } from '../list';

@Component({
  selector: 'constraint-themes',
  templateUrl: './theme.component.html',
  styleUrls: ['./theme.component.css']
})
export class ThemeComponent extends ItemListConstraint
implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;

    constructor(ngZone: NgZone, http : HttpClient) {
        super(ngZone, http);
    }

    ngOnInit() {
        this.types = [ItemTypes.CONCEPT];
        this.codec = new ThemeCodec(this.http);
        this.initialize(this.constraints);
    }

    ngOnDestroy() {
        this.destroy();
    }

    getCodec(): Codec { return this.codec; }

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

    /**
     * @override ItemListConstraint.prototype.configureQuery
     */
    configureQuery(query : Query) {
        //sort by relevance (DT-24187)
        query.sort("_score,desc");
        //and only find concepts that belong to concept schemes (DT-24187)
        query.setParameter("facet.inScheme.exists", true);
        //and make sure the concepts come back with their scheme info
        query.addField('scheme');
    }
}
