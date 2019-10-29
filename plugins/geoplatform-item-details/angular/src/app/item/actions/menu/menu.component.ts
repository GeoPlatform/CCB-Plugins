import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, Config, ItemService } from "@geoplatform/client";
import { ItemHelper } from '../../../shared/item-helper';


@Component({
  selector: 'gpid-action-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.less']
})
export class ActionMenuComponent implements OnInit {

    @Input() item : any;
    @Input() service : ItemService;

    public isCollapsed : boolean = true;

    constructor() {}

    ngOnInit() {}

    isAsset() : boolean {
        return ItemHelper.isAsset(this.item);
    }

    toggleMenu() {
        this.isCollapsed = !this.isCollapsed;
    }

}
