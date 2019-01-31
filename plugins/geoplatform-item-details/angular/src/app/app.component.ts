import { Component, OnInit, OnDestroy } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { Config, ItemService } from "geoplatform.client";

import { ItemDetailsError } from './shared/item-details-error';
import { NG2HttpClient } from "./shared/http-client";
import { environment } from '../environments/environment';

const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)/i;


@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent {

    private itemService : ItemService;
    public item : any;
    public error : ItemDetailsError;
    private template: any;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {

        let match = this.parseURL();
        if(!match || !match.id) {
            this.handleError({
                label: "Malformed URL",
                message: "The URL you provided is not a valid GeoPlatform resource identifier"
            });
        }

        //fetch item using itemService
        this.itemService.get(match.id)
        .then( item => { this.item = item; })
        .catch( e => {
            //display error message indicating failure to load item
            if(e.status && e.status === 404) {
                this.handleNotFound(e);
            } else if(e.status && e.status === 403) {
                this.handleUnauthorized(e);
            } else {
                this.handleError(e);
            }
        });

    }

    ngOnDestroy() {

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


    handleNotFound(e : Error) {
        this.handleError({
            label: "Item Not Found",
            message: "Could not find a resource with the specified identifier"
        });
    }

    handleUnauthorized(e : Error) {
        this.handleError({
            label: "Unauthorized",
            message: "You do not have authorization to view the requested resource"
        });
    }

    handleError(e : any) {
        this.error = new ItemDetailsError(e.message);
        this.error.label = e.label || e.error || "An Error Occurred";
    }

}
