
import { ItemTypes } from "geoplatform.client";

import { environment } from '../../environments/environment';

export class ItemHelper {

    constructor() { }

    /**
     * @param {any} item - either GP item or string type
     * @return {boolean}
     */
    static isAsset(item : any) {
        if(!item) return false;
        let type = null;
        if(typeof(item) === 'string')  type = item as string;
        else if(item.type) type = item.type;
        return type && (
            type === ItemTypes.DATASET || type === ItemTypes.SERVICE   ||
            type === ItemTypes.LAYER   || type === ItemTypes.MAP       ||
            type === ItemTypes.GALLERY || type === ItemTypes.COMMUNITY
        );
    }

    /**
     * @param {any} item - either GP item or string type
     * @return {string} url of icon for the type
     */
    static getIcon(item : any) :string {
        if(!item) return null;
        if(typeof(item) === 'string')
            return this.determineIconType(item as string);
        else if(item.type)
            return this.determineIconType(item.type);
        return null;
    }

    /**
     * @param {any} item - either GP item
     * @return {string} label for the item
     */
    static getLabel(item : any) {
        if(!item || !item.type) return 'Unknown type';

        let type = item.type;
        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.LAYER :
            case ItemTypes.MAP :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
                return item.label || item.title || "Un-titled resource";

            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON :
                return item.label || item.name || "Un-titled resource";

            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return item.label || item.prefLabel || "Un-titled resource";


            case ItemTypes.CONTACT :
                return (item.fullName || 'Unnamed contact') +
                    ( item.orgName ? " (" + item.orgName + ")" : '');

            default: return 'Unknown type';
        }
    }

    /**
     * @param {any} item - either GP item or string type
     * @return {string} label for the item's type
     */
    static getTypeLabel(item:any) {
        if(!item) return 'Unknown Resource Type';

        let type : string = null;
        if(typeof(item) === 'string') type = item as string;
        else if(item.type) type = item.type;
        else return null;

        switch(type) {
            case ItemTypes.DATASET : return "Dataset";
            case ItemTypes.SERVICE : return "Service";
            case ItemTypes.ORGANIZATION : return "Organization";
            case ItemTypes.CONTACT : return "Contact";
            case ItemTypes.PERSON : return "Person";
            case ItemTypes.CONCEPT : return "Concept";
            case ItemTypes.CONCEPT_SCHEME : return "Concept Scheme";
            case ItemTypes.LAYER : return "Layer";
            case ItemTypes.MAP : return "Map";
            case ItemTypes.GALLERY : return 'Gallery';
            case ItemTypes.COMMUNITY : return 'Community';
            default: return 'Unknown Resource Type';
        }
    }


    /**
     * @param {any} item - either GP item or string type
     * @return {string} key (plural) for the item's type
     */
    static getTypeKey(item:any) {
        if(!item) return null;

        let type : string = null;
        if(typeof(item) === 'string') type = item as string;
        else if(item.type) type = item.type;
        else return null;

        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return type.split(':')[1].toLowerCase() + 's';

            case ItemTypes.LAYER :
            case ItemTypes.MAP :
                return type.toLowerCase() + 's';

            case ItemTypes.GALLERY : return 'galleries';
            case ItemTypes.COMMUNITY : return 'communities';
            case ItemTypes.CONTACT : return 'contacts'; //instead of "vcards"

            default: return 'unsupported';
        }
    }


    /**
     * @param {string} type - item type
     * @return {string} string path to the type's icon
     */
    static determineIconType(type : string) {

        let name = 'dataset';

        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONTACT :
            case ItemTypes.PERSON :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                name = type.split(':')[1].toLowerCase();
                break;

            case ItemTypes.LAYER :
            case ItemTypes.MAP :
            case ItemTypes.GALLERY :
            case ItemTypes.COMMUNITY :
                name = type.toLowerCase();
                break;
        }

        return `${environment.assets}/icons/${name}.svg`;
    }

}
