import { Component, OnInit, Input } from '@angular/core';
import { Config, ItemTypes } from "geoplatform.client";

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
            case ItemTypes.MAP : this.openMap(); break;
            case ItemTypes.DATASET :
                // this.openDataset(); break;
            case ItemTypes.SERVICE :
            case ItemTypes.LAYER :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONTACT :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
            default: this.openInObjectEditor();
        }
    }

    getLabel() : string {
        if(!this.item || !this.item.type) return '';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.MAP : return 'Open Map';
            case ItemTypes.DATASET :
                // if(this.item.source && this.item.source.uri) return "View Metadata";
            case ItemTypes.SERVICE :
            case ItemTypes.LAYER :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONTACT :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
            default: return 'Open';
        }
    }

    getDescription() : string {
        if(!this.item || !this.item.type) return '';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.MAP : return 'Open Map';
            case ItemTypes.DATASET :
                // return 'View the source metadata for this Dataset';
            case ItemTypes.SERVICE :
            case ItemTypes.LAYER :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONTACT :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
            default: return 'View all of this resource\'s details using GeoPlatform Object Editor';
        }
    }


    /**
     *
     */
    openDataset() {
        if(!this.item.source || !this.item.source.uri) return;
        window.open(this.item.source.uri, '_blank');
    }


    /**
     *
     */
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

    /**
     *
     */
    openInObjectEditor() {
        let url = Config.ualUrl.replace('ual','oe') + '/view/' + this.item.id;
        window.open(url, '_blank');
    }

}
