import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from "geoplatform.client";

import { ItemHelper } from '../../shared/item-helper';

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

    getIcon() : string {
        return ItemHelper.getIcon(this.item);
    }

    getLabel() : string {
        return ItemHelper.getLabel(this.item);
    }

    getType() : string {
        return ItemHelper.getTypeKey(this.item);
    }

    getIconClass() : string {
        let type = ItemHelper.getTypeLabel(this.item);
        if("Contact" === type) type = 'vcard';
        if("Product" === type) type = 'imageproduct';
        return 'icon-' + type.toLowerCase();
    }

}
