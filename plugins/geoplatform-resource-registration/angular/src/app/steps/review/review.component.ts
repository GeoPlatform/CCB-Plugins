import {
    Component, OnInit, OnChanges, OnDestroy, SimpleChanges,
    Input, Output, EventEmitter, ViewChild, ElementRef
} from '@angular/core';
import {
    DomSanitizer, SafeResourceUrl, SafeUrl
} from '@angular/platform-browser';
import {
    HttpClient, HttpRequest, HttpHeaders, HttpParams,
    HttpResponse, HttpEvent, HttpErrorResponse
} from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import {
    MatAutocompleteSelectedEvent,
    MatChipInputEvent,
    MatAutocomplete
} from '@angular/material';

import { Observable, Subject } from 'rxjs';
import {map, flatMap, startWith} from 'rxjs/operators';
import * as md5 from "md5";
import {
    Config, ItemService, ItemTypes, ServiceService
} from 'geoplatform.client';
import * as GPAPI from 'geoplatform.client';

const URIFactory = GPAPI.URIFactory(md5);

import { GeoPlatformUser } from 'ng-gpoauth/angular';


import { AppEvent } from '../../app.component';
import { StepComponent, StepEvent, StepError } from '../step.component';
import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../http-client';
import { ClassifierTypes } from '../../model';
import {
    itemServiceProvider, serviceServiceProvider
} from '../../item-service.provider';
import { AppError } from '../../model';
import { ModelProperties, AppEventTypes, StepEventTypes } from '../../model';
import { PluginAuthService } from '../../auth.service';

const CLASSIFIERS = Object.keys(ClassifierTypes).filter(k=> {
    return k.indexOf("secondary")<0 && k.indexOf("community")<0
});


@Component({
  selector: 'wizard-step-review',
  templateUrl: './review.component.html',
  styleUrls: ['./review.component.less'],
  providers: [ itemServiceProvider, serviceServiceProvider ]
})
export class ReviewComponent implements OnInit, OnChanges, OnDestroy, StepComponent {

    @Input() data : any;
    @Input() appEvents: Observable<AppEvent>;
    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();

    public formGroup: FormGroup;
    //display final item for review
    public preview : string;
    public hasError : StepError;
    public status : any = {
        isSaving : false,
        isSaved : false
    };
    public PROPS : any = ModelProperties;

    // httpClient : NG2HttpClient;
    private eventsSubscription: any;


    constructor(
        private formBuilder: FormBuilder,
        private itemService : ItemService,
        private svcService : ServiceService,
        private sanitizer: DomSanitizer,
        private authService : PluginAuthService
    ) {
        this.formGroup = this.formBuilder.group({
            done: ['']
        });
    }

    ngOnInit() {
        this.eventsSubscription = this.appEvents.subscribe((event:AppEvent) => {
            this.onAppEvent(event);
        });
    }

    ngOnChanges( changes : SimpleChanges ) {

        //pre-populate data if the parent component injected values in
        if(changes.data) {

            let data = changes.data.currentValue;
            if(!data) {
                this.preview = null;
                return;
            }

            this.preview = JSON.stringify(data, null, '    ');
        }
    }

    ngOnDestroy() {
        this.eventsSubscription.unsubscribe();
    }



    /**
     * @param {string} type
     * @return {string} label
     */
    getTypeDisplayValue(type : string) : string {
        switch(type) {
            case ItemTypes.DATASET : return 'Dataset';
            case ItemTypes.SERVICE : return 'Web Service';
            default: return type;
        }
    }

    getTypeURLValue(type : string) : string {
        switch(type) {
            case ItemTypes.DATASET :
            case ItemTypes.SERVICE :
            case ItemTypes.ORGANIZATION :
            case ItemTypes.CONCEPT :
            case ItemTypes.CONCEPT_SCHEME :
                return type.toLowerCase().split(':')[1] + 's';
            case ItemTypes.GALLERY : return 'galleries';
            case ItemTypes.COMMUNITY : return 'communities';
            // case ItemTypes.MAP :
            // case ItemTypes.LAYER :
            default: return type.toLowerCase()+'s';
        }
    }

    getClassifierProperties() {
        return CLASSIFIERS;
    }

    /**
     *
     */
    startOver() {

        //should just reload page (minus any search parameters)
        // because we would have to remove the parameters anyway
        // in order to really start over from scratch
        let wdw = (window as any);
        let url = wdw.location.href;
        let qsidx = url.indexOf("?");
        if(qsidx > 0) {
            url = url.substring(0, qsidx);
        }
        wdw.location.href = url;

        //reset all forms
        // this.onEvent.emit( { type:StepEventTypes.RESET, value:true } );
    }

    /**
     *
     */
    viewResource() {
        let type = this.getTypeURLValue(this.data.type);
        let url = Config.wpUrl + '/resources/' + type + '/' + this.data.id;
        window.location.href = url;
    }


    /**
     * @param {function} callback - method to invoke upon successful registration
     */
    registerResource( ) {

        this.status.isSaving = true;

        // console.log("Checking auth token state...");
        this.authService.check().then( ( user : GeoPlatformUser ) => {
            if(!user) return Promise.reject(new Error("Not signed in"));
            // console.log("Token refreshed for " + user.username);

            //update createdBy to match refreshed user token
            if(this.data[ModelProperties.CREATED_BY] !== user.username)
                this.data[ModelProperties.CREATED_BY] = user.username;

            //if authorized, create a URI for the new resource
            this.generateURI();

            //then attempt to create it
            return this.itemService.save(this.data);
        })
        .then( ( persisted : any) => {

            if(ItemTypes.SERVICE === persisted.type) {
                //call harvest on newly persisted service
                return this.svcService.harvest(persisted.id)
                .then( layers => persisted )   //resolve the parent service
                .catch( e => {
                    // this.hasError = new StepError("Unable to Register Service Layers",
                    //     e.message);
                });

            } else {
                return Promise.resolve(persisted);
            }

        })
        .then( persisted => {
            this.status.isSaving = false;
            this.status.isSaved = true;

            //update internal data with saved copy
            Object.assign(this.data, persisted);
        })
        .catch( e => {
            this.status.isSaving = false;

            if(e.status) {
                if(e.status === 403) {
                    this.hasError = new StepError(
                        "Your session has expired", "Please login again and retry");
                } else if(e.status === 409) {
                    this.hasError = new StepError(
                        "Resource already exists",
                        "The resource you are attempting to register already exists");
                } else {
                    this.hasError = new StepError(
                        e.error || "Unable to Register Resource",
                        e.message);
                }

            } else {
                this.hasError = new StepError("Unable to Register Resource", e.message);
            }
        });
    }

    /**
     * @return {Promise} resolving the resource with its new uri added
     */
    private generateURI() : string {

        if(!this.data.uri) {
            let uri = URIFactory(this.data);
            if(!uri) {
                throw new Error("Unable to generate a URI for the resource");
            }
            this.data.uri = uri;
        }
        return this.data;
    }



    /**
     *
     */
    onAppEvent( event : AppEvent ) {
        // console.log("ReviewStep: App Event: " + event.type);
        switch(event.type) {
            case AppEventTypes.RESET:
                this.hasError = null;
                this.status.isSaved = false;
                this.status.isSaving = false;
                break;
            case AppEventTypes.AUTH:
                let token = event.value.token;
                this.itemService.getClient().setAuthToken( token as string);
                break;
        }
    }


    getThumbnailBackground() {
        let thumbnail = this.data[ModelProperties.THUMBNAIL];
        let type = thumbnail[ModelProperties.THUMBNAIL_TYPE] || 'image/png';
        let content = thumbnail[ModelProperties.THUMBNAIL_CONTENT];
        return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
    }
}
