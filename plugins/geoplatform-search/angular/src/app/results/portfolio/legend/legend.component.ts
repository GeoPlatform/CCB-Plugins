import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from 'geoplatform.client';

import { environment } from '../../../../environments/environment';


@Component({
  selector: 'portfolio-legend',
  templateUrl: './legend.component.html',
  styleUrls: ['./legend.component.less']
})
export class LegendComponent implements OnInit {

    @Input() public isCollapsed: boolean = true;
    public types : { id: string; label: string; icon: string; }[];
    // public isCollapsed: boolean = true;

    constructor() {

    }

    ngOnInit() {
        this.types = Object.keys(ItemTypes).map(t=> {
            let type = ItemTypes[t], label = type, icon = this.getIconPath(type);
            if(~label.indexOf(":")) label = label.split(':')[1];
            if("VCard" === label) label = 'Contact';
            return { id: type, label: label, icon: icon };
        });
    }

    getIconPath(type) {
        let result = "dataset";
        switch(type) {
            case ItemTypes.DATASET:         result =  'dataset'; break;
            case ItemTypes.SERVICE:         result =  'service'; break;
            case ItemTypes.LAYER:           result =  'layer'; break;
            case ItemTypes.MAP:             result =  'map'; break;
            case ItemTypes.GALLERY:         result =  'gallery'; break;
            case ItemTypes.ORGANIZATION:    result =  'organization'; break;
            case ItemTypes.CONTACT:         result =  'vcard'; break;
            case ItemTypes.COMMUNITY:       result =  'community'; break;
            case ItemTypes.CONCEPT:         result =  'concept'; break;
            case ItemTypes.CONCEPT_SCHEME:  result =  'conceptscheme'; break;
        }
        return `../${environment.assets}${result}.svg`;
    }

    getIconClass(typeName) {
        let type = "dataset";
        switch(typeName) {
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
        }
        return 'icon-' + type;
    }

    toggle () {
        this.isCollapsed = !this.isCollapsed;
    }
}
