import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, Config, ItemService } from "@geoplatform/client";

import { ItemHelper } from '../../../shared/item-helper';


@Component({
  selector: 'gpid-like-action',
  templateUrl: './like-action.component.html',
  styleUrls: ['./like-action.component.less']
})
export class LikeActionComponent implements OnInit {

    @Input() item : any;
    @Input() service : ItemService;

    public processing : boolean = false;

    constructor() {
    }

    ngOnInit() {
    }

    doAction() {
        if(!this.item || !this.item.id || !this.service) return;

        this.processing = true;
        this.service.like(this.item)
        .then( (updatedItem) => {
            this.processing = false;
            if(updatedItem.statistics) {
                if(!this.item.statistics) {
                    this.item.statistics = updatedItem.statistics;
                } else {
                    this.item.statistics.numLikes = updatedItem.statistics.numLikes || 0;
                }
            }
        })
        .catch( e => {
            this.processing = false;
            console.log("Error liking the resource: " + e.message);
        })
    }

}
