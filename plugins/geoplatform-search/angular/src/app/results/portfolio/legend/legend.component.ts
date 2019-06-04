import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, ItemTypeLabels } from 'geoplatform.client';

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
            let label = ItemTypeLabels[type], icon = this.getIconClass(type);
            return { id: type, label: label, icon: icon };
        }).filter(t=>t!==null);
    }

    getIconClass(typeName) {
        let type = typeName.replace(/^[a-z]+\:/i, '').toLowerCase();
        return 'icon-' + type;
    }

    toggle () {
        this.isCollapsed = !this.isCollapsed;
    }
}
