import {
    Inject, Component, OnInit, Input, OnChanges, SimpleChanges
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Subscription } from "rxjs";

import {
    Config, ItemTypes, MapService, LayerService, ServiceFactory
} from "@geoplatform/client";
import { NG2HttpClient } from '@geoplatform/client/angular';

import {
    MapFactory, MapInstance, OSM, LayerFactory, DefaultBaseLayer
} from "@geoplatform/mapcore";


import * as L from 'leaflet';
import * as esri from "esri-leaflet";
import "leaflet.vectorgrid";

import {
    DataProvider, Events, DataEvent, Extent, LayerState
} from '../shared/data.provider';
import { environment } from '../../environments/environment';
import { logger } from '../shared/logger';


const EXTENT_STYLE = {
    color: "transparent",
    weight: 1,
    fill: true,
    fillColor: "#777777",
    fillOpacity: 0.7
};


@Component({
  selector: 'gpmp-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.less']
})
export class MapComponent implements OnInit, OnChanges {

    @Input() extent : Extent;
    @Input() data : DataProvider;

    public errors : any[] = [] as any[];
    public warnings: any[] = [] as any[];

    private httpClient : NG2HttpClient;
    private map : MapInstance;
    private extentLayer : L.Layer;
    private dataSubscription : Subscription;
    private extentTimer : any;
    private layerService : LayerService;

    constructor( @Inject(LayerService) layerService : LayerService ) {
        this.layerService = layerService;
        this.httpClient = layerService.getClient();

        // this.httpClient.setAuthToken(function() {
        //     //uses token cached by ng-common's AuthenticationService
        //     let token = AuthenticationService.getJWTfromLocalStorage();
        //     return token;
        // });
    }

    ngOnInit() {
        this.initMap();

        if(this.data) {
            this.dataSubscription = this.data.subscribe( (event : DataEvent) => {
                this.onDataEvent(event);
            });
        }
    }

    ngOnChanges(changes: SimpleChanges) {
        if(!this.map) return;

    }

    ngOnDestroy() {
        if(this.dataSubscription) this.dataSubscription.unsubscribe();
        if(this.data) this.data = null;
        if(this.map) {
            this.map.destroyMap();
            this.map = null;
        }
    }

    /**
     *
     */
    initMap() {

        let map = L.map("previewMap", {
            zoomControl: true,
            center: [38, -96],
            zoom: 3,
            minZoom: 2,
            maxZoom: 21,
            maxBounds: [[-90,-180],[90,180]]
        });

        if(Config.leafletPane) {
            //create pane for all "overlay" layers to be added to...
            // and put it just atop the default tile pane
            map.createPane(Config.leafletPane);
            map.getPane(Config.leafletPane).style.zIndex = '250';
        }


        this.map = MapFactory.get('GP_MP_MAP');
        this.map.setMap(map);
        this.map.setErrorHandler( (error) => {
            this.errors.push(error);
        });

        this.map.setHttpClient(this.httpClient);
        this.map.setServiceFactory(this.getServiceFactory());

        //configure layer factory with service created here so it uses proper
        // env vars and http client
        LayerFactory.setLayerService(this.layerService);

        this.setDefaultBaseLayer();

        //listen for map pan/zoom events so we can update the map details
        map.on('moveend', () => { this.onMapBoundsChange(); });
    }

    onMapBoundsChange() {
        let bounds = this.map.getMap().getBounds();
        logger.debug("Map Extent Changed: ", bounds.toBBoxString());
        let extent : Extent = {
            minx: bounds.getWest(), miny: bounds.getSouth(),
            maxx: bounds.getEast(), maxy: bounds.getNorth()
        };
        this.data.setExtent(extent);
    }

    /**
     * @return {Promise}
     */
    setDefaultBaseLayer() {
        return DefaultBaseLayer.get(this.layerService).then(baseLayer => {
            if(this.map) {
                this.map.setBaseLayer(baseLayer);
            }
            return baseLayer;
        });
    }

    /**
     * @param {string} id
     * @return {Promise}
     */
    loadMap(id) {
        return this.map.loadMap(id)
        .then( map => {
            if(!map || !map.baseLayer) {
                this.setDefaultBaseLayer();
            }
            return map;
        })
        .catch(e => {
            logger.error("Unable to load map (" + id + ") because of: ", e.message);
            console.log(e);
            this.setDefaultBaseLayer();
            this.errors.push(e);    //display error message to user
            return Promise.resolve(null);
        });
    }


    /**
     * @param {object} extent - GP extent object ( { minx: ..., miny: ..., maxx: ..., maxy: ...} )
     */
    setExtent(extent) {
        if(extent) {
            let bounds = this.getBoundsFor(extent);
            if(!bounds) return;
            this.map.setExtent(extent); //MapCore Map expects extent, not array
        }
    }

    /**
     * @param {object} extent - extent object
     * @return {array} bounds
     */
    getBoundsFor(extent : any) : any {
        if(!extent) return null;
        if( isNaN(extent.minx) || isNaN(extent.miny) ||
            isNaN(extent.maxx) || isNaN(extent.maxy) ) {
            return null;
        }
        let bounds = [];
        bounds.push( [ extent.miny*1, extent.minx*1 ] );
        bounds.push( [ extent.maxy*1, extent.maxx*1 ] );
        if(bounds[0][0] < -89) bounds[0][0] = -89;
        if(bounds[0][1] < -179) bounds[0][1] = -179;
        if(bounds[1][0] > 89) bounds[1][0] = 89;
        if(bounds[1][1] > 179) bounds[1][1] = 179;
        return bounds;
    }

    renderBounds(bounds : any, forItem ?: boolean) : L.Layer {
        if(!bounds) return null;
        if(forItem) {
            return new L.Rectangle(bounds, EXTENT_STYLE);
        }

        let coords = [
            [ [-90, -180], [-90, 180], [90, 180], [90, -180], [-90,-180] ], // outer ring
            [   bounds[0], [bounds[0][0],bounds[1][1]],
                bounds[1], [bounds[1][0],bounds[0][1]],
                bounds[0]
            ]  // hole
        ];
        return new L.Polygon(coords, EXTENT_STYLE);
    }


    /**
     * @return {Function} used to generate GP service classes for use by MapCore map
     */
    getServiceFactory() {
        return function(arg, baseUrl, httpClient) {
            let type = (typeof(arg) === 'string') ?
                arg : (arg && arg.type ? arg.type : null);
            if(!type) throw new Error("Must provide a type or object with a type specified");

            //ignore baseUrl provided by map instance. instead use what is configured
            // inside this application
            // if(!baseUrl) throw new Error("Must provide a base url");

            if(!httpClient) throw new Error("Must provide an http client to use to make requests");
            switch(type) {
                case ItemTypes.LAYER:
                    return this.layerService;
                case ItemTypes.MAP:
                    //go to local node proxy endpoints in case jwt refresh is needed
                    return new MapService(Config.ualUrl, this.httpClient);
                default:
                    //non-maps won't need authentication to transact (no post/put/delete)
                    return ServiceFactory(arg, Config.ualUrl, this.httpClient);
            }
        };
    }


    isValidExtent(extent) {
        if (!extent) return false;
        if( isNaN(extent.minx) || isNaN(extent.maxx) ||
            isNaN(extent.miny) || isNaN(extent.maxy) ) return false;
        extent.maxx *= 1;
        extent.minx *= 1;
        extent.maxy *= 1;
        extent.miny *= 1;

        if(extent.maxx < extent.minx) {
            let min = extent.maxx;
            extent.maxx = extent.minx;
            extent.minx = min;
        } else if( (extent.maxx - extent.minx) < 0.0001 ) {
            if(extent.minx < -179.0) extent.minx -= 1.0;
            else extent.maxx += 1.0;
        }
        if(extent.maxy < extent.miny) {
            let min = extent.maxy;
            extent.maxy = extent.miny;
            extent.miny = min;
        } else if( (extent.maxy - extent.miny) < 0.0001 ) {
            if(extent.miny < -79.0) extent.miny -= 1.0;
            else extent.maxy += 1.0;
        }
        return true;
    }


    private wrapLayer(item : any) : any {
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



    onDataEvent( event : DataEvent ) {
        logger.debug("Map.onDataEvent(" + event.type.toString() + ")");
        switch(event.type) {

            case Events.ON :

                let count = this.data.getData().length;
                let data = event.data.map(d=>this.wrapLayer(d));
                this.map.addLayers(data);

                if(this.extentTimer) {
                    clearTimeout(this.extentTimer);
                }
                this.extentTimer = setTimeout( () => {
                    this.extentTimer = null;
                    this.map.setExtent(this.data.getExtent());
                }, 500);

                break;

            case Events.OFF :
                this.map.removeLayer(event.data[0].id);
                break;

            case Events.VIZ :
                this.map.toggleLayerVisibility(event.data[0].id);
                break;

            case Events.BASE :
                this.map.setBaseLayer(event.data[0]);
                break;
        }
    }

}
