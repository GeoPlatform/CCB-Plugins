import { Inject, Component, OnInit, OnDestroy } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Config, Query, ItemTypes, ItemService } from '@geoplatform/client';
import { NG2HttpClient } from '@geoplatform/client/angular';

import { itemServiceFactory } from './shared/service.provider';
import { DataProvider } from './shared/data.provider';
// import { NG2HttpClient } from './shared/http-client';
import { ItemHelper } from './shared/item-helper';
import { AuthenticatedComponent } from './shared/authenticated.component';
import { PluginAuthService } from './shared/auth.service';

const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)\/map/i;



@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.less']
})
export class AppComponent extends AuthenticatedComponent implements OnInit, OnDestroy {

    public error : Error;
    public item : any;
    public data : DataProvider;
    private _authService : PluginAuthService;
    private itemService : ItemService;

    constructor(
        authService : PluginAuthService,
        @Inject(ItemService) itemService : ItemService
    ) {
        super(authService);
        this.itemService = itemService;
        this._authService = authService;
        this.data = new DataProvider(this.itemService);
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
        this._authService.dispose();
        this._authService = null;
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



    // onUserChange(user) {
    //     console.log("User auth status has changed!: " + user);
    // }


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
