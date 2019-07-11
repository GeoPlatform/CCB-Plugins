import {
    Component,
    OnInit, OnChanges, OnDestroy,
    Input, Output,
    EventEmitter,
    SimpleChanges
} from '@angular/core';

import { ItemTypes } from '@geoplatform/client';

import { ISubscription } from "rxjs/Subscription";

import {
    Constraint, Constraints, ConstraintEditor, Facet, FacetCount
} from '../../models/constraint';
import { Codec } from '../../models/codec';

import { TypeCodec } from './codec';

// import { ServerRoutes } from '../../server-routes.enum'
import { environment } from '../../../environments/environment';


@Component({
  selector: 'constraint-types',
  templateUrl: './type.component.html',
  styleUrls: ['./type.component.css']
})
export class TypeComponent implements OnInit, OnChanges, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : {label:string; id:string;}[];
    private codec : TypeCodec = new TypeCodec();
    public options : { key:string; label:string; id:string; count: number; }[];

    private facetListener : ISubscription;

    constructor() { }

    ngOnInit() {
        this.value = this.codec.getValue(this.constraints);
        this.options = this.codec.typeOptions;

        if(this.constraints) {
            this.updateFacetCounts(this.constraints.facets);
            this.facetListener = this.constraints.facetEvents.subscribe( (facets : Facet[]) => {
                this.updateFacetCounts(facets);
            });
        }
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.constraints) {
            let constraints = changes.constraints.currentValue;

            this.value = this.codec.getValue(constraints);

            //since constraints obj changed, unsubscribe and re-subscribe
            if(this.facetListener) this.facetListener.unsubscribe();
            this.facetListener = constraints.facetEvents.subscribe( (facets : Facet[]) => {
                this.updateFacetCounts(facets);
            });
        }
    }

    ngOnDestroy() {
        this.options = null;
        this.value = null;
        if(this.facetListener) this.facetListener.unsubscribe();
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let constraint = this.codec.toConstraint(this.value);
        if(!this.value || !this.value.length) {
            this.constraints.unset(constraint);
        } else {
            this.constraints.set(constraint);
        }
    }

    isSelected(type) {
        return this.value && this.value.length &&
            ~this.value.findIndex(v=>v.id===type.id);
    }

    select(type) {
        if(!this.value || !this.value.length) {
            this.value = [type];
            return;
        }

        let idx = this.value.findIndex(v=>v.id===type.id);
        if(idx>=0) {
            this.value.splice(idx,1);
        } else {
            this.value.push(type);
        }
    }

    getIconClass(option) {
        let type = option.id.replace(/^[a-z]+\:/i, '').toLowerCase();
        return 'icon-' + type;
    }

    updateFacetCounts( facets : Facet[] ) {
        if(facets && facets.length) {
            facets.forEach( (facet : Facet) => {
                if(facet.name !== 'types' || !facet.buckets) return;
                facet.buckets.forEach( bucket => {
                    this.updateCount(bucket.key, bucket.count);
                });
            });
        }
    }

    updateCount( key: string, count: number) {
        this.options.forEach( o => {
            if(o.key === key) o.count = count;
        });
    }
}
