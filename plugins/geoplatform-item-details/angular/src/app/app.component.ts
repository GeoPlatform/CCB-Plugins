import { Component, OnInit, OnDestroy, ElementRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { Config, ItemService } from "geoplatform.client";
import { RPMService } from 'geoplatform.rpm/src/iRPMService'

import { ItemHelper } from './shared/item-helper';
import { ItemDetailsError } from './shared/item-details-error';
import { NG2HttpClient } from "./shared/http-client";
import { environment } from '../environments/environment';
import { itemServiceProvider } from './shared/service.provider';
import { PluginAuthService } from './shared/auth.service';


const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)/i;


@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css'],
    providers: [itemServiceProvider]
})
export class AppComponent implements OnInit, OnDestroy {

    public item : any;
    public error : ItemDetailsError;
    private template: any;

    constructor(
        private el: ElementRef,
        private itemService : ItemService,
        private rpm: RPMService,
        private authService : PluginAuthService
    ) {

    }

    ngOnInit() {

        // console.log("App.init() " + window.location.href);
        // if(this.item) return;

        let match = this.parseURL();
        if(!match || !match.id) {
            this.handleError({
                label: "Malformed URL",
                message: "The URL you provided is not a valid GeoPlatform resource identifier"
            });
            return;
        }

        //fetch item using itemService
        this.itemService.get(match.id)
        .then( item => {
            this.item = item;
            const TYPE = ItemHelper.getTypeLabel(item);
            this.updatePageTitle(
                `GeoPlatform Resource : ${TYPE}`
                // ItemHelper.getLabel(this.item)
            );
            ;
            this.rpm.logEvent(TYPE, 'Viewed', item.id);
        })
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
        this.authService.dispose();
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

    /**
     * @param {string} title - value to assign to page title
     */
    updatePageTitle( title : string ) {
        if(!title || !title.length) return;
        let document = this.el.nativeElement.ownerDocument;
        if(document) {
            let titleEls = document.getElementsByClassName('a-page__title');
            if(titleEls.length) {
                titleEls[0].innerHTML = title;
            }
        }
    }

    /**
     * @param {Error} e
     */
    handleNotFound(e : Error) {
        this.handleError({
            label: "Item Not Found",
            message: "Could not find a resource with the specified identifier"
        });
    }

    /**
     * @param {Error} e
     */
    handleUnauthorized(e : Error) {
        this.handleError({
            label: "Unauthorized",
            message: "You do not have authorization to view the requested resource"
        });
    }

    /**
     * @param {Error} e
     */
    handleError(e : any) {
        this.error = new ItemDetailsError(e.message);
        this.error.label = e.label || e.error || "An Error Occurred";
    }

}
