import {
    Component, OnInit, OnDestroy, OnChanges, SimpleChanges,
    Input, HostBinding
} from '@angular/core';
import { ISubscription } from "rxjs/Subscription";
import { ItemService, ItemTypes, Map } from '@geoplatform/client';
import { GeoPlatformUser } from 'ng-gpoauth/angular';

import {
    DataProvider, DataEvent, Events, Extent, LayerState
} from '../shared/data.provider';
import { AuthenticatedComponent } from '../shared/authenticated.component';
import { PluginAuthService } from '../shared/auth.service';
import { itemServiceProvider } from '../shared/service.provider';
import { environment } from '../../environments/environment';
import { logger } from "../shared/logger";


const GEOPLATFORM_MAP_TYPE = "http://www.geoplatform.gov/ont/openmap/GeoplatformMap";
const IS_DEV = 'development' === environment.env;


@Component({
  selector: 'gpmp-map-details',
  templateUrl: './details.component.html',
  styleUrls: ['./details.component.less'],
  providers: [itemServiceProvider]
})
export class DetailsComponent extends AuthenticatedComponent implements OnInit, OnDestroy {

    @Input() data : DataProvider;
    @Input() isCollapsed : boolean = false;
    @HostBinding('class.isCollapsed') hostCollapsed: boolean = false;

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

    private dataSubscription : ISubscription;

    constructor(
        private itemService : ItemService,
        authService : PluginAuthService
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
            if(IS_DEV) {
                this.onUserChange(user);
                return Promise.resolve(null);
            }
            // ensure token isn't in weird expired/revoked state
            return this.checkAuth().then( user => {
                this.onUserChange(user);
                if(!user) throw new Error("Not signed in");
            });
        })
        .then( () => {
            //then request a URI for the new map
            return this.itemService.getUri(this.mapItem);
        })
        .then(uri => {
            if(!uri) throw new Error("Unable to generate a URI for the new map");
            this.mapItem.uri = uri;
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


    getUser() {
        if('development' === environment.env) {
            return { username: 'tester' } as GeoPlatformUser;
        }
        return super.getUser();
    }

}









@Component({
  selector: 'gpmp-array-property',
  template: `
      <div class="m-article__desc">
          <div class="a-heading">
              <span *ngIf="iconClass" class="{{iconClass}}"></span>
              {{label}} ({{value?.length||0}})
              <button type="button" class="btn btn-sm btn-link"
                  (click)="isCollapsed=!isCollapsed">
                  <span *ngIf="isCollapsed" class="fas fa-chevron-down"></span>
                  <span *ngIf="!isCollapsed" class="fas fa-chevron-up"></span>
              </button>
          </div>
          <div *ngIf="!isCollapsed">
              <span *ngFor="let val of value" class="a-keyword">{{val.label||"Untitled Resource"}}</span>
          </div>
      </div>
  `
})
export class ArrayPropertyComponent {

    @Input() field : string;
    @Input() label : string;
    @Input() value : any[];
    @Input() iconClass : string;

    public isCollapsed : boolean = true;

    constructor() {

    }
}
