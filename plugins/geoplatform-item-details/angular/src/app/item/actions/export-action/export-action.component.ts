import { Component, OnInit, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { NG2HttpClient } from "../../../shared/http-client";



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
    private itemService : ItemService;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {
    }

    getFormats() {
        if(!this.item || !this.item.type ||
            !FORMATS[this.item.type])
            return [];

        return FORMATS[this.item.type];
    }

    doAction(format) {
        if(!this.item) return;

        this.itemService.exportItem(this.item, format)
        .then( (response) => {
            //TODO handle response so that file can be downloaded.
            //this might mean opening the URL in a new window instead
            // of calling itemservice
        })
        .catch(e => {
            //show error message
        });
    }


}
