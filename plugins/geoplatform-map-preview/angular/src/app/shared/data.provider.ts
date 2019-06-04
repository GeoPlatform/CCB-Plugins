import { Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";
import { Query, ItemService, ItemTypes } from 'geoplatform.client';



export const Events = {
    ON  : Symbol('on'),
    OFF : Symbol('off'),
    ADD : Symbol("add"),    //add layer event
    DEL : Symbol("del"),    //remove layer event
    VIZ : Symbol("viz"),    //layer visibility event
    BASE: Symbol("base")    //base layer event
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

export interface Item {
    uri         : string;
    type        : string;
    title       : string;
    description : string;
    createdBy   : string;
    keywords   ?: string[];
    themes     ?: any[];
    topics     ?: any[];
    usedBy     ?: any[];
    publishers ?: any[];
    classifiers?: {[key:string]:any};
    resourceTypes ?: any[];
    extent     ?: { minx ?: number; maxx ?: number; miny ?: number; maxy ?: number; };
}

export interface MapItem extends Item {
    layers      : any[];
    baseLayer  ?: any;
}


export class DataProvider {

    //GP service to fetch data items
    private itemService : ItemService;

    //object holding map metadata (title, etc)
    private details : MapItem = {
        uri         : null,
        type        : ItemTypes.MAP,
        title       : null,
        description : null,
        createdBy   : null,
        resourceTypes : [],
        keywords    : [],
        layers      : [],
        themes      : [],
        topics      : [],
        publishers  : [],
        usedBy      : [],
        classifiers : {},
        extent      : {}
    };

    //object holding spatial extent of all data
    private extent : Extent;

    //array of data objects
    private data : any[] = [] as any[];

    //kvp of data states (added to map or not)
    private states : {[key:string]:boolean} = {};

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
                console.log("Item " + it.id + " has no type and therefore cannot be previewed");
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
            .pageSize(50);
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
            .pageSize(50);
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
        // console.log("Searching page #" + page + " (" + pageSize + ")")

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
            console.log("Error searching for items: " + e.message);
        });
    }



    getExtent() : Extent {
        return this.extent;
    }

    addData(item : any | any[], warning ?: string) {

        if(Array.isArray(item)) {
            this.data = this.data.concat( (item as any[]).map(it => this.wrapItem(it) ) );
        } else {
            this.data.push( this.wrapItem(item) );
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
        let idx = this.data.findIndex( it => it.layerId === item.id);
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

    getDataWithState( state : boolean ) : any[] {
        return this.data.filter( d => !!this.states[d.layerId] );
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
            this.states[it.layerId] = !this.states[it.layerId];
            if( !this.states[it.layerId] ) type = Events.OFF;
        });

        let evt : DataEvent = { type : type, data: arr };
        this.sub.next(evt);
    }

    getDataState( item : any ) : boolean {
        return !!this.states[item.layerId];
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
     */
    private wrapItem(item : any) : any {
        if(!item.layer) {
            return {
                layer : item,
                layerId : item.id,
                opacity: 1.0,
                visibility: true
            };
        }
        return item;
    }

    /**
     *
     *
     */
    private adjustExtent() {
        let extent : Extent = { minx: 179, maxx: -179, miny: 89, maxy: -89 };
        this.data.forEach( it => {

            let itExt : Extent = it.layer.extent as Extent;
            if(!itExt) return;

            extent.minx = Math.min(extent.minx, itExt.minx||179);
            extent.miny = Math.min(extent.miny, itExt.miny||89);
            extent.maxx = Math.max(extent.maxx, itExt.maxx||-179);
            extent.maxy = Math.max(extent.maxy, itExt.maxy||-89);
        });
        this.extent = extent;
    }
}
