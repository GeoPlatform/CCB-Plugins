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
            let type = ItemTypes[t];
            if(ItemTypes.STANDARD === type) return null;
            let label = type, icon = this.getIconPath(type);
            if(~label.indexOf(":")) label = label.split(':')[1];
            if("VCard" === label) label = 'Contact';
            if("Product" === label) label = "Image Product";
            return { id: type, label: label, icon: icon };
        }).filter(t=>t!==null);
    }

    getIconPath(type) {
        let result = "dataset";
        switch(type) {
            case ItemTypes.CONTACT:         result =  'vcard'; break;
            case ItemTypes.IMAGE_PRODUCT:   result =  'imageproduct'; break;
            default: type = type.replace(/^[a-z]+\:/i, '').toLowerCase();
        }
        return `../${environment.assets}${result}.svg`;
    }

    getIconClass(typeName) {
        let type = "dataset";
        switch(typeName) {
            case ItemTypes.CONTACT:         type =  'vcard'; break;
            case ItemTypes.IMAGE_PRODUCT:   type =  'imageproduct'; break;
            default: type = typeName.replace(/^[a-z]+\:/i, '').toLowerCase();
        }
        return 'icon-' + type;
    }

    toggle () {
        this.isCollapsed = !this.isCollapsed;
    }
}
