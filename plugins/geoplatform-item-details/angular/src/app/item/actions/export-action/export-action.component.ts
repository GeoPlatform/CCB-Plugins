import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, Config, ItemService } from "geoplatform.client";


const FORMATS = buildFormats();


function buildFormats() {
    let result = {};

    result[ItemTypes.DATASET] = [
        {label: "ISO19139", value: "iso19139"},
        {label: "ISO19115", value: "iso19115"},
        {label: "GeoPlatform ISO19139 Profile", value: "gpfm"}
    ];
    result[ItemTypes.SERVICE] = [
        {label: "ISO19139", value: "iso19139"},
        {label: "ISO19115", value: "iso19115"},
        {label: "GeoPlatform ISO19139 Profile", value: "gpfm"}
    ];
    result[ItemTypes.LAYER] = [
        {label: "ISO19139", value: "iso19139"}
    ];
    result[ItemTypes.MAP] = [
        {label: "ISO19139", value: "iso19139"},
        {label: "KML", value: "kml"},
        {label: "Web Map Context", value: "wmc"}
    ];
    result[ItemTypes.GALLERY] = [
        {label: "ISO19139", value: "iso19139"}
    ];
    result[ItemTypes.COMMUNITY] = [
        {label: "ISO19139", value: "iso19139"}
    ];
    return result;
}



@Component({
  selector: 'gpid-export-action',
  templateUrl: './export-action.component.html',
  styleUrls: ['./export-action.component.less']
})
export class ExportActionComponent implements OnInit {

    @Input() item : any;
    @Input() service : ItemService;

    constructor() { }

    ngOnInit() {
    }

    getFormats() {
        if(!this.item || !this.item.type ||
            !FORMATS[this.item.type])
            return [];

        return FORMATS[this.item.type];
    }

    doAction(format) {
        if(!this.item || !this.service) return;
        window.open(Config.ualUrl + '/api/items/' + this.item.id + '/export?format=' + format, '_blank');
    }


}
