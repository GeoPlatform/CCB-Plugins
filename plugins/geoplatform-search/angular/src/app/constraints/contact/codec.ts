import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { Config, Query, QueryParameters, ItemService } from 'geoplatform.client';
import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { Codec } from '../../models/codec';

import { itemServiceFactory } from '../../shared/service.provider';

export class ContactCodec implements Codec {

    private service : ItemService;

    constructor(private http : HttpClient) {
        this.service = itemServiceFactory(http);
        // this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
    }

    getKey() : string { return QueryParameters.CONTACTS_ID; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        if(params && params.contacts) {
            let ids = params.contacts.split(',');
            if(ids && ids.length) {
                //have to get theme objects for ids provided
                this.resolveItems(ids.split(',')).then( contacts => {
                    constraint = this.toConstraint(contacts);
                    if(constraints && constraint) {
                        constraints.set(constraint);
                    }
                });
            }
        }
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.CONTACTS_ID);
        if(constraint && constraint.value.length)
            params['contacts'] = constraint.value.map(v=>v.id).join(',');
        else delete params['contacts'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.CONTACTS_ID);
        if(constraint) {
            return (constraint.value||[]).slice(0);
        }
        return null;
    }

    toConstraint(value : any) : Constraint {
        if(!value) return null;
        let contacts = value as [{id:string}];
        return new MultiValueConstraint(QueryParameters.CONTACTS_ID, contacts, "Contacts");
    }


    resolveItems(ids) {
        return this.service.getMultiple(ids)
        .catch( e => console.log("An error occurred: " + e.message) )
    }

}
