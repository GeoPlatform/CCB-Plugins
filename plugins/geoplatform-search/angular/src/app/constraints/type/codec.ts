
import { Params } from '@angular/router';
import { Query, QueryParameters, ItemTypes } from 'geoplatform.client';
import {
    Constraint, MultiValueConstraint, Constraints, FacetCount
} from '../../models/constraint';
import { Codec } from '../../models/codec';


export class TypeCodec implements Codec {

    public typeOptions : { key:string; label:string; id:string; count:number; }[];

    constructor() {
        this.typeOptions = Object.keys(ItemTypes)
        .filter(k=> {
            let t = ItemTypes[k];
            return  t !== ItemTypes.CONCEPT &&
                    t !== ItemTypes.CONCEPT_SCHEME &&
                    t !== ItemTypes.STANDARD;
        })
        .map(k=>{
            let v = ItemTypes[k], label = v;
            if(~label.indexOf(":")) label = label.split(':')[1];
            if("VCard" === label) label = 'Contact';
            if("Product" === label) label = "Image Product";
            if("RightsStatement" === label) label = "Right Statement";
            return { key: v, label: label, id: v, count: 0 };
        });
    }

    getKey() : string { return QueryParameters.TYPES; };

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let types = params.types;
        if(types) constraint = this.toConstraint(types.split(','));
        if(constraints && constraint) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.TYPES);
        if(constraint && constraint.value.length)
            params['types'] = constraint.value.map(v=>v.id).join(',');
        else delete params['types'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.TYPES);
        if(constraint && constraint.value)
            return constraint.value.slice(0);
        return null;
    }

    // getCount(constraints: Constraints, value : any) : number {
    //     if(!constraints) return null;
    //     let constraint = constraints.get(QueryParameters.TYPES);
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
                let opt = this.typeOptions.filter(o=>o.id===v);
                if(opt && opt.length)
                    return Object.assign({}, opt[0]);
                return null;
            }
            return v;
        });
        return new MultiValueConstraint(QueryParameters.TYPES, value, "Types");
    }

}
