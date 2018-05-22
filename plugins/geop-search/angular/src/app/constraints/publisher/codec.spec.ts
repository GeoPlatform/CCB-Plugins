import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Params } from '@angular/router';
import { QueryParameters, ItemTypes } from "geoplatform.client";

import { async, inject, TestBed } from '@angular/core/testing';

import { PublisherCodec } from './codec';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';


describe("Publisher Codec", () => {

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [HttpClientModule]
        });
    }));

    // it("should parse query parameters", () => {
    //     let params : Params = { publishers: 'test' };
    //     let constraints = new Constraints();
    //
    //     let codec = new PublisherCodec();
    //     let constraint = codec.parseParams(params, constraints);
    //     expect(constraint).toBeTruthy();
    //     expect(constraint.name).toEqual(QueryParameters.PUBLISHERS_ID);
    //     expect(constraint.value).toBeTruthy();
    //     expect(constraint.value.length).toEqual(1);
    //     expect(constraint.value[0]).toEqual('test');
    // });

    it("should set query parameters", async(
        inject( [ HttpClient ], (http: HttpClient) => {
            let params : Params = {};
            let codec = new PublisherCodec(http);
            let constraints = new Constraints();
            constraints.set(new MultiValueConstraint(
                QueryParameters.PUBLISHERS_ID, [ {id:'test'} ]
            ));

            codec.setParam(params, constraints);
            expect(params.publishers).toEqual('test');
        })
    ));


    it("should extract constraint values", async(
        inject( [ HttpClient ], (http: HttpClient) => {
            let constraints = new Constraints();
            let codec = new PublisherCodec(http);
            constraints.set(new MultiValueConstraint(
                QueryParameters.PUBLISHERS_ID, [ 'test' ]
            ));

            let value = codec.getValue(constraints);
            expect(value).toBeTruthy();
            expect(value.length).toEqual(1);
            expect(value[0]).toEqual('test');
        })
    ));


    // it("should format as a string", () => {
    //     let codec = new PublisherCodec();
    //     let constraints = new Constraints();
    //     constraints.set(new MultiValueConstraint(
    //         QueryParameters.PUBLISHERS_ID, [ 'test' ]
    //     ));
    //
    //     let value = codec.toString(constraints);
    //     expect(value).toBeTruthy();
    //     expect(value).toEqual('test');
    // });

    it("should create a constraint", async(
        inject( [ HttpClient ], (http: HttpClient) => {

            let codec = new PublisherCodec(http);
            let constraint = codec.toConstraint(['test']);
            expect(constraint).toBeTruthy();
            expect(constraint.value).toBeTruthy();
            expect(constraint.value.length).toEqual(1);
            expect(constraint.value[0]).toEqual('test');
        })
    ));

});
