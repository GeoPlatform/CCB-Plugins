import { Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";
import {
    Query, ItemService, ItemTypes,
    Item, Map as MapItem, Layer, Service
} from '@geoplatform/client';
import { logger } from './logger';


export const Events = {
    ON      : Symbol('on'),
    OFF     : Symbol('off'),
    ADD     : Symbol("add"),    //add layer event
    DEL     : Symbol("del"),    //remove layer event
    VIZ     : Symbol("viz"),    //layer visibility event
    BASE    : Symbol("base"),   //base layer event
    EXTENT  : Symbol("extent")  //map extent change
}


export interface DataEvent {
    type : Symbol;
    data : any[];
    warning?: string;
}

export interface Extent {
    minx: number;
    maxx: number;
    miny: number;
    maxy: number;
}


export interface LayerState {
    layer : any;
    layerId: string;
    visibility: boolean;
    opacity: number;
}


// export interface Item {
//     uri         : string;
//     type        : string;
//     title       : string;
//     description : string;
//     createdBy   : string;
//     keywords   ?: string[];
//     themes     ?: any[];
//     topics     ?: any[];
//     usedBy     ?: any[];
//     publishers ?: any[];
//     classifiers?: {[key:string]:any};
//     resourceTypes ?: any[];
//     extent     ?: { minx ?: number; maxx ?: number; miny ?: number; maxy ?: number; };
// }
//
// export interface MapItem extends Item {
//     layers      : LayerState[];
//     baseLayer  ?: any;
// }


export class DataProvider {

    //GP service to fetch data items
    private itemService : ItemService;

    //object holding map metadata (title, etc)
    private details : MapItem = {
        id          : null,
        uri         : null,
        type        : ItemTypes.MAP,
        title       : null,
        description : null,
        createdBy   : null,
        resourceTypes : [],
        keywords    : [],
        layers      : [] as LayerState[],
        themes      : [],
        topics      : [],
        publishers  : [],
        usedBy      : [],
        classifiers : {},
        extent      : null
    };

    //object holding spatial extent of all data
    private extent : Extent;

    //array of data objects
    private data : any[] = [] as any[];

    //kvp of data selected (added to map or not)
    private selected : {[key:string]:boolean} = {};

    //kvp of data marked as visible or hidden (must be in selected set first)
    private visibility : {[key:string]:boolean} = {};

    //subscription with which to notify listeners of data changes
    private sub : Subject<DataEvent> = new Subject<DataEvent>();

    constructor(itemService : ItemService) {
        this.itemService = itemService;
    }


    getDetails() : {[key:string]:any} {
        return this.details;
    }

    /**
     *
     */
    setDetails( item : Item ) {

        Object.keys( this.details ).forEach( property => {

            //don't bother trying to carry over non-map specializations
            if('resourceTypes' === property && item.type !== ItemTypes.MAP) {
                return;
            }

            let value = item[property] || null;

            // console.log("Setting Property '" + property + "' : " + JSON.stringify(value, null, ' '));

            if(!this.details[property]) {
                this.details[property] = value;

            } else {    //existing value, merge

                if(Array.isArray(value)) {

                    let isObj = 'keywords' !== property;
                    let arr = (this.details[property]||[]).concat(value);

                    if(isObj) {
                        let distinct = [];
                        const map = new Map();
                        for (const item of arr) {
                            if(!map.has(item.id)){
                                map.set(item.id, true);    // set any value to Map
                                distinct.push(item);
                            }
                        }
                        this.details[property] = distinct;
                    } else {
                        this.details[property] = [ ... Array.from(new Set(arr)) ];
                    }

                } else if( 'extent' === property ) {

                    if(!this.details.extent) this.details.extent = value;
                    else {
                        if(!this.details.extent.minx)
                            this.details.extent.minx = value.minx||-120;
                        if(!this.details.extent.miny)
                            this.details.extent.miny = value.miny||20;
                        if(!this.details.extent.maxx)
                            this.details.extent.maxx = value.maxx||-76;
                        if(!this.details.extent.maxy)
                            this.details.extent.maxy = value.maxy||50;
                    }

                }
            }

        });
    }


    /**
     *
     */
    processItem(item : any | any[]) {

        if(!item) {
            throw new Error("Nothing exists to be processed into renderables");
        }

        let arr = item;
        if(!Array.isArray(item)) {
            arr = [item];
        }

        let datasets = [], services = [], layers = [];

        arr.forEach( it => {

            let type = it.type;
            if(!type) {
                logger.warn("Item (" + it.id + ") has no type and therefore cannot be previewed");
                return;
            }

            if(!this.details.title) {
                this.setDetails(it as Item);
            }


            if(ItemTypes.DATASET === type) datasets.push(it);
            else if(ItemTypes.SERVICE === type) services.push(it);
            else if(ItemTypes.LAYER === type) layers.push(it);
             //else skip it
        });

        if(datasets.length) this.processDatasets(datasets);
        if(services.length) this.processServices(services);
        if(layers.length) this.processLayers(layers);

    }


    /**
     *
     */
    processDatasets(items : any[]) {
        let svcIds = [];
        items.map( item => {
            svcIds = svcIds.concat( (item.services || []).map(s=>s.id));
        });
        let query = new Query()
            .fields('*')
            .types(ItemTypes.LAYER)
            .page(0)
            .pageSize(100);
        query.setParameter("service", svcIds.join(','));
        this.searchItems(query);
    }

    /**
     *
     */
    processServices(items : any[]) {
        let query = new Query()
            .fields('*')
            .types(ItemTypes.LAYER)
            .page(0)
            .pageSize(100);
        query.setParameter( "service", items.map(it=>it.id).join(',') );
        this.searchItems(query);
    }

    /**
     *
     */
    processLayers(items : any[]) {
        this.addData(items);
    }


    /**
     *
     */
    searchItems(query : Query) {

        let page : number = query.getPage() as number;
        let pageSize : number  = query.getPageSize() as number;

        this.itemService.search(query).then( (response : any) => {

            let warning = null;
            if((page*pageSize) > 50) {
                warning = "More layers exist but are not available for performance reasons";
            }

            setTimeout(() => {
                this.addData(response.results, warning);
            });

            if(response.totalResults > ((page*pageSize) + pageSize)) {
                //more data to come

                if((page*pageSize) > 50) {
                    //TODO display warning message
                    return;
                }

                query.setPage(page+1);
                setTimeout(() => { this.searchItems(query) });
            }
        })
        .catch(e => {
            logger.error("Error searching for items: " + e.message);
        });
    }


    setExtent( extent : Extent ) {
        this.extent = extent;
        let evt = { type : Events.EXTENT, data: [extent] } as DataEvent;
        this.sub.next(evt);
    }

    getExtent() : Extent {
        return this.extent;
    }

    addData(item : any | any[], warning ?: string) {

        if(Array.isArray(item)) {
            this.data = this.data.concat( (item as any[]) );
        } else {
            this.data.push( item );
        }

        this.adjustExtent();

        let evt = {
            type : Events.ADD,
            data: Array.isArray(item) ? item as any[] : [ item ]
        } as DataEvent;
        if(warning) evt.warning = warning;
        this.sub.next(evt);
    }

    removeData(item : any) {
        let idx = this.data.findIndex( it => it.id === item.id);
        if(idx > -1) {
            this.data.splice(idx, 1);
            let evt = {
                type : Events.DEL,
                data: [ item ]
            } as DataEvent;

            this.adjustExtent();

            this.sub.next(evt);
        }
    }

    getData( ) : any[] {
        return this.data;
    }

    /**
     * @param state - boolean flag indicating whether the data object is selected
     * @return array of data objects matching specified state
     */
    getSelected( state : boolean ) : any[] {
        return this.data.filter( d => !!this.selected[d.id] );
    }

    /**
     *
     */
    toggleData( item : any|any[] ) {

        let arr = item;
        if(!Array.isArray(item)) {
            arr = [item];
        }

        let type = Events.ON;
        arr.forEach( it => {
            this.selected[it.id] = !this.selected[it.id];
            if( !this.selected[it.id] ) {
                type = Events.OFF;
                this.visibility[it.id] = false;
            } else {
                this.visibility[it.id] = true;
            }
        });

        let evt : DataEvent = { type : type, data: arr };
        this.sub.next(evt);
    }

    isSelected( item : any ) : boolean {
        let id = this.getId(item);
        return !!this.selected[id];
    }

    isVisible( item : any ) : boolean {
        let id = this.getId(item);
        return !!this.visibility[id];
    }

    toggleVisibility( item : any ) {
        if(!item || !item.id) return;
        this.visibility[item.id] = !this.visibility[item.id];
        let evt : DataEvent = { type: Events.VIZ, data: [item] }
        this.trigger(evt);
    }

    private getId( item : any ) {
        if(!item) return null;
        let id = null;
        if(typeof(item.id) !== 'undefined') id = item.id;
        else if(typeof(item) === 'string') id = item;
        return id;
    }



    setBaseLayer( layer : any, trigger ?: boolean ) {
        this.details.baseLayer = layer;
        if(typeof(trigger) === 'undefined' || trigger === true) {
            let evt : DataEvent = { type : Events.BASE, data: [layer] };
            this.sub.next(evt);
        }
    }
    getBaseLayer() {
        return this.details.baseLayer;
    }



    trigger( event : DataEvent ) {
        if(!event) return null;
        //just in case a base layer is set without using .setBaseLayer(),
        // catch the event here and then forward it to subscribers
        if(Events.BASE === event.type) {
            this.setBaseLayer(event.data[0], false);
        }
        this.sub.next(event);
    }


    subscribe( callback : any ) : ISubscription {
        return this.sub.subscribe( callback );
    }

    /**
     *
     *
     */
    private adjustExtent() {
        let extent : Extent = { minx: 179, maxx: -179, miny: 89, maxy: -89 };
        this.data.forEach( it => {

            let itExt : Extent = it.extent as Extent;
            if(!itExt) return;

            extent.minx = Math.min(extent.minx, itExt.minx||179);
            extent.miny = Math.min(extent.miny, itExt.miny||89);
            extent.maxx = Math.max(extent.maxx, itExt.maxx||-179);
            extent.maxy = Math.max(extent.maxy, itExt.maxy||-89);
        });
        this.extent = extent;
    }
}
