import { HttpClient, HttpClientModule } from '@angular/common/http';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';

import { Params } from '@angular/router';
import { Config, QueryParameters, ItemTypes } from "@geoplatform/client";

import { async, inject, TestBed } from '@angular/core/testing';

import { TopicCodec } from './codec';
import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';


describe("Topic Codec", () => {

    //setup testing configuration before running the tests
    beforeAll( () => {
        Config.configure({
            env: 'test',
            ualUrl: 'https://sit-ual.geoplatform.us'
        });
    });

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ HttpClientModule, HttpClientTestingModule ]
        });
    }));

    // it("should parse query parameters", () => {
    //     let params : Params = { themes: 'test' };
    //     let constraints = new Constraints();
    //
    //     let codec = new TopicCodec();
    //     let constraint = codec.parseParams(params, constraints);
    //     expect(constraint).toBeTruthy();
    //     expect(constraint.name).toEqual(QueryParameters.THEMES_ID);
    //     expect(constraint.value).toBeTruthy();
    //     expect(constraint.value.length).toEqual(1);
    //     expect(constraint.value[0]).toEqual('test');
    // });

    it("should set query parameters", async( inject(
        [ HttpClient, HttpTestingController ],
        (http: HttpClient, backend: HttpTestingController ) => {

            let params : Params = {};
            let codec = new TopicCodec(http);
            let constraints = new Constraints();
            constraints.set(new MultiValueConstraint(
                QueryParameters.THEMES_ID, [ {id:'test'} ]
            ));

            codec.setParam(params, constraints);
            expect(params.themes).toEqual('test');

            backend.match({
                url: Config.ualUrl + '/api/items',
                method: 'GET'
            });
            backend.match({
                url: Config.ualUrl + '/api/fetch',
                method: 'POST'
            });
        }
    )));


    it("should extract constraint values", async(
        inject( [ HttpClient ], (http: HttpClient) => {
            let constraints = new Constraints();
            let codec = new TopicCodec(http);
            constraints.set(new MultiValueConstraint(
                QueryParameters.THEMES_ID, [ 'test' ]
            ));

            let value = codec.getValue(constraints);
            expect(value).toBeTruthy();
            expect(value.length).toEqual(1);
            expect(value[0]).toEqual('test');
        })
    ));


    // it("should format as a string", () => {
    //     let codec = new TopicCodec();
    //     let constraints = new Constraints();
    //     constraints.set(new MultiValueConstraint(
    //         QueryParameters.THEMES_ID, [ 'test' ]
    //     ));
    //
    //     let value = codec.toString(constraints);
    //     expect(value).toBeTruthy();
    //     expect(value).toEqual('test');
    // });

    it("should create a constraint", async(
        inject( [ HttpClient ], (http: HttpClient) => {

            let codec = new TopicCodec(http);
            let constraint = codec.toConstraint(['test']);
            expect(constraint).toBeTruthy();
            expect(constraint.value).toBeTruthy();
            expect(constraint.value.length).toEqual(1);
            expect(constraint.value[0]).toEqual('test');
        })
    ));

});
