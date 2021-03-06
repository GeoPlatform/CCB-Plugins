
import { ItemTypes, ItemTypeLabels } from "@geoplatform/client";

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
            type === ItemTypes.GALLERY || type === ItemTypes.COMMUNITY ||
            type === ItemTypes.APPLICATION || type === ItemTypes.TOPIC ||
            type === ItemTypes.WEBSITE || type === ItemTypes.ORGANIZATION ||
            type === ItemTypes.PERSON
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

            case ItemTypes.ORGANIZATION :
            case ItemTypes.PERSON :
                return item.label || item.name || "Un-titled resource";

            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return item.label || item.prefLabel || "Un-titled resource";

            case ItemTypes.CONTACT :
                if(item.label) return item.label;
                let fn = item.fullName || item.fn || '';
                let pt = item.positionTitle || '';
                let on = item.orgName || item['organization-name'] || '';
                let label = fn + (fn.length?' - ':'') + pt + (pt.length?' - ':'') + on;
                //if none of those fields have been provided, default to email or placeholder
                if(!label.length) label = item.email || 'Untitled Contact';
                return label;

            default: return item.label || item.title || "Un-titled resource";
        }
    }

    /**
     * @param {any} item - either GP item or string type
     * @return {string} label for the item's type
     */
    static getTypeLabel(item:any) {
        if(!item) return 'Unknown Resource Type';
        return ItemTypeLabels[item.type] || 'Unknown Resource Type';
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
            //special plurality
            case ItemTypes.GALLERY : return 'galleries';
            case ItemTypes.COMMUNITY : return 'communities';
            //different name
            case ItemTypes.CONTACT : return 'contacts'; //instead of "vcards"
            //remainder
            default: return type.replace(/^[a-z]+\:/i, '').toLowerCase() + 's';

        }
    }


    /**
     * @param {string} type - item type
     * @return {string} string path to the type's icon
     */
    static determineIconType(type : string) {
        let name = type.replace(/^[a-z]+\:/i, '').toLowerCase();
        return `${environment.assets}/icons/${name}.svg`;
    }

}
