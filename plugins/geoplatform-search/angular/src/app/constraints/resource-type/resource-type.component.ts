import {
    Component,
    OnInit, OnChanges, OnDestroy,
    Input, Output,
    EventEmitter,
    SimpleChanges
} from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { ItemTypes, ItemTypeLabels } from '@geoplatform/client';

import { ISubscription } from "rxjs/Subscription";

import {
    Constraint, Constraints, ConstraintEditor, Facet, FacetCount
} from '../../models/constraint';
import { Codec } from '../../models/codec';

import { ResourceTypeCodec } from './codec';

// import { ServerRoutes } from '../../server-routes.enum'
import { environment } from '../../../environments/environment';

@Component({
    selector: 'constraint-resource-type',
    templateUrl: './resource-type.component.html',
    styleUrls: ['./resource-type.component.css']
})
export class ResourceTypeComponent implements OnInit, OnChanges, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : {label:string; uri: string; id:string;}[];
    public ITEM_TYPES : string[];
    public ITEM_TYPE_LABELS : any = ItemTypeLabels;
    private codec : ResourceTypeCodec;
    private facetListener : ISubscription;

    public isExpanded : {[key:string]:boolean} = {};

    constructor(http: HttpClient) {
        this.codec = new ResourceTypeCodec(http);

        this.buildItemTypesList();
    }

    buildItemTypesList() {
        this.ITEM_TYPES = Object.keys(ItemTypes).map(k=>ItemTypes[k])
        .filter( v=> {
            return v !== ItemTypes.STANDARD &&
                   v !== ItemTypes.RIGHTS_STATEMENT &&
                   v !== ItemTypes.CONCEPT &&
                   v !== ItemTypes.CONCEPT_SCHEME &&
                   v !== ItemTypes.ORGANIZATION &&
                   v !== ItemTypes.PERSON &&
                   v !== ItemTypes.CONTACT;
        });
    }

    ngOnInit() {
        this.value = this.codec.getValue(this.constraints);

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
        ~this.value.findIndex(v=>v.uri===type.uri);
    }

    select(type) {
        if(!this.value || !this.value.length) {
            this.value = [type];
            return;
        }

        let idx = this.value.findIndex(v=>v.uri===type.uri);
        if(idx>=0) {
            this.value.splice(idx,1);
        } else {
            this.value.push(type);
        }
    }

    getIconClass(itemType) {
        let type = itemType.replace(/^[a-z]+\:/i, '').toLowerCase();
        return 'icon-' + type;
    }

    getItemTypeLabel(itemType) {
        return ItemTypeLabels[itemType] || "Unknown Type " + itemType;
    }

    updateFacetCounts( facets : Facet[] ) {
        // if(facets && facets.length) {
        //     facets.forEach( (facet : Facet) => {
        //         if(facet.name !== 'types' || !facet.buckets) return;
        //         facet.buckets.forEach( bucket => {
        //             this.updateCount(bucket.key, bucket.count);
        //         });
        //     });
        // }
    }

    updateCount( key: string, count: number) {
        // this.getCodec().getOptions().forEach( o => {
        //     if(o.key === key) o.count = count;
        // });
    }

}
