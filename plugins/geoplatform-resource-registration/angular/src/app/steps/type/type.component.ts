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


    @ViewChild('resTypeAutoComplete') resTypeMatAutocomplete: MatAutocomplete;
    @ViewChild('resTypesInput') resourceTypesField: ElementRef;
    public filteredResourceTypeOptions: Observable<string[]>;


    private typeListener : any;
    private eventsSubscription: any;
    private httpClient : NG2HttpClient;
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
        this.formGroup = this.formBuilder.group(this.formOpts);
        this.httpClient = new NG2HttpClient(http);
        this.fetchData();
    }


    ngOnInit() {

        //set up filtering piping on the autocomplete fields
        this.filteredResourceTypeOptions = this.formGroup.get('$'+ModelProperties.RESOURCE_TYPES)
        .valueChanges.pipe(
            flatMap(value => {
                let result = this.filterResourceTypes(value);
                return result;
            })
        );

        this.typeListener = this.formGroup.get(ModelProperties.TYPE).valueChanges
        .subscribe(val => { this.onTypeSelection(val); });

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
        this.eventsSubscription.unsubscribe();
        this.filteredResourceTypeOptions = null;
        this.resTypeMatAutocomplete = null;
        this.resourceTypesField = null;
        this.filteredResourceTypeOptions = null;
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
        new ItemService(Config.ualUrl, this.httpClient).search(stq)
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




    get resourceTypes() {
        return this.getValue(ModelProperties.RESOURCE_TYPES) || [];
    }
    get $resourceTypes() {
        return this.getValue('$'+ModelProperties.RESOURCE_TYPES) || [];
    }

    private filterResourceTypes(value: string): Promise<string[]> {
        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let type = this.getValue(ModelProperties.TYPE);
        if(!type || !type.length) return Promise.resolve([]);

        let options = this.availableResourceTypes[type]||[];
        let results = options.filter(rt => {
            return ~rt.label.toLowerCase().indexOf(filterValue);
        });
        return Promise.resolve(results);

    }

    /**
     * Adds the value pointed to by the event parameter into the underlying resource types array in the model
     * @param {MatChipInputEvent} event - material chip event containing value to add to the model
     */
    addResourceType(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        // To make sure this does not conflict with OptionSelected Event
        // if (!this.resTypeMatAutocomplete.isOpen) {
        //     this.addChip('$'+ModelProperties.RESOURCE_TYPES, ModelProperties.RESOURCE_TYPES, event);
        // }
    }

    /**
     * @param {string} from - temporary model property
     * @param {string} to - actual model property
     * @param {MatChipInputEvent} event - event containing value to be applied
     */
    private addChip( from: string, to: string, event: MatChipInputEvent ) {

        const input = event.input;
        const value = event.value;

        // Add our value
        if (value) {
            let val = value;
            if(typeof(val) === 'string') val = val.trim();
            let existing = this.getValue(to) || [];
            existing.push(val);
            this.setValue(to, existing);
        }

        // Reset the input value
        if (input) {
            input.value = '';
        }

        //clear the local form group so the autocomplete empties
        this.setValue(from, null);

    }

    /**
     * @param {object} type - resource type to remove from the internal model
     */
    removeResourceType(type: any): void {
        let existing = this.getValue(ModelProperties.RESOURCE_TYPES);
        let index = existing.indexOf(type.uri);
        if (index >= 0) {
            existing.splice(index, 1);
            this.setValue(ModelProperties.RESOURCE_TYPES, existing);
        }
    }

    /**
     * Fired when user selects a value from the material autocomplete dropdown.
     * Takes the selected value and appends to the real form group property,
     * while clearing the temporary form group property's value.
     */
    onResTypeAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        let existing = this.getValue(ModelProperties.RESOURCE_TYPES) || [];
        existing.push(event.option.value);
        this.setValue(ModelProperties.RESOURCE_TYPES, existing);

        //clear input and blur so autocomplete isn't left in weird state after selection
        this.resourceTypesField.nativeElement.value='';
        this.resourceTypesField.nativeElement.blur();
        this.setValue('$'+ModelProperties.RESOURCE_TYPES, null);
    }


    getValue(field) { return this.formGroup.get(field).value; }
    setValue(field, value) { this.formGroup.get(field).setValue(value); }
}
