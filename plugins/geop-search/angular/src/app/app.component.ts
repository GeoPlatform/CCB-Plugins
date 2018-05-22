import { NgZone, Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { ISubscription } from "rxjs/Subscription";
import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

import { Constraints, Constraint } from './models/constraint';
import { CodecFactory, FreeTextCodec } from './constraints/codecs';
import { Codec } from './models/codec';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit, OnDestroy {

    private listener : ISubscription;
    private codecFactory : CodecFactory;
    private freeTextCodec : FreeTextCodec = new FreeTextCodec();

    //@see https://github.com/angular/angular-cli/issues/5623#issuecomment-289024943
    public freeText: string;
    public pickerClosed = false;
    public constraints : Constraints = new Constraints();


    constructor(
        private _ngZone: NgZone,
        private http : HttpClient,
        private route: ActivatedRoute,
        private router: Router
    ) {
        this.codecFactory = new CodecFactory(http);
    }

    ngOnInit() {
        this.route.queryParams.subscribe(params => {
            this.parseQueryParameters(params);
        });

        this.listener = this.constraints.on( (constraint) => {
            this.updateFreeTextField();
            this.updateURL();
        });
    }

    ngOnDestroy() {
        if(this.listener)
            this.listener.unsubscribe();
    }

    onPickerClose(closed) {
        this.pickerClosed = !this.pickerClosed;
    }

    onFreeTextChange() {
        let constraint = this.constraints.get(QueryParameters.QUERY);
        if(constraint) {
            // console.log("AppComponent.onFreeTextChange() - Updating existing");
            if(this.freeText) {
                // console.log("AppComponent.onFreeTextChange() - Changing to '" + this.freeText + "'");
                constraint.set(this.freeText);
                this.constraints.set(constraint);
            } else {
                // console.log("AppComponent.onFreeTextChange() - Clearing");
                this.constraints.unset(constraint);
            }
        } else {
            // console.log("AppComponent.onFreeTextChange() - Setting new");
            if(this.freeText) {
                // console.log("AppComponent.onFreeTextChange() - Setting as '" + this.freeText + "'");
                constraint = this.freeTextCodec.toConstraint(this.freeText);
                this.constraints.set(constraint);
            }
        }
    }

    parseQueryParameters(params: Params) {
        // console.log(params);

        this.codecFactory.list().forEach( (codec : Codec) => {
            codec.parseParams(params, this.constraints);
        });

        //initialize free text query box with supplied querystring parameter (if any)
        this.updateFreeTextField();

    }

    updateFreeTextField() {
        let constraint = this.constraints.get(QueryParameters.QUERY);
        if(constraint) {
            this.freeText = constraint.value;
        } else {
            this.freeText = null;
        }
    }

    updateURL() {
        let params : Params = Object.assign({}, this.route.snapshot.queryParams);
        this.codecFactory.list().forEach( (codec : Codec) => {
            codec.setParam(params, this.constraints);
        });
        //Note: this will not refresh the page, but will insert a new entry
        // into history and update URL bar
        this.router.navigate(['.'], { queryParams: params });
    }

}
