import {
    Component, OnInit, OnChanges, OnDestroy, SimpleChanges,
    Input, Output, EventEmitter, ViewChild, ElementRef
} from '@angular/core';
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



@Component({
  selector: 'wizard-step-review',
  templateUrl: './review.component.html',
  styleUrls: ['./review.component.less']
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


    httpClient : NG2HttpClient;
    private eventsSubscription: any;


    constructor(
        private formBuilder: FormBuilder,
        private http: HttpClient
    ) {
        this.formGroup = this.formBuilder.group({
            done: ['']
        });

        this.httpClient = new NG2HttpClient(http);
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
    createAndStartOver() {

        //save resource and then start fresh in wizard...

        let func = (persisted) => {
            //wait a few seconds before sending back to the start
            setTimeout( () => {
                this.onEvent.emit( { type:'app.reset', value:true } );
            }, 5000);
        };

        this.registerResource(func);
    }

    /**
     *
     */
    createAndLeave() {

        //save resource and then navigate to resource's home page (OE for now)

        let func = (persisted) => {
            let url = Config.ualUrl.replace('ual', 'oe') + '/view/' + persisted.id;
            setTimeout( () => { window.location.href = url; }, 2000);
        };

        this.registerResource(func);

    }


    /**
     * @param {function} callback - method to invoke upon successful registration
     */
    private registerResource( callback : Function ) {

        this.generateURI().then( item => {
            return new ItemService(Config.ualUrl, this.httpClient).save(item)
        })
        .then( (persisted) => {

            if(ItemTypes.SERVICE === persisted.type) {
                //TODO call harvest on newly persisted service
                return new ServiceService(Config.ualUrl, this.httpClient)
                .harvest(persisted.id)
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
            callback(persisted);
        })
        .catch( (e:Error) => {
            this.status.isSaving = false;
            this.hasError = new StepError("Unable to Register Resource", e.message);
        });
    }

    /**
     * @return {Promise} resolving the resource with its new uri added
     */
    private generateURI() : Promise<any> {

        if(this.data.uri) {
            return Promise.resolve(this.data);
        }

        return new ItemService(Config.ualUrl, this.httpClient)
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
                break;
        }
    }
}
