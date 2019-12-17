import { moveItemInArray }  from '@angular/cdk/drag-drop';
import { Subject, Subscription } from 'rxjs';
import {
    Query, ItemService, ItemTypes,
    Item, Map as MapItem, Layer, Service
} from '@geoplatform/client';
import { logger } from './logger';



const IGNORED_PROPERTIES = [
    'id',               //don't bother inheriting these properties
    'uri',              //
    'type',             //
    'createdBy',        //
    'lastModifiedBy'    //

    //https://geoplatform.atlassian.net/browse/DT-2945?focusedCommentId=38006
    // 'themes',
    // 'usedBy'
]

export const Events = {
    ON      : Symbol('on'),
    OFF     : Symbol('off'),
    ADD     : Symbol("add"),    //add layer event
    DEL     : Symbol("del"),    //remove layer event
    VIZ     : Symbol("viz"),    //layer visibility event
    MOVE    : Symbol("move"),   //layer moved (reordered)
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


/**
 *
 *
 */
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

    /**
     *
     */
    getDetails() : MapItem {
        return this.details;
    }

    /**
     * @param item - GeoPlatform resource from which the map is being created as a preview of
     */
    setDetails( item : Item ) {

        Object.keys( this.details ).forEach( (property) => {

            if( IGNORED_PROPERTIES.indexOf(property) >= 0 ) {
                logger.debug(`DataProvider.setDetails() - Ignoring '${property}'`);
                return;
            }
            if('resourceTypes' === property && item.type !== ItemTypes.MAP) {
                //don't bother trying to carry over non-map specializations
                return;
            }

            this.setDetailsProperty(property, item[property] || null);
        });
    }

    /**
     * @param property - string name of field being set
     * @param value - value to set for specified field
     */
    setDetailsProperty( property : string, value : any ) {

        logger.debug(`DataProvider.setDetailsProperty('${property}') - `, JSON.stringify(value, null, ' '));

        if( !this.details[property] ) {     //initialize value if not set already
            this.details[property] = value;
            return;
        }

        if(Array.isArray(value)) {      //merging arrayed values

            let isObj = 'keywords' !== property;
            let arr = ( this.details[property] || [] ).concat(value);

            if(isObj) {                 //merging non-literals
                let distinct = [];
                const map = new Map();
                for ( const item of arr ) {
                    if( !map.has(item.id) ){
                        map.set(item.id, true);    // set any value to Map
                        distinct.push(item);
                    }
                }
                this.details[property] = distinct;
                return;

            }

            //merging literal values
            this.details[property] = [ ... Array.from(new Set(arr)) ];
            return;
        }

        if( 'extent' === property && value && typeof(value.minx) !== 'undefined' ) {

            if( !this.details.extent ) {        //no existing value
                this.details.extent = value;
                return;
            }

            //merging/overwriting if parts not already set
            this.details.extent.minx = this.details.extent.minx || value.minx || -120;
            this.details.extent.miny = this.details.extent.miny || value.miny ||   20;
            this.details.extent.maxx = this.details.extent.maxx || value.maxx ||  -60;
            this.details.extent.maxy = this.details.extent.maxy || value.maxy ||   50;
        }

    }


    /**
     *
     */
    processItem(item : any | any[]) {

        if(!item) {
            throw new Error("DataProvider.processItem() - Nothing exists to be processed into renderables");
        }

        let arr = Array.isArray(item) ? item : [item];

        let datasets = [],
            services = [],
            layers   = [],
            // maps     = [],
            galleries = [];

        arr.forEach( it => {

            let type = it.type;
            if(!type) {
                logger.warn("DataProvider.processItem() - Item (" + it.id + ") has no type and therefore cannot be previewed");
                return;
            }

            if(!this.details.title) {
                this.setDetails(it as Item);
            }

            if(     ItemTypes.DATASET === type) datasets.push(it);
            else if(ItemTypes.SERVICE === type) services.push(it);
            else if(ItemTypes.LAYER   === type) layers.push(it);
            // else if(ItemTypes.MAP     === type) maps.push(it);
            else if(ItemTypes.GALLERY === type) galleries.push(it);
             //else skip it
        });

        if(datasets.length)  this.processDatasets(datasets);
        if(services.length)  this.processServices(services);
        if(layers.length)    this.processLayers(layers);
        // if(maps.length)      this.processMaps(maps);
        if(galleries.length) this.processGalleries(galleries);

    }


    /**
     *
     */
    processDatasets(items : any[]) {
        let svcIds = [];
        items.map( item => {
            svcIds = svcIds.concat( (item.services || []).map(s=>s.id));
        });
        this.findServiceLayers(svcIds);
    }

    /**
     *
     */
    processServices(items : any[]) {
        let svcIds = items.map(it=>it.id);
        this.findServiceLayers(svcIds);
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
    processMaps(maps: any[]) {

        let serviceIds = [], layerIds = [], layers = [],
            baseLayerId = null, baseLayer = null;
        maps.forEach(map => {

            //they have a base layer
            if(map.baseLayer_id) {
                layerIds.push(map.baseLayer_id);
                baseLayerId = map.baseLayer_id;
            } else if(map.baseLayer) {
                baseLayer = map.baseLayer;
            }

            //they have one or more overlay layers
            map.layers.forEach(layerState => {
                if(layerState.layer) layers.push(layerState.layer);
                else if(layerState.layer_id) layerIds.push(layerState.layer_id);
            });
        });

        //add already resolved base layer
        if(baseLayer) this.setBaseLayer(baseLayer);

        //add already parsed layers
        if(layers.length) setTimeout(() => { this.addData(layers); });

        //if layers were just layer ids instead of full objects, resolve them and add
        if(layerIds.length) {
            this.itemService.getMultiple(layerIds)
            .then( (response:any) => {
                this.addMapLayers(response, baseLayerId);
            })
            .catch(e => {
                logger.error("DataProvider.processMaps() - Error searching for renderables: " + e.message);
            });
        }
    }

    /**
     *
     */
    processGalleries(galleries: any[]) {

        let serviceIds = [], layerIds = [], layers = [], baseLayerId = null;
        galleries.forEach(gallery => {

            if(!gallery.items) return;

            gallery.items.forEach(item => {

                if(item.assetType === ItemTypes.SERVICE) {
                    //they have a service with layers (check below)...
                    serviceIds.push(item.assetId);

                } else if(item.assetType === ItemTypes.LAYER && item.asset) { //they have a layer...
                    layers.push(item.asset);

                } else if(item.assetType === ItemTypes.MAP && item.asset) {
                    //they have a map with a base layer
                    if(item.asset.baseLayer_id) {
                        layerIds.push(item.asset.baseLayer_id);
                        //only remember the first base layer found
                        baseLayerId = baseLayerId || item.asset.baseLayer_id;
                    }

                    //they have a map with one or more overlay layers
                    item.asset.layers.forEach(layerState => {
                        if(layerState.layer) layers.push(layerState.layer);
                        else if(layerState.layer_id) layerIds.push(layerState.layer_id);
                    });
                }
            });
        });

        if(layers.length) setTimeout(() => { this.addData(layers); });

        //find all layers associated with extracted service ids
        if(serviceIds.length) {
            this.findServiceLayers(serviceIds);
        }

        //and resolve all layers referenced via ids
        if(layerIds.length) {
            this.itemService.getMultiple(layerIds).then( (response:any) => {
                this.addMapLayers(response, baseLayerId);
            }).catch(e => {
                logger.error("DataProvider.processGalleries() - Error searching for renderables: " + e.message);
            });
        }
    }

    /**
     * Finds and adds layers to the current data based upon associations with
     * services having ids in provided list
     * @param svcIds - array of service identifiers from which to find linked layers
     */
    findServiceLayers( svcIds : string[] ) {
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
            logger.error("DataProvider.searchItems() - Error : " + e.message);
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


    /**
     * @param layers - list of zero or more layers
     * @param baseLayerId - string id of layer to set as base layer
     */
    addMapLayers( layers : any[], baseLayerId ?: string ) {
        if(!layers || !layers.length) return;
        setTimeout(() => {
            if(baseLayerId) {
                let data = [];
                layers.forEach( l => {
                    if(l.id === baseLayerId) this.setBaseLayer(l);
                    else data.push(l);
                });
                this.addData(data);
                return;
            }
            this.addData(layers);
        });
    }


    /**
     * @param item Item or Item[] to add to list of data layers
     */
    addData(item : any | any[], warning ?: string) {

        let items = Array.isArray(item) ? item : [item];

        if(this.data.length) {
            //check if already added
            items = items.filter( it => this.data.findIndex(d=>d.id===it.id)<0 );
        }

        if(!item.length) return;  //no new layers to add

        this.data = this.data.concat( items );
        this.adjustExtent();

        let evt = { type : Events.ADD, data: items } as DataEvent;
        if(warning) evt.warning = warning;
        this.sub.next(evt);
    }

    /**
     * @param item Item to remove from data layers list
     */
    removeData(item : any | any[], trigger ?: boolean) {

        let removed = [];
        let arr = Array.isArray(item) ? item : [item];
        arr.forEach( o => {
            let idx = this.data.findIndex( it => it.id === o.id);
            if(idx > -1) {
                this.data.splice(idx, 1);
                removed.push(o);
            }
        });

        if( false === trigger ) return;

        let evt = { type : Events.DEL, data: removed } as DataEvent;
        this.adjustExtent();
        this.sub.next(evt);
    }

    /**
     * @return array of layer objects
     */
    getData( ) : any[] {
        return this.data;
    }

    /**
     * @param state - boolean flag indicating whether the data object is selected
     * @return array of data objects matching specified state
     */
    getSelected( state : boolean ) : any[] {
        return this.data.filter( d => !!this.selected[d.id] ).sort( (a,b)=>a.zIndex>b.zIndex?-1:1 );
    }

    /**
     *
     */
    toggleData( item : any|any[] ) {

        let arr = Array.isArray(item) ? item : [item];

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

    /**
     *
     */
    activateData( item : any|any[] ) {

        let arr = Array.isArray(item) ? item : [item];

        let type = Events.ON;
        arr.forEach( it => {
            this.selected[it.id] = true;
            this.visibility[it.id] = true;
        });

        let evt : DataEvent = { type : Events.ON, data: arr };
        this.sub.next(evt);
    }


    /**
     * @param item - layer object
     * @return boolean
     */
    isSelected( item : any ) : boolean {
        let id = this.getId(item);
        return !!this.selected[id];
    }

    /**
     * @param item - layer object
     * @return boolean
     */
    isVisible( item : any ) : boolean {
        let id = this.getId(item);
        return !!this.visibility[id];
    }

    /**
     * @param item - layer state
     */
    toggleVisibility( item : any ) {
        if(!item || !item.id) return;
        this.visibility[item.id] = !this.visibility[item.id];
        let evt : DataEvent = { type: Events.VIZ, data: [item] }
        this.trigger(evt);
    }

    /**
     * @param layers - list of layer states
     */
    updateOrdering(layers : any[]) {
        layers.forEach( (state) => {
            let idx = this.data.findIndex(d => d.id === state.layer.id);
            this.data[idx].zIndex = state.zIndex;
        });
    }

    /**
     *
     */
    moveLayerBefore(fromId : string, toId : string) {
        let selected   = this.getSelected(true);
        let currentPos = selected.findIndex(d=>d.id===fromId);
        let newPos = selected.findIndex(d=>d.id===toId);
        let from   = this.data.findIndex(d=>d.id===fromId);
        let to     = this.data.findIndex(d=>d.id===toId);
        let copy   = this.data.splice(from, 1)[0];
        this.data.splice(to, 0, copy);
        let evt : DataEvent = { type: Events.MOVE, data: [currentPos, newPos] }
        this.trigger(evt);
    }

    /**
     * @param item - layer
     * @return identifier of layer
     */
    private getId( item : any ) {
        if(!item) return null;
        let id = null;
        if(typeof(item.id) !== 'undefined') id = item.id;
        else if(typeof(item) === 'string') id = item;
        return id;
    }


    /**
     * @param layer - layer object to use as base layer
     * @param trigger - optional boolean
     */
    setBaseLayer( layer : any, trigger ?: boolean ) {
        this.details.baseLayer = layer;
        if(typeof(trigger) === 'undefined' || trigger === true) {
            let evt : DataEvent = { type : Events.BASE, data: [layer] };
            this.sub.next(evt);
        }
    }

    /**
     * @return layer object used as base layer
     */
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


    subscribe( callback : any ) : Subscription {
        return this.sub.subscribe( callback );
    }

    /**
     *
     *
     */
    private adjustExtent() {
        let extent : Extent = { minx: 179, maxx: -179, miny: 89, maxy: -89 };
        this.data.forEach( it => {

            if(!it || !it.extent) return;

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
