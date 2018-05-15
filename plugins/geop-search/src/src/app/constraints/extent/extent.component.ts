import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import * as Leaflet             from 'leaflet';
import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';
import { ExtentCodec } from './codec';

@Component({
  selector: 'constraint-extent',
  templateUrl: './extent.component.html',
  styleUrls: ['./extent.component.css']
})
export class ExtentComponent implements OnInit, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    public value : { minx: number; maxx: number; miny: number; maxy: number; };
    public placeName : string;

    private codec : ExtentCodec = new ExtentCodec();
    private map : Leaflet.Map;

    constructor() { }

    ngOnInit() {
        let value = this.codec.getValue(this.constraints);
        if(value) {
            let coords = value.split(',');
            this.value = {
                minx : coords[0]*1.0,
                miny : coords[1]*1.0,
                maxx : coords[2]*1.0,
                maxy : coords[3]*1.0
            };
        }

        let map = this.map = Leaflet.map("extent-constraint-map-container", {
            zoomControl: false,
            center: [38, -96],
            zoom: 5,
            minZoom: 2,
            maxZoom: 21,
            maxBounds: [[-90,-180],[90,180]]
        });

        //load current map state into the map
        this.loadMap();


    }

    ngOnDestroy() {
        if(this.map) {
            this.map.remove();
            this.map = null;
        }
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        if(!this.value) return;
        let value = this.value.minx + ',' + this.value.miny + ',' +
            this.value.maxx + ',' + this.value.maxy;
        let constraint = this.codec.toConstraint(value);
        this.constraints.set(constraint);
    }


    loadMap () {

        let osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        let osmAttrib='Map data (c) <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
        let baseLayer = new Leaflet.TileLayer(osmUrl, {minZoom: 1, maxZoom: 19, attribution: osmAttrib});
        this.map.addLayer(baseLayer);

        // let extent = this.service.getExtent();
        if(this.value) {
            let extent = new Leaflet.LatLngBounds([
                [this.value.miny, this.value.miny],
                [this.value.maxy, this.value.maxx]
            ]);
            this.map.fitBounds(extent);
        }
    }

}
