import { Component, OnInit, Input } from '@angular/core';

import { ItemTypes } from "geoplatform.client";

@Component({
  selector: 'gpid-resource-link',
  templateUrl: './resource-link.component.html',
  styleUrls: ['./resource-link.component.less']
})
export class ResourceLinkComponent implements OnInit {

    @Input() item : any;
    @Input() icon : any;
    @Input() external : boolean = false;    //open link in new window/tab

    constructor() { }

    ngOnInit() {
    }

    hasIcon() : boolean {
        // return this.icon !== null && this.icon !== undefined;
        return true;
    }

    getIcon() :string {
        // let result = null;
        // let iconType = typeof(this.icon);
        // if(iconType === 'boolean') {
            return this.determineIconType();
        // }
        // else if( iconType === 'string') return iconType;
    }

    getLabel() {
        if(!this.item || !this.item.type) return 'Unknown type';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.LAYER :
            case ItemTypes.MAP :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
                return this.item.label || this.item.title;

            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON :
                return this.item.label || this.item.name;

            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return this.item.label || this.item.prefLabel;


            case ItemTypes.CONTACT :
                return this.item.fullName +
                    ( this.item.orgName ? " (" + this.item.orgName + ")" : '');

            default: return 'Unknown type';
        }
    }

    getType() {
        if(!this.item || !this.item.type) return 'unsupported';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONTACT :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return type.split(':')[1].toLowerCase();

            case ItemTypes.LAYER :
            case ItemTypes.MAP :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
                return type.toLowerCase();

            default: return 'unsupported';
        }
    }

    determineIconType() {
        let path = '/assets/icons/';
        let name = 'dataset';
        if(this.item && this.item.type) {
            let type = this.item.type;
            switch(type) {
                case ItemTypes.DATASET :
                case ItemTypes.ORGANIZATION :
                case ItemTypes.CONTACT :
                case ItemTypes.PERSON :
                case ItemTypes.CONCEPT :
                case ItemTypes.CONCEPT_SCHEME :
                    name = type.split(':')[1].toLowerCase();
                    break;

                case ItemTypes.LAYER :
                case ItemTypes.MAP :
                case ItemTypes.GALLERY :
                case ItemTypes.COMMUNITY :
                    name = type.toLowerCase();
                    break;
            }
        }
        return path + name + '.svg';
    }

}
