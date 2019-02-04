import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from "geoplatform.client";

import { environment } from '../../../../environments/environment';

const AGOL_RES_TYPE = "http://www.geoplatform.gov/ont/openmap/AGOLMap";
const GP_RES_TYPE = 'http://www.geoplatform.gov/ont/openmap/GeoplatformMap';



@Component({
  selector: 'gpid-primary-action',
  templateUrl: './primary-action.component.html',
  styleUrls: ['./primary-action.component.less']
})
export class PrimaryActionComponent implements OnInit {

    @Input() item : any;


    constructor() { }

    ngOnInit() {
    }


    doAction() {
        if(!this.item || !this.item.type) return;

        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET : break;
            case ItemTypes.SERVICE : break;
            case ItemTypes.LAYER : break;
            case ItemTypes.MAP : this.openMap(); break;
            case ItemTypes.GALLERY : break;
            case ItemTypes.COMMUNITY : break;
            case ItemTypes.ORGANIZATION : break;
            case ItemTypes.CONTACT : break;
            case ItemTypes.PERSON : break;
            case ItemTypes.CONCEPT : break;
            case ItemTypes.CONCEPT_SCHEME : break;
            default: break;
        }
    }

    getLabel() : string {
        if(!this.item || !this.item.type) return '';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET : return 'Do Something';
            case ItemTypes.SERVICE : return 'Do Something';
            case ItemTypes.LAYER : return 'Do Something';
            case ItemTypes.MAP : return 'Open Map';
            case ItemTypes.GALLERY : return 'Do Something';
            case ItemTypes.COMMUNITY : return 'Do Something';
            case ItemTypes.ORGANIZATION : return 'Do Something';
            case ItemTypes.CONTACT : return 'Do Something';
            case ItemTypes.PERSON : return 'Do Something';
            case ItemTypes.CONCEPT : return 'Do Something';
            case ItemTypes.CONCEPT_SCHEME : return 'Do Something';
            default: return 'Do Something';
        }
    }

    getDescription() : string {
        if(!this.item || !this.item.type) return '';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.SERVICE : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.LAYER : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.MAP : return 'Open Map';
            case ItemTypes.GALLERY : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.COMMUNITY : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.ORGANIZATION : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.CONTACT : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.PERSON : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.CONCEPT : return 'This will be helpful text indicating what will happen if you click the associated button';
            case ItemTypes.CONCEPT_SCHEME : return 'This will be helpful text indicating what will happen if you click the associated button';
            default: return 'This will be helpful text indicating what will happen if you click the associated button';
        }
    }


    openMap() {

        let resTypes = this.item.resourceTypes;
        if(!Array.isArray(resTypes)) {
            console.log("Warning: Map has no resource types specified");
            return;
        }

        // GeoPlatform OpenMaps...
        if(~resTypes.indexOf(GP_RES_TYPE)) {
            let prefix = '', ext = '.gov';
            let env = environment.env;
            if('dev' === env || 'sit' === env) {
                prefix = 'sit-';
                ext = '.us';
            } else if('stg' === env)
                prefix = 'stg-';
            let url = 'https://' + prefix + 'viewer.geoplatform' + ext + '/?id=' + this.item.id;
            window.open(url, "_blank");
            return;    
        }

        //all other map types...

        if(!this.item.landingPage) {
            console.log("Warning: External map has no home page specified");
            return;
        }
        window.open(this.item.landingPage, "_blank");
    }

}
