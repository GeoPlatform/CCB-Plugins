import {
    Component, OnInit, OnDestroy, Input, Output, EventEmitter
} from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { Observable, Subject } from 'rxjs';
import {
    catchError, debounceTime, distinctUntilChanged, map, tap, switchMap, merge
} from 'rxjs/operators';
import { fromPromise } from 'rxjs/observable/fromPromise';

declare const L: any;   //needed this way to ensure leaflet-draw is properly imported
import 'leaflet-draw';

import { Config, UtilsService } from 'geoplatform.client';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';
import { ExtentCodec } from './codec';

import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { HttpTypeaheadService } from '../../shared/typeahead';






/**
 * Gazetteer Service to populate Typeahead control
 *
 */
class GazetteerTypeaheadService implements HttpTypeaheadService {

    private service : UtilsService;

    constructor(private http: HttpClient) {
        let client = new NG2HttpClient(http);
        this.service = new UtilsService(Config.ualUrl, client);
    }

    search(term: string) {
        if (term === '') return Observable.of([]);

        return fromPromise(this.service.locate(term))
        .pipe(
            //catch and gracefully handle rejections
            catchError(error => Observable.of([]))
        );
    }
}






@Component({
  selector: 'constraint-extent',
  templateUrl: './extent.component.html',
  styleUrls: ['./extent.component.css']
})
export class ExtentComponent implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : L.LatLngBounds;
    public placeName : string;

    private codec : ExtentCodec = new ExtentCodec();
    private map : L.Map;
    private filterLayer : L.FeatureGroup;
    private handler : any;

    private rectangleStyle = {
        color : '#f00',
        weight : 5,
        opacity : 1.0,
        fill : false,
        clickable : false,
        className : 'map-extent'
    };

    private service : HttpTypeaheadService;


    constructor(private http : HttpClient) {
        this.service = new GazetteerTypeaheadService(http);
    }

    ngOnInit() {
        let value = this.codec.getValue(this.constraints);
        if(value) {
            this.value = this.parseBboxString(value);
        }

        let map = this.map = L.map("extent-constraint-map-container", {
            zoomControl: false,
            center: [38, -96],
            zoom: 3,
            minZoom: 2,
            maxZoom: 21,
            maxBounds: [[-90,-180],[90,180]]
        });

        //load current map state into the map
        this.loadMap();

    }

    ngOnDestroy() {
        if(this.handler)
            this.handler.disable();
        if(this.map) {
            this.map.off();
            this.map.remove();
            this.map = null;
        }
    }

    getCodec() : Codec {
        return this.codec;
    }

    apply() {
        let value = this.value ? this.formatBboxString(this.value) : null;
        let constraint = this.codec.toConstraint(value);
        if(this.value) this.constraints.set(constraint);
        else this.constraints.unset(constraint);
    }


    loadMap () {

        let osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        let osmAttrib='Map data (c) <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
        let baseLayer = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 19, attribution: osmAttrib});
        this.map.addLayer(baseLayer);

        //layer for constraint MBR
        this.filterLayer = L.featureGroup();
        this.map.addLayer(this.filterLayer);

        this.onValueChange();

        //tool for MBR
        L.drawLocal.draw.toolbar.buttons.rectangle = "Draw an MBR to filter results";
        var drawControl = new L.Control.Draw({
            draw: {
                polyline : false, polygon : false,
                circle : false, marker : false,
                circleMarker: false,
                rectangle : {
                    shapeOptions: this.rectangleStyle
                }
            }
        });
        this.map.addControl(drawControl);

        //keep reference to the handler for the mbr tool
        var toolbars = drawControl._toolbars;
        var toolbar = null;
        for(var key in toolbars) {
            if(toolbars.hasOwnProperty(key)) {
                if(toolbars[key]._modes) {
                    toolbar = toolbars[key];
                    break;
                }
            }
        }
        this.handler = toolbar ? toolbar._modes.rectangle.handler : null;

        //when a new extent is drawn, fire event so it can be used to
        // trigger a search or whatever is needed
        this.map.on('draw:created', (e) => {
            let evt = e as any;
            //add layer to feature group
            var type = evt.layerType,
                layer = evt.layer;
            this.filterLayer.addLayer(layer);
            this.handler.disable();
            this.value = layer.getBounds();
        });

        //on new extent draw
        this.map.on("draw:drawstart", (e) => {
            //remove previous features
            this.clear();
        });

    }

    clear () {
        this.filterLayer.clearLayers();
        this.value = null;
    }

    /**
     * @param {string} bbox - bbox string format 'west,south,east,north'
     * @return {L.LatLngBounds}
     */
    parseBboxString(bbox) {
        if(!bbox || !bbox.length) return null;
        let value = bbox.split(',');
        return new L.LatLngBounds([
            [value[1]*1.0, value[0]*1.0],
            [value[3]*1.0, value[2]*1.0]
        ])
    }

    /**
     * @param {L.LatLngBounds} bounds
     */
    formatBboxString(bounds) {
        return bounds ? bounds.toBBoxString() : null;
    }


    getLocationLabel (result) {
        return result.label;
    }

    selectLocation (item) {
        //TODO convert item into a bbox and set as this.value and render to map
        if(item) {
            this.clear();
            this.value = L.latLng(item.latitude, item.longitude).toBounds(1000);
            this.onValueChange();
        }
    }

    onValueChange() {
        if(this.value) {
            this.map.fitBounds(this.value);
            let mbr = L.rectangle(this.value, this.rectangleStyle);
            this.filterLayer.addLayer(mbr);
        }
    }
}
