import { Component, OnInit, OnDestroy } from '@angular/core';
import { Config, Query, ItemTypes, ItemService } from 'geoplatform.client';

import { itemServiceProvider } from './shared/service.provider';
import { DataProvider } from './shared/data.provider';
import { NG2HttpClient } from './shared/http-client';
import { ItemHelper } from './shared/item-helper';
import { AuthenticatedComponent } from './shared/authenticated.component';


const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)\/map/i;



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

        let match = this.parseURL();
        if(!match || !match.id) {
            this.error = new Error(
                "The URL provided did not contain a valid GeoPlatform resource identifier"
            );
            return;
        }

        this.itemService.get(match.id)
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


    parseURL() : { id: string, type: string } {

        let url = window.location.href;
        let matches = URL_REGEX.exec(url);
        if(matches && matches.length) {
            let type = matches[1];
            // console.log(`TYPE: ${type}`);
            let id = matches[2];
            // console.log(`ID: ${id}`);

            return { id:id, type:type };
        }

        return null;
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