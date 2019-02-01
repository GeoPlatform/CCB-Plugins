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

    private layerService : LayerService;
    private httpClient : NG2HttpClient;
    private map : MapInstance;

    constructor(private http : HttpClient) {
        this.httpClient = new NG2HttpClient(this.http);
        this.layerService = new LayerService(Config.ualUrl, this.httpClient);
    }

    ngOnInit() {

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

        if(this.mapId) {
            this.map.loadMap(this.mapId);
        } else {
            OSM.get(this.layerService).then(osm => {
                this.map.setBaseLayer(osm);

                if(this.layerId) {
                    this.layerService.get(this.layerId)
                    .then( layer => {
                        this.map.addLayers(layer);
                    })
                    .catch( e => {
                        //TODO display error about loading layer
                    });

                }

                if(this.extent) {
                    var bbox = [
                        [ this.extent.minx, this.extent.miny ],
                        [ this.extent.maxx, this.extent.maxy ]
                    ];
                    this.map.setExtent(bbox);
                }

            }).catch(e => {
                console.log("Unable to get OSM base layer because " + e.message);
            });
        }
    }

    ngOnChanges(changes: SimpleChanges) {
        if(!this.map) return;

        if(changes.mapId) {
            this.map.loadMap(changes.mapId.currentValue)
            .catch(e => {
                console.log("Unable to load map '" +
                    changes.mapId.currentValue + "' because " + e.message);
                console.log(e);
            });

        } else if(changes.layerId) {

            //TODO remove all layers...

            this.layerService.get(changes.layerId.currentValue)
            .then( layer => {
                this.map.addLayers(layer);
            })
            .catch( e => {
                //TODO display error about loading layer
            });

        }

        if(changes.extent) {
            let extent = changes.extent.currentValue;
            var bbox = [
                [ extent.minx, extent.miny ],
                [ extent.maxx, extent.maxy ]
            ];
            this.map.setExtent(bbox);
        }
    }

    ngOnDestroy() {
        if(this.map) {
            this.map.remove();
            this.map = null;
        }
    }

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
