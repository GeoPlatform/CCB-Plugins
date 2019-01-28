import {
    Component, OnInit, OnChanges, SimpleChanges, OnDestroy,
    Input, ElementRef
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { ItemHelper } from '../shared/item-helper';
import { NG2HttpClient } from "../shared/http-client";


const MAX_DESC_LENGTH = 550;



@Component({
  selector: 'gpid-item',
  templateUrl: './item.component.html',
  styleUrls: ['./item.component.less']
})
export class ItemComponent implements OnInit {

    @Input() item : any;

    public hasLongDescription : boolean = false;
    public descriptionCollapsed: boolean = true;

    private itemService : ItemService;

    constructor(private el: ElementRef, http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {
        this.onResize();
    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.item) {
            //have to wrap in a timeout to let angular
            // do the rendering to the element in question
            // before we check for resize needs
            setTimeout(() => { this.onResize() }, 500);
        }
    }

    ngOnDestroy() {

    }

    onResize( ) {
        let descEl = this.el.nativeElement.getElementsByClassName("a-description__display");
        if(!descEl.length) return;
        let height = parseInt(descEl[0].offsetHeight);
        this.hasLongDescription = (height > 68);
    }

    toggleDesc() {
        this.descriptionCollapsed = !this.descriptionCollapsed;
    }

    isAuthorized(action : string) {
        //TODO use ng-gpoauth to see if user has credentials and
        // proper privileges for each supported action
        switch(action) {
        case 'edit' : return false;
        case 'delete': return false;
        default: return false;
        }
    }

    hasCoverage() {
        return this.item && (this.item.extent ||
            this.item.type === ItemTypes.MAP ||
            this.item.type === ItemTypes.LAYER);
    }

    isAsset() {
        return ItemHelper.isAsset(this.item);
    }

    getTypeKey() {
        return ItemHelper.getTypeKey(this.item);
    }

    likeItem() {
        this.itemService.like(this.item.id)
        .then( () => {
            //show something
        })
        .catch( e => {
            //show error message
        })
    }

}
