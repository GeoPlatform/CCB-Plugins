import { Params } from '@angular/router';
import { QueryParameters } from "geoplatform.client";

import { KeywordCodec } from './codec';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';


describe("Keywords Codec", () => {


    it("should parse query parameters", () => {
        let params : Params = {
            keywords: "testing"
        };
        let constraints = new Constraints();

        let codec = new KeywordCodec();
        let constraint = codec.parseParams(params, constraints);
        expect(constraint).toBeTruthy();
        expect(constraint.name).toEqual(QueryParameters.KEYWORDS);
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(1);
        expect(constraint.value[0]).toEqual("testing");
    });

    it("should set query parameters", () => {
        let params : Params = {};
        let codec = new KeywordCodec();
        let constraints = new Constraints();
        constraints.set(new MultiValueConstraint(
            QueryParameters.KEYWORDS, [ "testing" ]
        ));

        codec.setParam(params, constraints);
        expect(params.keywords).toBeTruthy();
        expect(params.keywords).toEqual("testing");
    });


    it("should extract constraint values", () => {
        let constraints = new Constraints();
        let codec = new KeywordCodec();
        constraints.set(new MultiValueConstraint(
            QueryParameters.KEYWORDS, [ "testing" ]
        ));

        let value = codec.getValue(constraints);
        expect(value).toBeTruthy();
        expect(value.length).toEqual(1);
        expect(value[0]).toEqual("testing");
    });


    // it("should format as a string", () => {
    //     let codec = new KeywordCodec();
    //     let constraints = new Constraints();
    //     constraints.set(new MultiValueConstraint(
    //         QueryParameters.KEYWORDS, [ "testing" ]
    //     ));
    //
    //     let value = codec.toString(constraints);
    //     expect(value).toBeTruthy();
    //     expect(value).toEqual("testing");
    // });

    it("should create a constraint", () => {

        let codec = new KeywordCodec();
        let constraint = codec.toConstraint(["testing"]);
        expect(constraint).toBeTruthy();
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(1);
        expect(constraint.value[0]).toEqual("testing");
    });

});
