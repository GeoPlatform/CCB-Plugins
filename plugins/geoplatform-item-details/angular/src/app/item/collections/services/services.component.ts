import { Component, OnInit, Input } from '@angular/core';

import { ItemTypes } from "@geoplatform/client";
import { OSM } from "@geoplatform/mapcore";

const MBVTRT = "http://www.geoplatform.gov/ont/openlayer/MapBoxVectorTileLayer";



@Component({
  selector: 'gpid-services',
  templateUrl: './services.component.html',
  styleUrls: ['./services.component.less']
})
export class ServicesComponent implements OnInit {

    @Input() item : any;
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    isServiceRequired() : boolean {
        if(!this.item) return false;

        if(ItemTypes.LAYER === this.item.type) {

            if(OSM.test(this.item))
                return false;
            else {
                let rts = this.item.resourceTypes || [];
                if(~rts.indexOf(MBVTRT)) {
                    return false;
                }

            }
        }


        return true;
    }

}
