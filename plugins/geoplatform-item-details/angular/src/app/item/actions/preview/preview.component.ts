import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from 'geoplatform.client';
import { ItemHelper } from '../../../shared/item-helper';

@Component({
  selector: 'gpid-preview-action',
  templateUrl: './preview.component.html',
  styleUrls: ['./preview.component.less']
})
export class PreviewActionComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

    isSupported() {
        return this.item && (
            ItemTypes.DATASET === this.item.type ||
            ItemTypes.SERVICE === this.item.type ||
            ItemTypes.LAYER === this.item.type
        );
    }

    getHref() {
        let type = ItemHelper.getTypeKey(this.item);
        let url = '/resources/' + type + '/' + this.item.id + '/map';
        return url;
    }

}
