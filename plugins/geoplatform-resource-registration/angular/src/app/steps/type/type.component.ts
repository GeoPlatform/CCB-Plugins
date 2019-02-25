import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter, SimpleChanges,
    ViewChild, ElementRef
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
    ItemTypes, Config, ItemService, ServiceService,
    UtilsService, Query, QueryParameters
} from 'geoplatform.client';

import { AppEvent } from '../../app.component';
import { StepComponent, StepEvent, StepError } from '../step.component';
import { NG2HttpClient } from '../../http-client';
import { environment } from '../../../environments/environment';

import { ModelProperties } from '../../model';

const URL_VALIDATOR = Validators.pattern("https?://.+");


interface ResourceType {
    label:string;
    uri:string;
}


@Component({
  selector: 'wizard-step-type',
  templateUrl: './type.component.html',
  styleUrls: ['./type.component.less']
})
export class TypeComponent implements OnInit, OnChanges, StepComponent {

    @Input() data : any;
    @Input() appEvents: Observable<AppEvent>;

    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();

    public formGroup: FormGroup;
    public currentType : string;
    public serviceTypes : any[];

    //for UI state
    public status : any = {
        isFetchingServiceInfo: false
    };
    public hasError : StepError = null;
    public doesExist : string;


    private formListener : any;
    private typeListener : any;
    private eventsSubscription: any;
    private httpClient : NG2HttpClient;
    private itemService : ItemService = null;
    private availableResourceTypes: { type: string; values: ResourceType[]; } =
        {} as { type: string; values: ResourceType[]; };

    formOpts: any = {};


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient
    ) {
        this.formOpts[ModelProperties.TYPE] = ['', Validators.required];
        this.formOpts[ModelProperties.TITLE] = ['', Validators.required];
        this.formOpts[ModelProperties.DESCRIPTION] = [''];
        this.formOpts[ModelProperties.ACCESS_URL] = ['', URL_VALIDATOR];
        this.formOpts[ModelProperties.LANDING_PAGE] = ['', URL_VALIDATOR];
        this.formOpts[ModelProperties.SERVICE_TYPE] = [''];
        this.formOpts[ModelProperties.RESOURCE_TYPES] = [''];
        this.formOpts['$'+ModelProperties.RESOURCE_TYPES] = [''];   //temp field for autocomplete
        this.formOpts[ModelProperties.PUBLISHERS] = [''];
        this.formOpts['$'+ModelProperties.PUBLISHERS] = [''];   //for autocomplete
        this.formGroup = this.formBuilder.group(this.formOpts);
        this.httpClient = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, this.httpClient);
        this.fetchData();
    }


    ngOnInit() {


        this.typeListener = this.formGroup.get(ModelProperties.TYPE).valueChanges
        .subscribe(val => { this.onTypeSelection(val); });

        this.formListener = this.formGroup.valueChanges.subscribe(
            change => { this.onFormChange(change); });


        this.eventsSubscription = this.appEvents.subscribe((event) => {
            this.onAppEvent(event);
        });

        // //-------------------------------------------------
        // //TEMP for dev purposes. Remove prior to push
        // setTimeout( () => {
        //     this.hasError = new StepError("This is a test of the error display");
        // }, 3000);
        // //-------------------------------------------------

        this.formGroup.get(ModelProperties.TYPE).markAsTouched();
        this.formGroup.get(ModelProperties.TITLE).markAsTouched();
    }

    ngOnChanges(changes : SimpleChanges) {
        if(changes.data) {
            let data = changes.data.currentValue;
            if(!data) {
                //reset content
                this.formGroup.reset();

            } else { //update content

                this.setValue(ModelProperties.TYPE, data[ModelProperties.TYPE]||null );
                this.setValue(ModelProperties.TITLE, data[ModelProperties.TITLE]||null );
                this.setValue(ModelProperties.DESCRIPTION, data[ModelProperties.DESCRIPTION]||null );
                this.setValue(ModelProperties.SERVICE_TYPE, data[ModelProperties.SERVICE_TYPE]||null );
                this.setValue(ModelProperties.LANDING_PAGE, data[ModelProperties.LANDING_PAGE]||null );

                if(data[ModelProperties.RESOURCE_TYPES] && data[ModelProperties.RESOURCE_TYPES].length) {
                    this.formGroup.get(ModelProperties.RESOURCE_TYPES)
                        .setValue(data[ModelProperties.RESOURCE_TYPES]);
                }
                if(data[ModelProperties.PUBLISHERS] && data[ModelProperties.PUBLISHERS].length) {
                    this.formGroup.get(ModelProperties.PUBLISHERS)
                        .setValue(data[ModelProperties.PUBLISHERS]);
                }

                let url = null;
                if(ItemTypes.DATASET === data[ModelProperties.TYPE] &&
                    data[ModelProperties.DISTRIBUTIONS] &&
                    data[ModelProperties.DISTRIBUTIONS].length &&
                    data[ModelProperties.DISTRIBUTIONS][0].accessURL) {
                    url = data[ModelProperties.DISTRIBUTIONS][0].accessURL;

                } else if(ItemTypes.SERVICE === data[ModelProperties.TYPE] &&
                    data[ModelProperties.HREF]) {
                    url = data[ModelProperties.HREF];
                }
                this.setValue(ModelProperties.ACCESS_URL, url);

                //trigger selection event
                this.onTypeSelection( data[ModelProperties.TYPE]||null );
            }
        }
    }


    ngOnDestroy() {
        this.typeListener.unsubscribe();
        this.formListener.unsubscribe();
        this.eventsSubscription.unsubscribe();
        this.availableResourceTypes = null;
    }


    /**
     * Load external data needed to populate drop-downs and autocompletes that
     * won't be loading results on-the-fly.
     */
    fetchData() {

        //fetch list of supported service types
        let stq = new Query()
            .types('dct:Standard')
            .resourceTypes('ServiceType')
            .pageSize(50)
            .sort('label,asc');
        this.itemService.search(stq)
        .then( response => this.serviceTypes = response.results )
        .catch( (e : Error) => {
            //display error indicating issue loading service types
            this.hasError = new StepError("Unable to Load Service Types", e.message);
        });

        //fetch list of resource types
        new UtilsService(Config.ualUrl, this.httpClient).capabilities('resourceTypes')
        .then( response => {
            response.results.forEach( type => {
                this.availableResourceTypes[type.assetType] = this.availableResourceTypes[type.assetType] || [];
                this.availableResourceTypes[type.assetType].push(type);
            });
        })
        .catch( (e : Error) => {
            //display error indicating issue loading service types
            this.hasError = new StepError("Unable to Load Resource Types", e.message);
        });
    }


    /**
     * @param {any} t1 - first service type to compare
     * @param {any} t2 - second service type to compare
     * @return {boolean} true if they are the same, false otherwise
     * @see https://angular.io/api/forms/SelectControlValueAccessor#customizing-option-selection
     */
    compareServiceTypes(t1 : any, t2: any) : boolean {
        return t1 && t2 && t1.uri === t2.uri;
    }

    /**
     * NOTE: this will get called AFTER type listener's callback when type is
     * changed.
     * @param {object} formChange
     */
    onFormChange( formChange ) {
        // console.log("Form change: " + JSON.stringify(formChange));

        this.checkExists();
    }

    /**
     * Update internal logic (and other property choices/values) when the
     * user selects a new type.
     *
     * @param {string} type - newly-selected type
     */
    onTypeSelection( type ) {

        //if no change to type
        if(type && type === this.currentType) return;

        //set new type
        this.currentType = type;

        let urlField     = this.formGroup.get(ModelProperties.ACCESS_URL);
        let svcTypeField = this.formGroup.get(ModelProperties.SERVICE_TYPE);
        let landingField = this.formGroup.get(ModelProperties.LANDING_PAGE);
        let resTypeField = this.formGroup.get(ModelProperties.RESOURCE_TYPES);
        let tempResTypeField = this.formGroup.get('$'+ModelProperties.RESOURCE_TYPES);

        //clear current value of URL
        urlField.setValue(null);
        svcTypeField.setValue(null);
        resTypeField.setValue([]);
        tempResTypeField.setValue(null);


        //update validators based upon type selected
        if(type && ItemTypes.SERVICE === type) {
            urlField.setValidators([Validators.required, URL_VALIDATOR]);
            svcTypeField.setValidators(Validators.required);


            //TEMP FOR DEV PURPOSES, REMOVE BEFORE DEPLOYMENT...
            // urlField.setValue('https://tigerweb.geo.census.gov/arcgis/rest/services/Generalized_ACS2016/Tracts_Blocks/MapServer');
            //==================================================

        } else {
            urlField.setValidators(URL_VALIDATOR);
            svcTypeField.setValidators(null);

        }

        if(type && ItemTypes.MAP === type) {
            landingField.setValidators([Validators.required, URL_VALIDATOR]);
        } else {
            landingField.setValidators(URL_VALIDATOR);
        }

        //after changing validators, must re-evaluate to clear previous errors
        urlField.updateValueAndValidity();
        svcTypeField.updateValueAndValidity();
        landingField.updateValueAndValidity();
    }


    /**
     *
     */
    fetchServiceInfo() {

        let href = this.getValue(ModelProperties.ACCESS_URL);
        if(!href) return;   //TODO notify user of missing value

        let type = this.getValue(ModelProperties.SERVICE_TYPE);
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

                let titleField = this.formGroup.get(ModelProperties.TITLE);
                if(!titleField.value) {
                    titleField.setValue(response.label || response.title);
                }

                let descField = this.formGroup.get(ModelProperties.DESCRIPTION);
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


    isInvalid(fieldName) {
        return this.formGroup.get(fieldName).invalid;
    }

    getErrorMessage(fieldName) {
        if(this.formGroup.get(fieldName).hasError('required'))
            return 'You must enter a value';
        if(this.formGroup.get(fieldName).hasError('pattern'))
            return 'You must provide a valid url';
        return null;
    }


    onAppEvent( event : AppEvent ) {
        console.log("TypeStep: App Event: " + event.type);
        switch(event.type) {
            case 'reset':
                this.hasError = null;
                this.status.isFetchingServiceInfo = false;
                break;
        }
    }



    filterResourceTypes = (value: string): Promise<string[]> => {
        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let type = this.getValue(ModelProperties.TYPE);
        if(!type || !type.length) return Promise.resolve([]);

        let current = this.getValue(ModelProperties.RESOURCE_TYPES) || [];
        let options = this.availableResourceTypes[type]||[];
        let results = options.filter(rt => {
            if(current && current.length && current.filter(c=>c.uri===rt.uri).length)
                return false;
            return ~rt.label.toLowerCase().indexOf(filterValue);
        });
        return Promise.resolve(results);

    }

    // public filterPublishers(value: string): Promise<string[]> {
    filterPublishers = (value:string) : Promise<string[]> => {

        let current = this.getValue(ModelProperties.PUBLISHERS) || [];
        current = current.map(c=>c.id);

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;
        let query = new Query().types(ItemTypes.ORGANIZATION).q(filterValue);
        return this.itemService.search(query)
        .then( response => {
            let hits = response.results;
            if(current && current.length) {
                hits = hits.filter(o => { return current.indexOf(o.id)<0; });
            }
            return hits;
        })
        .catch(e => {
            //display error message indicating an issue searching...
            this.hasError = new StepError("Error Searching Publishers", e.message);
        });
    }

    getValue(field) { return this.formGroup.get(field).value; }
    setValue(field, value) { this.formGroup.get(field).setValue(value); }

    private checkDebounce = null;

    checkExists() {
        if(this.checkDebounce) {
            clearTimeout(this.checkDebounce);
            this.checkDebounce = setTimeout(() => {
                this.checkDebounce = null;
                this.checkExists();
            });
            return;
        }
        this.doCheckExists();
    }

    doCheckExists() {
        this.getURI().then( uri => {
            if(!uri) return Promise.resolve({results:[]});
            return this.itemService.search({uri:uri});
        })
        .then( response => {
            if(response.results.length) { //Item already exists!
                this.doesExist = this.getResourceLink(response.results[0]);
            } else { //good to go, clear any warning
                this.doesExist = null;
            }
        })
        .catch(e => {
            console.log("Error checking for existing value: " + e.message);
            this.hasError = new StepError("Unable to verify uniqueness of resource", e.message);
            this.doesExist = null;
        });
    }

    getURI() : Promise<any> {
        if(this.formGroup.invalid) return Promise.resolve(null);
        let obj = this.formGroup.value;
        return this.itemService.getUri(obj);
    }

    getResourceLink(item) {
        if(!item || !item.type) return "";
        let type = null;
        switch(item.type) {
            case ItemTypes.DATASET:
            case ItemTypes.SERVICE:
            case ItemTypes.ORGANIZATION:
            case ItemTypes.CONCEPT:
            case ItemTypes.CONCEPT_SCHEME:
                type = item.type.split(':')[1].toLowerCase();
                break;
            case ItemTypes.MAP:
            case ItemTypes.LAYER:
            case ItemTypes.GALLERY:
            case ItemTypes.COMMUNITY:
                type = item.type.toLowerCase();
                break;
            case ItemTypes.CONTACT: type = "contacts"; break;
        }
        if(!type) return '';
        return `/resources/${type}/${item.id}`;
    }
}
