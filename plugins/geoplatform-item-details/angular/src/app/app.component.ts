import {
    Inject, Component, OnInit, OnDestroy, ElementRef, Renderer2
} from '@angular/core';
import { Meta } from '@angular/platform-browser';
import { DOCUMENT } from "@angular/common";

import { Config, Item, ItemTypes, ItemService } from "@geoplatform/client";
import { NG2HttpClient } from '@geoplatform/client/angular';

import { RPMService } from '@geoplatform/rpm/src/iRPMService'

import { ItemHelper } from './shared/item-helper';
import { ItemDetailsError } from './shared/item-details-error';
import { environment } from '../environments/environment';
import { PluginAuthService } from './shared/auth.service';


const URL_REGEX = /resources\/([A-Za-z]+)\/([a-z0-9]+)/i;


@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit, OnDestroy {

    public item         : any;
    public error        : ItemDetailsError;
    private template    : any;

    constructor(
        private el          : ElementRef,
        private authService : PluginAuthService,
        private metaService : Meta,
        private renderer2   : Renderer2,
        @Inject(ItemService)
        private itemService : ItemService,
        @Inject(RPMService)
        private rpm         : RPMService,
        @Inject(DOCUMENT) private _document
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
            this.updatePageTitle(`GeoPlatform Resource : ${TYPE}`);
            // this.rpm.logEvent(TYPE, 'Viewed', item.id);

            this.addPageMetadata(item);
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


    /**
     * In order for web scrapers, such as Google's Dataset Search crawler, to
     * understand GP Portfolio resources, we must add some metadata to the HTML
     * that defines the object using schema.org syntax.
     */
    addPageMetadata(item : Item) {

        //Add identifier tag
        this.metaService.addTag({ name: 'DC.identifier', content: window.location.href, scheme: "DCTERMS.URI" });
        this.metaService.addTag({ name: 'DC.title',      content: item.label || item.prefLabel });
        this.metaService.addTag({ name: 'DC.creator',    content: item.createdBy });
        (item.publishers || []).forEach( pub => {
            this.metaService.addTag({ name: 'DC.publisher',  content: pub.label||pub.name });
        });

        let dcType = this.determineDCType(item);
        if(dcType) {
            this.metaService.addTag({ name: 'DC.type', content: dcType });
        }

        let dcDate = this.determineDCDate(item);
        if(dcDate) {
            this.metaService.addTag({ name: 'DC.date', content: dcDate, scheme: "DCTERMS.W3CDTF" });
        }



        // fetch JSON-LD representation of this item...
        this.itemService.get(item.id + '.jsonld')
        .then( json => {
            // ... and then write it into the page
            const jsonStr = json ? JSON.stringify(json, null, 2) : '';
            const scriptTag = this.renderer2.createElement('script');
            scriptTag.type = 'application/ld+json';
            scriptTag.text = jsonStr;
            this.renderer2.appendChild(this._document.body, scriptTag);
        })
        .catch( e => {
            console.log("Error fetching JSON-LD, " + e.message);
        });
    }

    /**
     * @return string Dublin Core type
     */
    determineDCType( item : Item ) {
        switch(item.type) {
            case ItemTypes.DATASET : return 'Dataset';
            case ItemTypes.SERVICE : return 'Service';
            case ItemTypes.GALLERY : return 'Collection';
            case ItemTypes.APPLICATION : return 'InteractiveResource';
            default : return null;
        }
    }

    determineDCDate( item : Item ) {
        let date = item.issued || item.created || item._created;
        if(date) {
            let dt = new Date(date);
            let year = dt.getUTCFullYear();
            let mnth : any = dt.getUTCMonth() + 1;
            if(mnth < 10) mnth = '0' + mnth;
            let day : any = dt.getUTCDate();
            if(day < 10) day = '0' + day;
            return year + '-' + mnth + '-' + day;
        }
        return null;
    }

}
