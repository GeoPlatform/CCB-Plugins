import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { HttpClient } from '@angular/common/http';


import {
    Config, ItemTypes, MapService, LayerService, ServiceFactory
} from "@geoplatform/client";

import { NG2HttpClient } from '@geoplatform/client/angular';

import {
    MapFactory, MapInstance, OSM, LayerFactory, DefaultBaseLayer
} from "@geoplatform/mapcore";


import * as L from 'leaflet';
// var L = require('leaflet');
import * as esri from "esri-leaflet";
import "leaflet.vectorgrid";

import { ResetExtentControl } from './reset-extent';


const EXTENT_STYLE = {
    color: "transparent",
    weight: 1,
    fill: true,
    fillColor: "#777777",
    fillOpacity: 0.7
};

interface Bbox {
    minx: number;
    maxx: number;
    miny: number;
    maxy: number;
}

@Component({
  selector: 'gpid-coverage-map',
  templateUrl: './coverage-map.component.html',
  styleUrls: ['./coverage-map.component.less']
})
export class CoverageMapComponent implements OnInit, OnChanges {

    @Input() extent : Bbox;
    @Input() mapId : string;
    @Input() layerId : string;

    public errors : any[] = [] as any[];
    public warnings: any[] = [] as any[];

    private layerService : LayerService;
    private httpClient : NG2HttpClient;
    private map : MapInstance;
    private extentControl : any;
    private extentLayer : L.Layer;

    constructor(private http : HttpClient) {
        this.httpClient = new NG2HttpClient(this.http);
        this.layerService = new LayerService(Config.ualUrl, this.httpClient);
    }

    ngOnInit() {

        this.initMap().then( () => {

            if(this.mapId) {
                this.loadMap(this.mapId);

            } else {
                this.setDefaultBaseLayer().then( () => {

                    if(this.layerId) {
                        this.loadLayer(this.layerId);

                    } else if(this.isValidExtent(this.extent)) {
                        this.setExtent(this.extent);

                    }
                });
            }
        });
    }

    ngOnChanges(changes: SimpleChanges) {
        if(!this.map) return;

        if(changes.mapId) {
            this.loadMap(changes.mapId.currentValue)

        } else if(changes.layerId) {
            //TODO remove all layers...
            this.loadLayer(changes.layerId.currentValue)

        } else if(this.isValidExtent(changes.extent)) {
            this.setExtent(changes.extent.currentValue);
        }
    }

    ngOnDestroy() {
        if(this.map) {
            this.map.destroyMap();
            this.map = null;
        }
    }

    /**
     *
     */
    initMap() : Promise<void> {

        return new Promise<void>( (resolve, reject) => {

            let map = L.map("item-coverage-map", {
                zoomControl: true,
                center: [38, -96],
                zoom: 3,
                minZoom: 1,
                maxZoom: 21,
                maxBounds: [[-90,-180],[90,180]]
            });

            if(Config.leafletPane) {
                //create pane for all "overlay" layers to be added to...
                // and put it just atop the default tile pane
                map.createPane(Config.leafletPane);
                map.getPane(Config.leafletPane).style.zIndex = '250';
            }

            //...
            this.extentControl = new ResetExtentControl();
            this.extentControl.addTo(map);


            this.map = MapFactory.get('GPMVMAP');
            this.map.setMap(map);
            this.map.setErrorHandler( (error) => {
                this.errors.push(error);
            });

            let httpClient = new NG2HttpClient(this.http);
            // httpClient.setAuthToken(function() {
            //     //uses token cached by ng-common's AuthenticationService
            //     let token = AuthenticationService.getJWTfromLocalStorage();
            //     return token;
            // });
            this.map.setHttpClient(httpClient);
            this.map.setServiceFactory(this.getServiceFactory());

            //configure layer factory with service created here so it uses proper
            // env vars and http client
            LayerFactory.setLayerService(this.layerService);

            resolve();

        });
    }

    /**
     * @return {Promise}
     */
    setDefaultBaseLayer() {
        return DefaultBaseLayer.get(this.layerService).then(baseLayer => {
            if(this.mapId) {
                //only show warning if this happens loading a map
                this.warnings.push(new Error("Using default base layer"));
            }
            this.map.setBaseLayer(baseLayer);
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

            if(map.extent) {
                this.extentControl.setExtent([
                    [map.extent.miny, map.extent.minx],
                    [map.extent.maxy, map.extent.maxx]
                ]);
            }

            return map;
        })
        .catch(e => {

            console.log("Unable to load map '" + id + "' because " + e.message);
            console.log(e);

            this.setDefaultBaseLayer();

            //display error message to user
            this.errors.push(e);

            return Promise.resolve(null);
        });
    }

    /**
     * @param {string} id - GP Layer object to load
     */
    loadLayer(id) {
        this.layerService.get(id).then( layer => {
            if(layer.extent) {
                this.setExtent(layer.extent);
            }
            this.map.addLayers(layer);
        })
        .catch( e => {
            //TODO display error about loading layer
        });
    }

    /**
     * @param {object} extent - GP extent object ( { minx: ..., miny: ..., maxx: ..., maxy: ...} )
     */
    setExtent(extent) {
        if(extent) {
            let bounds = this.getBoundsFor(extent);
            if(!bounds) return;

            this.extentControl.setExtent(bounds);

            //MapCore Map expects extent, not array
            this.map.setExtent(extent);

            if(!this.mapId && !this.layerId) {
                //if not loading a map or layer, show extent layer
                if(!this.extentLayer) {
                    this.extentLayer = new L.FeatureGroup();
                    this.extentLayer.addTo(this.map.getMap());
                }
                let l = this.renderBounds(bounds, !!this.mapId||!!this.layerId);
                if(!l) {
                    console.log("WARN: Could not render extent as a path layer");
                    return;
                }
                (this.extentLayer as any).addLayer(l);

            } else if(this.extentLayer) {
                //if loading a map or layer, don't show extent layer
                this.extentLayer.remove();
                this.extentLayer = null;
            }
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

}
