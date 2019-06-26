
import { Params } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import {
    Config, Query, QueryParameters,
    ItemTypes, ItemTypeLabels, UtilsService
} from 'geoplatform.client';
import {
    Constraint, MultiValueConstraint, Constraints, FacetCount
} from '../../models/constraint';
import { Codec } from '../../models/codec';


export class ResourceTypeCodec implements Codec {

    private typesLoaded : boolean;
    public typeOptions : { [key:string] : any[] };

    constructor(http: HttpClient) {

        this.typeOptions = {};
        this.typesLoaded = false;

        let client = new NG2HttpClient(http);
        let service = new UtilsService(Config.ualUrl, client);
        let query = {
            size: 200,
            types: 'Dataset,Service,Layer,Map,Gallery,Community,Application,Topic,WebSite'
        };
        service.capabilities('resourceTypes', query).then( response => {
            response.results.forEach( resourceType => {
                if(resourceType.resourceTypes.length) { //must have an associated asset type or ignore it
                    let assetTypes = resourceType.resourceTypes.map(t=>t.replace(/Type/i,''));
                    assetTypes.forEach( at => {
                        let itemType = null;
                        switch(at) {
                            case 'Dataset': itemType = ItemTypes.DATASET; break;
                            case 'Service': itemType = ItemTypes.SERVICE; break;
                            default: itemType = at;
                        }
                        if(itemType !== null) {
                            this.typeOptions[itemType] = this.typeOptions[itemType] || [];
                            this.typeOptions[itemType].push({
                                label: resourceType.title,
                                uri: resourceType.uri,
                                id: resourceType.id
                            });
                        }
                    });
                }
            });
            this.typesLoaded = true;
        })
        .catch( e => {
            console.log("Unable to fetch available resource types from GeoPlatform because " +
                e.message);
        });

    }

    getKey() : string { return QueryParameters.RESOURCE_TYPE; };

    getOptions() : { [key:string] : any[] } {
        return this.typeOptions;
    }

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let types = params.resourceTypes;
        if(!types) return constraint;

        if(!this.typesLoaded) {
            //wait for the list of available resource types to be loaded
            setTimeout(() => {
                constraint = this.toConstraint(types.split(','));
                if(constraints && constraint) {
                    constraints.set(constraint);
                }
            }, 1000);
        } else {
            constraint = this.toConstraint(types.split(','));
            if(constraints && constraint) constraints.set(constraint);
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.RESOURCE_TYPE);
        if(constraint && constraint.value.length)
            params.resourceTypes = constraint.value.map(v=>v.uri).join(',');
        else delete params.resourceTypes;
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.RESOURCE_TYPE);
        if(constraint && constraint.value)
            return constraint.value.slice(0);
        return null;
    }

    // getCount(constraints: Constraints, value : any) : number {
    //     if(!constraints) return null;
    //     let constraint = constraints.get(QueryParameters.RESOURCE_TYPE);
    //     if(constraint && constraint.counts) {
    //         let v : FacetCount[] = constraint.counts.filter( f => f.label === value );
    //         if(v.length) return v[0].count;
    //     }
    //     return 0;
    // }

    toString(constraints: Constraints) : string {
        return (this.getValue(constraints) || []).map(v=>v.id).join(', ');
    }

    toConstraint(value : any) : Constraint {
        if(value && typeof(value.push) === 'undefined') {
            value = [value];
        }
        value = value.map(v=> {
            if(v.label === undefined) {
                let match = null;
                Object.keys(this.typeOptions).forEach(itemType => {
                    if(match) return;
                    let idx = this.typeOptions[itemType].findIndex( opt => {
                        return opt.uri && v === opt.uri;
                    });
                    if(idx >= 0) {
                        match = Object.assign({}, this.typeOptions[itemType][idx]);
                    }
                });
                return match;
            }
            return v;
        });
        let constraint = new MultiValueConstraint(QueryParameters.RESOURCE_TYPE, value, "Type Specializations");
        constraint.setValueProperty("uri");
        return constraint;
    }

}
