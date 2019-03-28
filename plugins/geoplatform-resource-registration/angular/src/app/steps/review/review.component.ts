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
import {
    Config, ItemService, ItemTypes, ServiceService
} from 'geoplatform.client';


import { AppEvent } from '../../app.component';
import { StepComponent, StepEvent, StepError } from '../step.component';
import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../http-client';
import { ClassifierTypes } from '../../model';
import {
    itemServiceProvider, serviceServiceProvider
} from '../../item-service.provider';
import { AppError } from '../../model';

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


    // httpClient : NG2HttpClient;
    private eventsSubscription: any;


    constructor(
        private formBuilder: FormBuilder,
        private itemService : ItemService,
        private svcService : ServiceService,
        private sanitizer: DomSanitizer
    ) {
        this.formGroup = this.formBuilder.group({
            done: ['']
        });

        // this.httpClient = new NG2HttpClient(http);
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
        //reset all forms
        this.onEvent.emit( { type:'app.reset', value:true } );
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

        this.generateURI().then( item => {
            // return new ItemService(Config.ualUrl, this.httpClient).save(item)
            return this.itemService.save(item);
        })
        .then( (persisted) => {

            if(ItemTypes.SERVICE === persisted.type) {
                //TODO call harvest on newly persisted service
                return this.svcService.harvest(persisted.id)
                .then( layers => {
                    return persisted;   //resolve the parent service
                })
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
            // callback(persisted);
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
    private generateURI() : Promise<any> {

        if(this.data.uri) {
            return Promise.resolve(this.data);
        }

        // return new ItemService(Config.ualUrl, this.httpClient)
        return this.itemService
        .getUri(this.data)
        .then( uri => {
            this.data.uri = uri;
            return Promise.resolve(this.data);
        })
        .catch( (e:Error) => {
            return Promise.reject(new Error("Unable to generate a URI for the resource"));
            // this.hasError = new StepError("Error Generating URI", e.message);
        });

    }



    /**
     *
     */
    onAppEvent( event : AppEvent ) {
        // console.log("ReviewStep: App Event: " + event.type);
        switch(event.type) {
            case 'reset':
                this.hasError = null;
                this.status.isSaved = false;
                this.status.isSaving = false;
                break;
            case 'auth':
                let token = event.value.token;
                this.itemService.client.setAuthToken( token as string);
                break;
        }
    }


    getThumbnailBackground() {
        let thumbnail = this.data.thumbnail;
        let type = thumbnail.mediaType || 'image/png';
        let content = thumbnail.contentData;
        return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
    }
}
