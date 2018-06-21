import {
    Component,
    OnInit, OnChanges, OnDestroy,
    Input, Output,
    EventEmitter,
    SimpleChanges
} from '@angular/core';

import { ItemTypes } from 'geoplatform.client';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { TypeCodec } from './codec';

import { ServerRoutes } from '../../server-routes.enum'


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
    public options : {label:string; id:string;}[];

    constructor() { }

    ngOnInit() {
        this.value = this.codec.getValue(this.constraints);
        this.options = this.codec.typeOptions;
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.constraints) {
            this.value = this.codec.getValue(changes.constraints.currentValue);
        }
    }

    ngOnDestroy() {
        this.options = null;
        this.value = null;
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

    getIconPath(option) {
        let type = "dataset";
        switch(option.id) {
            case ItemTypes.DATASET:         type =  'dataset'; break;
            case ItemTypes.SERVICE:         type =  'service'; break;
            case ItemTypes.LAYER:           type =  'layer'; break;
            case ItemTypes.MAP:             type =  'map'; break;
            case ItemTypes.GALLERY:         type =  'gallery'; break;
            case ItemTypes.ORGANIZATION:    type =  'organization'; break;
            case ItemTypes.CONTACT:         type =  'vcard'; break;
            case ItemTypes.COMMUNITY:       type =  'community'; break;
            case ItemTypes.CONCEPT:         type =  'concept'; break;
            case ItemTypes.CONCEPT_SCHEME:  type =  'conceptscheme'; break;
            //WP types
            case 'pages':                   type =  'page'; break;
            case 'posts':                   type =  'post'; break;
            case 'media':                   type =  'attachment'; break;
        }
        return `${ServerRoutes.ASSETS}${type}.svg`;
    }

}
