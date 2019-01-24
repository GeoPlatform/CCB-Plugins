import {
    Component, OnInit, OnDestroy
} from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { Config, ItemService } from "geoplatform.client";

import { NG2HttpClient } from "./shared/http-client";
import { environment } from '../environments/environment';

// const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)/i;
const URL_REGEX = /resources\/([a-z0-9]+)/i;


@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent {

    private itemService : ItemService;
    private item : any;
    private template: any;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {

        let url = window.location.href;
        console.log(`URL: ${url}`);

        let matches = URL_REGEX.exec(url);

        if(matches && matches.length) {
            //for urls with 'resources/type/id'...
            // let type = matches[1];
            // console.log(`TYPE: ${type}`);
            // let id = matches[2];

            //for urls without 'type'
            let id = matches[1];
            // console.log(`ID: ${id}`);

            //fetch item using itemService
            this.itemService.get(id)
            .then( item => { this.item = item; })
            .catch( e => {
                //display error message indicating failure to load item
            })

            //load template based upon type...

        } else {
            //display error message indicating bad URL...
        }

    }

    ngOnDestroy() {

    }

}
