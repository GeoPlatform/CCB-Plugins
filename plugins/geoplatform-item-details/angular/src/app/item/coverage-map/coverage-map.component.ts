import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { HttpClient } from '@angular/common/http';


import {
    Config, ItemTypes, MapService, LayerService, ServiceFactory
} from "geoplatform.client";

import {
    MapFactory, MapInstance, OSM, LayerFactory, L
} from "geoplatform.mapcore";


// // import * as L from 'leaflet';
// var L = require('leaflet');
import * as esri from "esri-leaflet";



import { ResetExtentControl } from './reset-extent';
import { NG2HttpClient } from '../../shared/http-client';


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

    constructor(private http : HttpClient) {
        this.httpClient = new NG2HttpClient(this.http);
        this.layerService = new LayerService(Config.ualUrl, this.httpClient);
    }

    ngOnInit() {

        this.initMap();

        if(this.mapId) {
            this.loadMap(this.mapId);

        } else {
            this.setDefaultBaseLayer()
            .then( () => {

                if(this.layerId) {
                    this.loadLayer(this.layerId);

                } else if(this.extent) {
                    this.setExtent(this.extent);

                }
            });
        }
    }

    ngOnChanges(changes: SimpleChanges) {
        if(!this.map) return;

        if(changes.mapId) {
            this.loadMap(changes.mapId.currentValue)

        } else if(changes.layerId) {
            //TODO remove all layers...
            this.loadLayer(changes.layerId.currentValue)

        } else if(changes.extent) {
            this.setExtent(changes.extent.currentValue);
        }
    }

    ngOnDestroy() {
        if(this.map) {
            this.map.remove();
            this.map = null;
        }
    }

    /**
     *
     */
    initMap() {

        let map = L.map("item-coverage-map", {
            zoomControl: true,
            center: [38, -96],
            zoom: 5,
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
        let layerService = ServiceFactory(ItemTypes.LAYER, Config.ualUrl, this.httpClient);
        LayerFactory.setLayerService(layerService);
    }

    /**
     * @return {Promise}
     */
    setDefaultBaseLayer() {
        return OSM.get(this.layerService).then(osm => {
            if(this.mapId) {
                //only show warning if this happens loading a map
                this.warnings.push(new Error("Using OpenStreetMap default base layer"));
            }
            this.map.setBaseLayer(osm);
            return osm;
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
        this.layerService.get(id)
        .then( layer => {
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
            var bbox = [ [ extent.miny, extent.minx ], [ extent.maxy, extent.maxx ] ];
            this.extentControl.setExtent(bbox);

            //MapCore Map expects extent, not array
            this.map.setExtent(extent);
        }
    }

    /**
     * @return {Function} used to generate GP service classes for use by MapCore map
     */
    getServiceFactory() {
        return function(arg, baseUrl, httpClient) {
            let type = (typeof(arg) === 'string') ?
                arg : (arg && arg.type ? arg.type : null);
            if(!type) throw new Error("Must provide a type or object with a type specified");
            if(!baseUrl) throw new Error("Must provide a base url");
            if(!httpClient) throw new Error("Must provide an http client to use to make requests");
            switch(type) {
                case ItemTypes.MAP:
                    //go to local node proxy endpoints in case jwt refresh is needed
                    return new MapService(Config.ualUrl, this.httpClient);
                default:
                    //non-maps won't need authentication to transact (no post/put/delete)
                    return ServiceFactory(arg, baseUrl, this.httpClient);
            }
        };
    }

}