import { Component, OnInit, Input } from '@angular/core';

import { Layer as LayerAsset } from "@geoplatform/client";

const OSM_RS_TYPE = "http://www.geoplatform.gov/ont/openlayer/OSMLayer";
const MBVT_RS_TYPE = "http://www.geoplatform.gov/ont/openlayer/MapBoxVectorTileLayer";


@Component({
  selector: 'gpid-layer-details',
  templateUrl: './layer-details.component.html',
  styleUrls: ['./layer-details.component.less']
})
export class LayerDetailsComponent implements OnInit {

    @Input() item : LayerAsset;

    constructor() { }

    ngOnInit() {
    }


    validate( property : string ) : boolean {

        switch(property) {
            case 'layerType':
            let type = this.item.layerType;
            if(type) return true;
            if(
                this.hasResourceType(OSM_RS_TYPE) ||
                this.hasResourceType(MBVT_RS_TYPE)
            ) return true;
            return false;


            case 'layerName':
            let name = this.item.layerName;
            if(name) return true;
            if(
                this.hasResourceType(OSM_RS_TYPE) ||
                this.hasResourceType(MBVT_RS_TYPE)
            ) return true;
            return false;

            case 'supportedFormats' :
            let formats = this.item.supportedFormats;
            if(formats && formats.length > 0) return true;
            if(
                this.hasResourceType(OSM_RS_TYPE) ||
                this.hasResourceType(MBVT_RS_TYPE)
            ) return true;
            return false;

        }

        return false;
    }

    hasResourceType( type : string ) : boolean {
        let types = this.item.resourceTypes || [];
        if(!types.length) return false;
        return types.indexOf(type) >= 0;
    }

}
