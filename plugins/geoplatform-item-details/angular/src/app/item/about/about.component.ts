import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';

import { ItemTypes, ItemService } from 'geoplatform.client';

import { ItemHelper } from '../../shared/item-helper';
import { itemServiceProvider } from '../../shared/service.provider';

@Component({
  selector: 'gpid-about',
  templateUrl: './about.component.html',
  styleUrls: ['./about.component.less'],
  providers: [itemServiceProvider]
})
export class AboutComponent implements OnInit {

    @Input() item : any;
    public clonedFrom : any;

    constructor(private itemService : ItemService) { }

    ngOnInit() {

    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes && changes.item) {
            this.fetchClonedFrom(changes.item.currentValue);
        }
    }

    fetchClonedFrom(item) {
        if(item && item._cloneOf) {
            this.itemService.get(item._cloneOf)
            .then( (source : any) => { this.clonedFrom = source; })
            .catch( e => {

            });
        }
    }

    isAsset() {
        return this.item && ItemHelper.isAsset(this.item);
    }

    getCloneLink() {
        if(!this.clonedFrom) return '';
        return '/resources/' +
            ItemHelper.getTypeKey(this.clonedFrom.type) +
            '/' + this.clonedFrom.id;
    }
}
