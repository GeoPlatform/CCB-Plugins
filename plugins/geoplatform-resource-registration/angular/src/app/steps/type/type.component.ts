import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter, SimpleChanges
} from '@angular/core';
import {
    HttpClient, HttpRequest, HttpHeaders, HttpParams,
    HttpResponse, HttpEvent, HttpErrorResponse
} from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ItemTypes, Config, ItemService, ServiceService, Query, QueryParameters } from 'geoplatform.client';

import { StepComponent, StepEvent, StepError } from '../step.component';
import { NG2HttpClient } from '../../http-client';
import { environment } from '../../../environments/environment';


@Component({
  selector: 'wizard-step-type',
  templateUrl: './type.component.html',
  styleUrls: ['./type.component.less']
})
export class TypeComponent implements OnInit, OnChanges, StepComponent {

    @Input() data : any;
    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();

    public formGroup: FormGroup;
    public currentType : string;
    public serviceTypes : any[];

    //for UI state
    public status : any = {
        isFetchingServiceInfo: false
    };
    public hasError : StepError = null;


    private typeListener : any;
    private httpClient : NG2HttpClient;

    formOpts : any = {

        //maps to Item.type
        type: ['', Validators.required],

        //maps to Item.title
        title: ['', Validators.required],

        //maps to Item.description
        description: [''],

        //maps to Dataset.distributions[ N ].accessURL
        //  OR to Service.href
        accessURL: [''],

        //maps to Item.landingPage
        landingPage: [''],

        //maps to Service.serviceType
        serviceType: ['']

    };


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient
    ) {

        this.formGroup = this.formBuilder.group(this.formOpts);

        this.httpClient = new NG2HttpClient(http);

        //fetch list of supported service types
        let stq = new Query()
            .types('dct:Standard')
            .resourceTypes('ServiceType')
            .pageSize(50)
            .sort('label,asc');
        new ItemService(Config.ualUrl, this.httpClient).search(stq)
        .then( response => this.serviceTypes = response.results )
        .catch( (e : Error) => {
            //display error indicating issue loading service types
            this.hasError = new StepError("Unable to Load Service Types", e.message);
        });
    }


    ngOnInit() {


        this.typeListener = this.formGroup.get('type').valueChanges.subscribe(val => {
            this.onTypeSelection(val);
        });

        
        // //-------------------------------------------------
        // //TEMP for dev purposes. Remove prior to push
        // setTimeout( () => {
        //     this.hasError = new StepError("This is a test of the error display");
        // }, 3000);
        // //-------------------------------------------------

    }

    ngOnChanges(changes : SimpleChanges) {
        if(changes.data) {
            let data = changes.data.currentValue;
            if(!data) {
                //reset content
                this.formGroup.reset();

            } else {
                //update content
                this.formGroup.get("type").setValue(data.type||null);
                this.formGroup.get('title').setValue(data.title||null);
                this.formGroup.get("description").setValue(data.description||null);
                this.formGroup.get("serviceType").setValue(data.serviceType||null);
                this.formGroup.get('landingPage').setValue(data.landingPage||null);

                let url = null;
                if(ItemTypes.DATASET === data.type && data.distributions &&
                    data.distributions.length && data.distributions[0].accessURL) {
                    url = data.distributions[0].accessURL;

                } else if(ItemTypes.SERVICE === data.type && data.href) {
                    url = data.href;
                }
                this.formGroup.get('accessURL').setValue(null);

                //trigger selection event
                this.onTypeSelection(data.type||null);
            }
        }
    }


    ngOnDestroy() {
        this.typeListener.unsubscribe();
    }


    /**
     * @param {string} type - newly-selected type
     */
    onTypeSelection( type ) {

        //if no change to type
        if(type && type === this.currentType) return;

        //set new type
        this.currentType = type;

        let urlField = this.formGroup.get('accessURL');
        let svcTypeField = this.formGroup.get('serviceType');

        //clear current value of URL
        urlField.setValue(null);
        svcTypeField.setValue(null);

        //update validators based upon type selected
        if(type && ItemTypes.SERVICE === type) {
            urlField.setValidators(Validators.required);
            svcTypeField.setValidators(Validators.required);


            //TEMP FOR DEV PURPOSES, REMOVE BEFORE DEPLOYMENT...
            urlField.setValue('https://tigerweb.geo.census.gov/arcgis/rest/services/Generalized_ACS2016/Tracts_Blocks/MapServer');
            //==================================================


        } else {
            urlField.setValidators(null);
            svcTypeField.setValidators(null);

        }

        //after changing validators, must re-evaluate to clear previous errors
        urlField.updateValueAndValidity();
        svcTypeField.updateValueAndValidity();
    }


    /**
     *
     */
    fetchServiceInfo() {

        let href = this.formGroup.get("accessURL").value;
        if(!href) return;   //TODO notify user of missing value

        let type = this.formGroup.get("serviceType").value;
        if(!type) return;   //TODO notify user of missing value
        let typeLabel = type.label;

        this.status.isFetchingServiceInfo = true;
        new ServiceService(Config.ualUrl, this.httpClient)
        .about({ url: href, serviceType: typeLabel })
        .then( response => {

            this.status.isFetchingServiceInfo = false;

            if(response) {            //pre-populate fields with harvested info

                //notify parent so values can be injected into other steps when needed
                let evt : StepEvent = { type: 'service.about', value: response } as StepEvent;
                this.onEvent.emit(evt);

                let titleField = this.formGroup.get("title");
                if(!titleField.value) {
                    titleField.setValue(response.label || response.title);
                }

                let descField = this.formGroup.get("description");
                if(!descField.value) {
                    descField.setValue(response.description);
                }

            }

        })
        .catch( (e : Error) => {
            this.status.isFetchingServiceInfo = false;
            //display error indicating issue harvesting service info

            this.hasError = new StepError("Unable to Fetch Service Info", e.message);
        });
    }

}
