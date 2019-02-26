import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { ItemHelper } from '../../../shared/item-helper';


@Component({
  selector: 'gpid-like-action',
  templateUrl: './like-action.component.html',
  styleUrls: ['./like-action.component.less']
})
export class LikeActionComponent implements OnInit {

    @Input() item : any;
    @Input() service : ItemService;

    constructor() {
    }

    ngOnInit() {
    }

    doAction() {
        if(!this.item || !this.item.id || !this.service) return;

        this.service.like(this.item)
        .then( (updatedItem) => {
            if(updatedItem.statistics) {
                this.item.statistics.numLikes = updatedItem.statistics.numLikes || 0;
            }
        })
        .catch( e => {
            console.log("Error liking the resource: " + e.message);
        })
    }

}
