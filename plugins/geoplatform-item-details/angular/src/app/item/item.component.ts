import {
    Component, OnInit, OnChanges, SimpleChanges, OnDestroy,
    Input, ElementRef
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { ItemHelper } from '../shared/item-helper';
import { NG2HttpClient } from "../shared/http-client";
import { AuthenticatedComponent} from '../shared/authenticated.component';
import { itemServiceProvider } from '../shared/service.provider';

const MAX_DESC_LENGTH = 550;



@Component({
  selector: 'gpid-item',
  templateUrl: './item.component.html',
  styleUrls: ['./item.component.less'],
  providers: [itemServiceProvider]
})
export class ItemComponent extends AuthenticatedComponent implements OnInit {

    @Input() item : any;

    public hasLongDescription : boolean = false;
    public descriptionCollapsed: boolean = true;
    public TYPES = ItemTypes;

    constructor(private el: ElementRef, private itemService: ItemService) {
        super();
    }

    ngOnInit() {
        super.init();
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
        super.destroy();
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
        if(!this.isAuthenticated()) return false;
        let user = this.getUser();
        // if(!this.authService.isAuthenticated()) return false;
        // let user = this.authService.getUser();

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

    getTitle() {
        return ItemHelper.getLabel(this.item);
    }

}
