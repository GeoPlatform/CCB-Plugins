import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { ItemTypes, Config, ItemService } from "geoplatform.client";
import { RPMService } from 'geoplatform.rpm/src/iRPMService'


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

    public formats : any[];

    constructor( private rpm: RPMService) { }

    ngOnInit() {
    }

    ngOnChanges(changes : SimpleChanges) {
        if(changes && changes.item) {
            this.determineFormats(changes.item.currentValue);
        }
    }

    determineFormats(item) {
        if(!item || !item.type || !FORMATS[item.type]) this.formats = [];
        this.formats = FORMATS[item.type];
    }

    doAction(format) {
        if(!this.item || !this.service) {
            console.log("Warning: ExportAction has no item or service");
            return;
        }
        window.open(Config.ualUrl + '/api/items/' + this.item.id + '/export?format=' + format, '_blank');

        // RPM : Asset Exported
        const TYPE = this.item.type.replace(/.+:/,'')
        this.rpm.logEvent(TYPE, 'Exported', this.item.id);
    }


}
