import { Component, OnInit, OnDestroy } from '@angular/core';
import { Config, Query, ItemTypes, ItemService } from 'geoplatform.client';

import { itemServiceProvider } from './shared/service.provider';
import { DataProvider } from './shared/data.provider';
import { NG2HttpClient } from './shared/http-client';
import { ItemHelper } from './shared/item-helper';
import { AuthenticatedComponent } from './shared/authenticated.component';



@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.less'],
  providers: [ itemServiceProvider ]
})
export class AppComponent extends AuthenticatedComponent implements OnInit, OnDestroy {

    public error : Error;
    public item : any;
    public data : DataProvider;

    constructor( private itemService : ItemService ) {
        super();
        this.data = new DataProvider(itemService);
    }

    ngOnInit() {
        super.ngOnInit();

        let searchParams: string = window.location.search;
        if(!searchParams) {
            this.error = new Error("No parameters provided. Must specify an id " +
                "of a GeoPlatform item to preview");
            return;
        }

        let params : { [key:string]:any } = {} as { [key:string]:any };
        searchParams.replace('?','').split('&').forEach(p => {
            let param = p.split('='), key = param[0], value = param[1];
            params[key] = value;
        });

        if(!params.id) {
            this.error = new Error("Must specify an id " +
                "of a GeoPlatform item to preview");
            return;
        }

        this.itemService.get(params.id)
        .then( (item : any) => {
            this.item = item;
            this.processItem(this.item);    //process item into data...
        })
        .catch( (error : Error) => {
            this.error = error;
        });
    }

    ngOnDestroy() {
        super.ngOnDestroy();
        this.itemService = null;
        this.item = null;
        this.error = null;
    }

    onUserChange(user) {
        console.log("User auth status has changed!: " + user);
    }


    /**
     *
     */
    processItem(item : any) {

        if(!item) {
            this.error = new Error("Nothing exists to be processed into renderables");
            return;
        }

        let type = item.type;
        if(!type) {
            this.error = new Error("Requested item has no type and therefore cannot be previewed");
            return;
        }

        try {
            this.data.processItem(item);
        } catch(e) {
            this.error = e;
        }

    }



    getTypeKey() {
        return ItemHelper.getTypeKey(this.item);
    }
}
