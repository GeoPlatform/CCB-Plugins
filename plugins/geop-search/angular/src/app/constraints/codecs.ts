import { HttpClient } from '@angular/common/http';
import { Params } from '@angular/router';
import { QueryParameters } from "geoplatform.client";

import { Constraint, Constraints } from '../models/constraint';
import { Codec } from '../models/codec';
import { TypeCodec } from './type/codec';
import { KeywordCodec } from './keywords/codec';
import { ThemeCodec } from './theme/codec';
import { PublisherCodec } from './publisher/codec';
import { CreatorCodec } from './creator/codec';
import { ExtentCodec } from './extent/codec';
import { TemporalCodec } from './temporal/codec';



/**
 *
 */
export class FreeTextCodec implements Codec {

    parseParams(params: Params, constraints?: Constraints) : Constraint {
        let constraint : Constraint = null;
        let text = params.q;
        if(text) constraint = this.toConstraint(text);
        if(constraints && constraint) constraints.set(constraint);
        return constraint;
    }

    setParam(params: Params, constraints: Constraints) {
        let constraint = constraints.get(QueryParameters.QUERY);
        if(constraint) params['q'] = constraint.value;
        else delete params['q'];
    }

    getValue(constraints: Constraints) : any {
        if(!constraints) return null;
        let constraint = constraints.get(QueryParameters.QUERY);
        return constraint ? constraint.value : null;
    }

    toConstraint(value : any) : Constraint {
        return new Constraint(QueryParameters.QUERY, value, "Free Text");
    }
}





/**
 *
 */
export class CodecFactory {

    private codecs : [Codec] = [] as [Codec];

    constructor(private http : HttpClient) {
        this.codecs.push(new FreeTextCodec());
        this.codecs.push(new TypeCodec());
        this.codecs.push(new KeywordCodec());
        this.codecs.push(new ThemeCodec(http));
        this.codecs.push(new PublisherCodec(http));
        this.codecs.push(new CreatorCodec());
        this.codecs.push(new ExtentCodec());
        this.codecs.push(new TemporalCodec());
    }

    get () : Codec {
        return null;
    }

    list () : [Codec] {
        return this.codecs;
    }

};
