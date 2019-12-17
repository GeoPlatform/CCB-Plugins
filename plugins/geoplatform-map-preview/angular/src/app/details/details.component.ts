import {
    Inject, Component, OnInit, OnDestroy, OnChanges, SimpleChanges,
    Input, Output, EventEmitter, HostBinding
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { MatDialog } from '@angular/material/dialog';
import { Subscription } from "rxjs";
import * as md5 from "md5";
import { Query, QueryParameters, Item, ItemService, ItemTypes, Map, URIFactory } from '@geoplatform/client';

const URIF = URIFactory(md5);

import { GeoPlatformUser } from '@geoplatform/oauth-ng/angular';

import {
    DataProvider, DataEvent, Events, Extent, LayerState
} from '../shared/data.provider';
import { AuthenticatedComponent } from '../shared/authenticated.component';
import { PluginAuthService } from '../shared/auth.service';
import { ListSelectDialog } from '../shared/dialogs';
import { environment } from '../../environments/environment';
import { logger } from "../shared/logger";


const GEOPLATFORM_MAP_TYPE = "http://www.geoplatform.gov/ont/openmap/GeoplatformMap";
const IS_DEV = 'development' === environment.env;


@Component({
  selector: 'gpmp-map-details',
  templateUrl: './details.component.html',
  styleUrls: ['./details.component.less']
})
export class DetailsComponent extends AuthenticatedComponent implements OnInit, OnDestroy {

    @Input() data : DataProvider;
    @Input() isCollapsed : boolean = false;
    @HostBinding('class.isCollapsed') hostCollapsed: boolean = false;

    public Params : any = QueryParameters;
    public isKeywordsCollapsed : boolean = true;
    public error : Error;
    public isSaving : boolean;

    public mapItem : Map = {
        id          : null,
        uri         : null,
        type        : ItemTypes.MAP,
        title       : "My New Map",
        description : "This map needs a description",
        keywords    : [],
        createdBy   : "tester",
        resourceTypes: [],
        layers      : [],
        themes      : [],
        topics      : [],
        publishers  : [],
        usedBy      : [],
        classifiers : {},
        extent      : {}
    };

    public keyword : string;

    private dataSubscription : Subscription;


    constructor(
        @Inject(ItemService) private itemService : ItemService,
        authService : PluginAuthService,
        private dialog ?: MatDialog
    ) {
        super(authService);
    }

    ngOnInit() {
        super.ngOnInit();

        this.itemService.getClient().setAuthToken( () => { return this.getAuthToken(); });

        if(this.data) {
            this.dataSubscription = this.data.subscribe( (event : DataEvent) => {
                this.onDataEvent(event);
            });
        }
    }

    ngOnChanges (changes : SimpleChanges) {
        if(changes.isCollapsed) {
            this.hostCollapsed = changes.isCollapsed.currentValue;
        }
    }

    ngOnDestroy() {
        super.ngOnDestroy();
        this.itemService = null;
        this.dataSubscription.unsubscribe();
        this.dataSubscription = null;
        this.keyword = null;
        this.mapItem = null;
    }

    onUserChange(user) {
        logger.debug("User has changed: " + (user?user.username:'N/A'));
        let token = null;
        this.mapItem.createdBy = user ? user.username : null;
    }

    /**
     *
     */
    createMap() {

        this.isSaving = true;

        if(!this.mapItem.createdBy) {
            logger.warn("User not authenticated, aborting create...");
            return;
        }

        Promise.resolve(this.getUser())
        .then( user => {
            // if(IS_DEV) {
            //     this.onUserChange(user);
            //     return Promise.resolve(null);
            // }
            // ensure token isn't in weird expired/revoked state
            return this.checkAuth().then( user => {
                this.onUserChange(user);
                if(!user) throw new Error("Not signed in");
            });
        })
        .then( () => {
            //then request a URI for the new map
            return URIF(this.mapItem);
        })
        .then(uri => {
            if(!uri) throw new Error("Unable to generate a URI for the new map");
            this.mapItem.uri = uri;
            this.mapItem.id = null;
            return this.mapItem;
        })
        .then( map => {
            //store selected layers onto map being created
            this.mapItem.layers = this.data.getSelected(true)
            .map( (layer: any) => {
                return {
                    layer: {
                        id: layer.id,
                        uri: layer.uri,
                        type: layer.type
                    },
                    layerId: layer.id,
                    visibility: this.data.isVisible(layer),
                    opacity: 1.0
                } as LayerState;
            });
            this.mapItem.baseLayer = this.data.getBaseLayer();

            // logger.debug("Saving Map as...");
            // logger.debug(JSON.stringify(this.mapItem, null, ' '))

            //and then save the map
            return this.itemService.save(map)
            // return null;    //use if testing locally without actually saving
        })
        .then( created => {
            if(!created) throw new Error("No response when attempting to create the map");
            this.isSaving = false;

            //for now, take user to the map's IDp page
            (window as any).location.href = '/resources/maps/' + created.id;
        })
        .catch( (e:Error) => {
            this.isSaving = false;
            //display error message to user

            if( ~e.message.indexOf('it matches an existing item; ID:') ) {
                let expr = new RegExp(/ID\:\s([a-z0-9]+)\sURI/gi);
                let matches = expr.exec(e.message);
                if(matches && matches.length) {
                    let id = matches[1];
                    let url = window.location.href;
                    url = url.substring(0, url.indexOf("/resources/map")) + '/resources/maps/' + id;
                    e.message = "The map you are creating already exists. " +
                        '<a target="_blank" href="' + url + '">View item</a>';
                }
            }

            this.error = e;
            logger.error("Unable to create map because of: " + e.message);
        });
    }


    /**
     *
     */
    addKeyword( $event ?: any ) {
        let keyword = $event ? $event.target.value : this.keyword;
        if(keyword && keyword.length &&
            this.mapItem.keywords.indexOf(keyword)<0 ) {
            this.mapItem.keywords.push(keyword);

            if($event) $event.target.value = "";
            this.keyword = "";
        }
    }

    /**
     * @param event - DataEvent
     */
    onDataEvent( event : DataEvent ) {
        logger.debug("MapLayers.onDataEvent(" + event.type.toString() + ")");
        switch(event.type) {

            case Events.ADD :
                let details = this.data.getDetails();
                this.updateDetails(details);
                break;

            case Events.DEL : break;

            case Events.EXTENT :
                if(event.data && event.data.length) {
                    let extent : Extent = event.data[0] as Extent;
                    this.mapItem.extent = extent;
                }
                break;
        }
    }

    /**
     * @param details - object defining metadata for the new map
     */
    updateDetails( details : {[key:string]:any} ) {

        Object.keys(this.mapItem).forEach( property => {
            //don't copy id or uri or type
            if('id' === property || 'uri' === property || 'type' === property) return;

            let value = details[property] || null;
            if('title' === property) {
                value = 'Map of ' + value;
            }
            this.mapItem[property] = value;
        });

        this.ensureExtent();
        this.ensureResourceTypes();

        // logger.debug("Updated Map Item Details");
        // logger.debug(JSON.stringify(this.mapItem, null, ' '));

    }

    /**
     * if the map doesn't have a geographic extent because whatever it's
     * being created from doesn't have one defined, set a default of CONUS
     */
    ensureExtent() {
        if(!this.mapItem) return;

        if(!this.mapItem.extent) {
            this.mapItem.extent = {
                minx: -120, miny: 20, maxx: -76, maxy: 50
            };
            return;
        }
        if(typeof(this.mapItem.extent.minx) === 'undefined' || this.mapItem.extent.minx === null)
            this.mapItem.extent.minx = -120;
        if(typeof(this.mapItem.extent.miny) === 'undefined' || this.mapItem.extent.miny === null)
            this.mapItem.extent.miny = 20;
        if(typeof(this.mapItem.extent.maxx) === 'undefined' || this.mapItem.extent.maxx === null)
            this.mapItem.extent.maxx = -76;
        if(typeof(this.mapItem.extent.maxy) === 'undefined' || this.mapItem.extent.maxy === null)
            this.mapItem.extent.maxy = 50;
    }

    /**
     * if no map specializations were copied over, set the default one
     * since this map is being generated from a non-map asset
     */
    ensureResourceTypes() {
        if(!this.mapItem) return;
        this.mapItem.resourceTypes = this.mapItem.resourceTypes || [];
        if( !this.mapItem.resourceTypes.length ) {
            this.mapItem.resourceTypes.push(GEOPLATFORM_MAP_TYPE);
        }
    }


    // getUser() {
    //     if('development' === environment.env) {
    //         return { username: 'tester' } as GeoPlatformUser;
    //     }
    //     return super.getUser();
    // }


    openDialog( property : string ): void {
        console.log(JSON.stringify(property));
        let key : string;
        let query = new Query();
        switch(property) {
            case 'publishers':
            key = QueryParameters.PUBLISHERS_ID;
            query.types(ItemTypes.ORGANIZATION);
            break;

            case 'themes':
            key = QueryParameters.THEMES_ID;
            query.types(ItemTypes.CONCEPT);
            break;

            case 'topics':
            key = QueryParameters.TOPICS_ID;
            query.types(ItemTypes.TOPIC);
            break;

            case 'usedBy':
            key = QueryParameters.USED_BY_ID;
            query.types(ItemTypes.COMMUNITY);
            break;
        };

        let opts : any = {
            width: '50%',
            data: {
                service : this.itemService,
                query   : query,
                selected: []
            }
        };
        const dialogRef = this.dialog.open(ListSelectDialog, opts);
        dialogRef.afterClosed().subscribe( ( results : Item[] ) => {
            if(results && results.length) {

                this.mapItem[property] = (this.mapItem[property] || []).concat(results);
            }
        });
    }

}









@Component({
    selector: 'gpmp-array-property',
    template: `
    <div class="m-article__desc">
        <div class="a-heading d-flex flex-justify-between flex-align-center">

            <span class="flex-1" (click)="isCollapsed=!isCollapsed">
                {{label}}
                <button type="button" class="btn btn-sm btn-link">
                    <span *ngIf="isCollapsed" class="fas fa-caret-down"></span>
                    <span *ngIf="!isCollapsed" class="fas fa-caret-up"></span>
                    <span class="sr-only">Hide/Show {{label}} values</span>
                </button>
            </span>

            <button type="button" class="btn btn-sm btn-outline-secondary"
                *ngIf="enableAdd && !isCollapsed" (click)="doAdd()">
                ADD
                <span class="sr-only">Click to add values </span>
            </button>
        </div>
        <div *ngIf="!isCollapsed">
            <ng-content select="[contents]"></ng-content>
        </div>
    </div>
    `
})
export class ArrayPropertyComponent {

    @Input() field : string;
    @Input() label : string;
    @Input() iconClass : string;
    @Input() enableAdd : boolean = true
    @Output() onEvent : EventEmitter<string> = new EventEmitter<string>();

    public isCollapsed : boolean = true;

    constructor() {

    }

    doAdd() {
        this.onEvent.emit(this.field);
    }
}
