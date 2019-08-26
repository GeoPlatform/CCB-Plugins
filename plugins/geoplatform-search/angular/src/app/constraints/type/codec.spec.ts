import { Params } from '@angular/router';
import { QueryParameters, ItemTypes } from "@geoplatform/client";

import { TypeCodec } from './codec';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';


describe("Type Codec", () => {


    it("should begin with all available types", () => {
        let codec = new TypeCodec();
        expect(codec.typeOptions).toBeTruthy();
        expect(codec.typeOptions.length).toBeGreaterThan(0);
    });

    it("should parse query parameters", () => {
        let params : Params = {
            types: ItemTypes.DATASET
        };
        let constraints = new Constraints();

        let codec = new TypeCodec();
        let constraint = codec.parseParams(params, constraints);
        expect(constraint).toBeTruthy();
        expect(constraint.name).toEqual(QueryParameters.TYPES);
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(1);
        expect(constraint.value[0]).toBeTruthy();
        expect(constraint.value[0].id).toEqual(ItemTypes.DATASET);
    });

    it("should set query parameters", () => {
        let params : Params = {};
        let codec = new TypeCodec();
        let constraints = new Constraints();
        constraints.set(new MultiValueConstraint(
            QueryParameters.TYPES, [ codec.typeOptions[0] ]
        ));

        codec.setParam(params, constraints);
        expect(params.types).toBeTruthy();
        expect(params.types).toEqual(codec.typeOptions[0].id);
    });


    it("should extract constraint values", () => {
        let constraints = new Constraints();
        let codec = new TypeCodec();
        constraints.set(new MultiValueConstraint(
            QueryParameters.TYPES, [ codec.typeOptions[0] ]
        ));

        let value = codec.getValue(constraints);
        expect(value).toBeTruthy();
        expect(value.length).toEqual(1);
        expect(value[0]).toBeTruthy();
        expect(value[0].id).toEqual(codec.typeOptions[0].id);
    });


    it("should format as a string", () => {
        let codec = new TypeCodec();
        let constraints = new Constraints();
        constraints.set(new MultiValueConstraint(
            QueryParameters.TYPES, [ codec.typeOptions[0] ]
        ));

        let value = codec.toString(constraints);
        expect(value).toBeTruthy();
        expect(value).toEqual(codec.typeOptions[0].id);
    });

    it("should create a constraint", () => {

        let codec = new TypeCodec();
        let constraint = codec.toConstraint([ItemTypes.DATASET]);
        expect(constraint).toBeTruthy();
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(1);
        expect(constraint.value[0]).toBeTruthy();
        expect(constraint.value[0].id).toEqual(ItemTypes.DATASET);
    });

});
