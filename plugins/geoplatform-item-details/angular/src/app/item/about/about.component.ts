import { Component, OnInit, Input } from '@angular/core';

import { ItemTypes } from 'geoplatform.client';

@Component({
  selector: 'gpid-about',
  templateUrl: './about.component.html',
  styleUrls: ['./about.component.less']
})
export class AboutComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

    isAsset() {
        return this.item && (
            this.item.type === ItemTypes.DATASET ||
            this.item.type === ItemTypes.SERVICE ||
            this.item.type === ItemTypes.LAYER ||
            this.item.type === ItemTypes.MAP ||
            this.item.type === ItemTypes.GALLERY ||
            this.item.type === ItemTypes.COMMUNITY
        );
    }
}
