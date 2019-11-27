import { Component, OnInit, Input } from '@angular/core';
import { Config, ItemTypes } from "@geoplatform/client";

import { ItemHelper } from '../../../shared/item-helper';
import { environment } from '../../../../environments/environment';

const AGOL_RES_TYPE = "http://www.geoplatform.gov/ont/openmap/AGOLMap";
const GP_MAP_TYPE = 'http://www.geoplatform.gov/ont/openmap/GeoplatformMap';



@Component({
  selector: 'gpid-primary-action',
  templateUrl: './primary.component.html',
  styleUrls: ['./primary.component.less']
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
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.COMMUNITY :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON :
            case ItemTypes.APPLICATION :
            case ItemTypes.WEBSITE :
            case ItemTypes.TOPIC :
            case ItemTypes.GALLERY :
            case ItemTypes.CONTACT :
                return !!(this.item.href || this.item.landingPage);
            case ItemTypes.MAP:
                if(!this.getMapUrl()) {
                    setTimeout(() => {
                        this.warning = "Map is missing specializations and/or external links";
                    });
                    return false;
                }
                return true;
            default: return false;
        }
    }

    doAction() {
        if(!this.item || !this.item.type) return;

        let type = this.item.type;
        switch(type) {
            case ItemTypes.MAP          : this.openMap(); break;
            case ItemTypes.DATASET      : this.downloadDataset(); break;
            case ItemTypes.SERVICE      :
            case ItemTypes.COMMUNITY    :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON       :
            case ItemTypes.APPLICATION  :
            case ItemTypes.WEBSITE      :
            case ItemTypes.TOPIC        :
            case ItemTypes.CONTACT      : 
            case ItemTypes.GALLERY      : this.goToItemSite(); break;

            // NO PRIMARY ACTION FOR THESE TYPES AT THIS TIME
            // case ItemTypes.LAYER :
            // case ItemTypes.CONTACT :
            // case ItemTypes.CONCEPT :
            // case ItemTypes.CONCEPT_SCHEME :
            // default: this.openInObjectEditor();
        }
    }

    getLabel() : string {
        if(!this.item || !this.item.type) return '';
        if(ItemTypes.DATASET === this.item.type) {
            return 'Download Dataset';
        }
        return 'Open ' + ItemHelper.getTypeLabel(this.item)
    }

    getDescription() : string {
        if(!this.item || !this.item.type) return '';
        if(ItemTypes.DATASET === this.item.type) {
            return "Go to this Dataset's download site";
        }
        return "Go to the access site or home page for this item";
    }

    getIconClass() {
        let value = 'fas fa-external-link-alt';
        // if(!this.item || !this.item.type) return value;
        // let type = this.item.type;
        // switch(type) {
        //     case ItemTypes.MAP :
        //     case ItemTypes.DATASET :
        //     case ItemTypes.SERVICE :
        //     case ItemTypes.LAYER :
        //     case ItemTypes.GALLERY :
        //     case ItemTypes.COMMUNITY :
        //     case ItemTypes.ORGANIZATION :
        //     case ItemTypes.CONTACT :
        //     case ItemTypes.PERSON :
        //     case ItemTypes.CONCEPT :
        //     case ItemTypes.CONCEPT_SCHEME :
        // }
        return value;
    }


    /**
     *
     */
    goToItemSite() {
        if(!this.item) return;
        let href = this.item.href || this.item.landingPage;
        if(href) window.open(href, '_blank');
        else {
            console.log('[WARN] Cannot navigate to site for Item, no site configured');
        }
        // if(!this.item.source || !this.item.source.uri) return;
        // window.open(this.item.source.uri, '_blank');
    }

    downloadDataset() {
        if(!this.item) return;
        let href = this.item.href;
        if(href) window.open(href, '_blank');
        else {
            console.log('[WARN] Cannot navigate to download site for Dataset, no site configured');
        }
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
