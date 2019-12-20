import { Component, OnInit, Input } from '@angular/core';
import { Config, Item, ItemTypes } from "@geoplatform/client";

@Component({
    selector : 'gp-resource-link',
    template :
    `
    <a href="{{url}}" target="_blank">
        <span gpIcon [item]="item"></span>
        {{item.label}}
    </a>
    `,
    styles: []
})
export class ResourceLinkComponent implements OnInit {

    @Input() item : Item;
    public url : string = "";

    constructor() { }

    ngOnInit() {

        if(!this.item) return;
        let type = this.item.type;

        switch(type) {
            case ItemTypes.COMMUNITY:
                type = 'communities'; break;
            case ItemTypes.GALLERY:
                type = 'galleries'; break;
            default:
                type = type.toLowerCase().replace(/[a-z]+\:/, '') + 's';
        }

        let baseUrl = Config.portalUrl || Config.wpUrl;
        this.url = baseUrl + '/resources/' + type + '/' + this.item.id;
    }

}
