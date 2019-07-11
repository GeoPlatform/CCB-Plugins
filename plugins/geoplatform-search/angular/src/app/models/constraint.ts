
import { Query, QueryParameters } from "@geoplatform/client";
import { Codec } from './codec';


/**
 *
 */
export interface ConstraintEditor {
    constraints: Constraints;
    getCodec() : Codec;
}

/**
 *
 */
export interface FacetCount {
    label: string;
    key: string;
    count: number;
}
/**
 *
 */
export interface Facet {
    name: string;
    buckets?: FacetCount[];
}


/**
 *
 */
export class Constraint {

    name          : string;
    label         : string;
    value         : any;
    valueProperty : string;
    counts        : FacetCount[];

    constructor(name: string, value?:any, label?:string) {
        this.name = name;
        if(label) this.label = label;
        this.set(value);
    }

    set(value: any) {
        if(value && value instanceof Constraint) {
            this.update(value as Constraint);
        } else {
            this.value = value;
        }
    }

    update(constraint: Constraint) {
        this.set(constraint.value);
    }

    apply(query: Query) {
        let value = null;
        if(this.value) {
            if('object' === typeof(this.value)) {
                if(this.valueProperty && this.value[this.valueProperty] !== undefined) {
                    value = this.value[this.valueProperty];
                } else {
                    //      asset obj     or  suggested concept
                    value = this.value.id || this.value.uri || null;
                }
            } else {
                value = this.value;
            }
        }
        query.setParameter(this.name, value);
    }

    updateFacetCounts( counts: FacetCount[] ) {
        this.counts = counts;
    }

    setValueProperty( property : string) {
        this.valueProperty = property;
    }
}




/**
 *
 */
export class MultiValueConstraint extends Constraint {
    constructor(name: string, value?:[any], label?:string) {
        super(name, value, label);
    }
    set(value: any) {
        if(value !== null && !Array.isArray(value))
            value = [value];
        this.value = value as [any];
    }
    remove(value : any) {
        if(value && this.value && this.value.length) {
            let k1 = value.id ? value.id : value;
            let index = this.value.findIndex( v => {
                let k2 = v.id ? v.id : v;
                return k1 === k2;
            });
            if(index >= 0) {
                this.value.splice(index, 1);
            }
        }
    }
    update(constraint: Constraint) {
        this.set(constraint.value);
    }
    apply(query: Query) {
        let value = null;
        if(this.value) {
            value = this.value.map( v => {
                if('object' === typeof(v)) {
                    if(this.valueProperty && v[this.valueProperty] !== undefined) {
                        return v[this.valueProperty];
                    } else {
                        //      asset obj     or  suggested concept
                        return v.id || v.uri || null;
                    }
                }
                return v;
            });
        }
        // if(this.value) {
        //     let value = this.value.map(v=>v.id?v.id:v);
        //     query.setParameter(this.name, value);
        // }
        query.setParameter(this.name, value);
    }

}



/**
 *
 */
export class CompoundConstraint extends Constraint {
    constructor(name: string, value?:Constraint[], label?:string) {
        super(name, value, label);
    }
    apply(query: Query) {
        if(this.value && this.value.length) {
            this.value.forEach(constraint=> { constraint.apply(query) });
        }
    }
}





import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";


/**
 *
 */
export class Constraints {

    events : Subject<Constraint> = new Subject<Constraint>();
    facetEvents : Subject<Facet[]> = new Subject<Facet[]>();

    public constraints : [Constraint] = [] as [Constraint];
    public facets : Facet[] = [] as Facet[];

    constructor() { }

    on( callback : any ) : ISubscription {
        return this.events.subscribe( callback );
    }

    list() { return this.constraints.slice(0); }

    get(name:string) : Constraint {
        return this.constraints.find(c=>c.name===name);
    }

    set(constraint: Constraint) {
        if(!constraint)return;
        let existing = this.constraints.find(c=>c.name===constraint.name);
        if(existing) {
            existing.update(constraint);
        } else {
            this.constraints.push(constraint);
        }
        this.events.next(existing || constraint);
    }

    unset(constraint: Constraint) {
        let index = this.constraints.findIndex(c=>c.name===constraint.name);
        if(index >= 0) {
            this.constraints.splice(index, 1);
            this.events.next(constraint);
        }
    }

    removeValue(constraint: MultiValueConstraint, value: any) {
        if(constraint && value) {
            constraint.remove(value);

            if( !constraint.value || !constraint.value.length ) {
                //if constraint is empty after removing value, remove it
                this.unset(constraint);
            } else {
                this.events.next(constraint);
            }
        }
    }

    clear() {
        this.constraints = [] as [Constraint];
        this.events.next(null);
    }

    apply(query: Query) {
        this.constraints.forEach( constraint => {
            constraint.apply(query);
        });
    }

    toString() {
        return JSON.stringify(this.list());
    }


    updateFacetCounts( facets : Facet[] ) {
        this.facets = facets;
        this.facetEvents.next(facets);
    }

}
