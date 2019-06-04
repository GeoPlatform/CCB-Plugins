import { Component, OnInit, Input } from '@angular/core';
import { Config, ItemTypes } from "geoplatform.client";

import { environment } from '../../../../environments/environment';

const AGOL_RES_TYPE = "http://www.geoplatform.gov/ont/openmap/AGOLMap";
const GP_MAP_TYPE = 'http://www.geoplatform.gov/ont/openmap/GeoplatformMap';



@Component({
  selector: 'gpid-primary-action',
  templateUrl: './primary-action.component.html',
  styleUrls: ['./primary-action.component.less']
})
export class PrimaryActionComponent implements OnInit {

    @Input() item : any;
    public warning : string = null;

    constructor() { }

    ngOnInit() {
    }

    canDoAction() {
        if(!this.item || !this.item.type) return false;
        let type = this.item.type;
        switch(type) {
            case ItemTypes.DATASET:
                if(!this.item.source || !this.item.source.uri) {
                    setTimeout(() => {
                        this.warning = "Dataset has no source link";
                    });
                    return false;
                }
                return true;
            case ItemTypes.MAP:
                if(!this.getMapUrl()) {
                    setTimeout(() => {
                        this.warning = "Map is missing specializations and/or external links";
                    });
                    return false;
                }
                return true;
            default: return true;
        }
    }

    doAction() {
        if(!this.item || !this.item.type) return;

        let type = this.item.type;
        switch(type) {
            case ItemTypes.MAP : this.openMap(); break;
            // case ItemTypes.DATASET :
            // case ItemTypes.SERVICE :
            // case ItemTypes.LAYER :
            // case ItemTypes.GALLERY :
            // case ItemTypes.COMMUNITY :
            // case ItemTypes.ORGANIZATION :
            // case ItemTypes.CONTACT :
            // case ItemTypes.PERSON :
            // case ItemTypes.CONCEPT :
            // case ItemTypes.CONCEPT_SCHEME :
            default: this.openInObjectEditor();
        }
    }

    getLabel() : string {
        if(!this.item || !this.item.type) return '';

        let type = this.item.type;
        switch(type) {
            case ItemTypes.MAP : return 'Open Map';
            // case ItemTypes.DATASET :
            // case ItemTypes.SERVICE :
            // case ItemTypes.LAYER :
            // case ItemTypes.GALLERY :
            // case ItemTypes.COMMUNITY :
            // case ItemTypes.ORGANIZATION :
            // case ItemTypes.CONTACT :
            // case ItemTypes.PERSON :
            // case ItemTypes.CONCEPT :
            // case ItemTypes.CONCEPT_SCHEME :
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
        let url = this.getMapUrl();
        if(url) window.open(url, '_blank');
        else console.log("Warning: Map has insufficient info to open");
    }

    getMapUrl() {
        let resTypes = this.item.resourceTypes || [];

        if(~resTypes.indexOf(GP_MAP_TYPE)) {
            return Config.ualUrl.replace('ual','viewer') + '/?id=' + this.item.id;
        }

        //all other map types...

        if(this.item.landingPage || this.item.href) {
            return this.item.landingPage || this.item.href;
        }

        return null;
    }

    /**
     *
     */
    openInObjectEditor() {
        let url = Config.ualUrl.replace('ual','oe') + '/view/' + this.item.id;
        window.open(url, '_blank');
    }

}
