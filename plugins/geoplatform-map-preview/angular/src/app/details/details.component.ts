import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { ISubscription } from "rxjs/Subscription";
import { ItemService, ItemTypes } from 'geoplatform.client';

import { DataProvider, DataEvent, Events } from '../shared/data.provider';
import { AuthenticatedComponent } from '../shared/authenticated.component';
import { itemServiceProvider } from '../shared/service.provider';


@Component({
  selector: 'gpmp-map-details',
  templateUrl: './details.component.html',
  styleUrls: ['./details.component.less'],
  providers: [itemServiceProvider]
})
export class DetailsComponent extends AuthenticatedComponent implements OnInit, OnDestroy {

    @Input() data : DataProvider;
    public isCollapsed : boolean = true;
    public isKeywordsCollapsed : boolean = true;

    public mapItem : {
        uri         : string;
        type        : string;
        title       : string;
        description : string;
        keywords   ?: string[];
        createdBy   : string;
        layers      : any[];
    } = {
        uri         : null,
        type        : ItemTypes.MAP,
        title       : "My New Map",
        description : "This map needs a description",
        keywords    : [],
        createdBy   : "tester",
        layers      : []
    };

    public keyword : string;

    private dataSubscription : ISubscription;

    constructor(private itemService : ItemService) {
        super();
    }

    ngOnInit() {
        super.ngOnInit();
        if(this.data) {
            this.dataSubscription = this.data.subscribe( (event : DataEvent) => {
                this.onDataEvent(event);
            });
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
        if(user) {
            this.mapItem.createdBy = user.username;
        }
    }

    /**
     *
     */
    createMap() {

        if(!this.mapItem.createdBy) {
            console.log("User not authenticated, aborting create...");
            return;
        }

        this.itemService.getUri(this.mapItem)
        .then(uri => {
            this.mapItem.uri = uri;
            return this.mapItem;
        })
        .then( map => {
            //store selected layers onto map being created
            this.mapItem.layers = this.data.getDataWithState(true);
            return this.itemService.save(map) 
        })
        .then( created => {
            //TODO display success message!

            //for now, take user to the map's IDp page
            (window as any).location.href = '/resources/maps/' + created.id;
        })
        .catch( (e:Error) => {
            //TODO display error message to user
            console.log("Unable to create map, " + e.message);
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
        // console.log("MapLayers.onDataEvent(" + event.type.toString() + ")");
        switch(event.type) {

            case Events.ADD :
                let details = this.data.getDetails();
                this.updateDetails(details);
                break;

            case Events.DEL :
                break;
        }
    }

    /**
     * @param details - object defining metadata for the new map
     */
    updateDetails( details : {[key:string]:any} ) {
        if(details.title) {
            let title = "Map of " + details.title;
            this.mapItem.title = title;
        }
        if(details.description) this.mapItem.description = details.description;
        if(details.keywords) this.mapItem.keywords = details.keywords;
    }

}
