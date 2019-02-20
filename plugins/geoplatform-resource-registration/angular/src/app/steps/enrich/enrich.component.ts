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
    Config, KGService, KGQuery, KGClassifiers, ItemTypes
} from 'geoplatform.client';

import { AppEvent } from '../../app.component';
import { StepComponent, StepEvent, StepError } from '../step.component';
import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../http-client';

import { ModelProperties, ClassifierTypes } from '../../model';


@Component({
  selector: 'wizard-step-enrich',
  templateUrl: './enrich.component.html',
  styleUrls: ['./enrich.component.less']
})
export class EnrichComponent implements OnInit, OnDestroy, StepComponent {

    @Input() data : any;
    @Input() appEvents: Observable<AppEvent>;
    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();

    //for storing model values for usage in the workflow proper
    public formGroup: FormGroup;
    public hasError: StepError;
    public filteredOptions : any = {};

    kgService : KGService = null;
    private eventsSubscription: any;

    @ViewChild('purposeAutoComplete') purposeMatAutocomplete: MatAutocomplete;
    @ViewChild('functionAutoComplete') functionMatAutocomplete: MatAutocomplete;
    @ViewChild('topicAutoComplete') topicMatAutocomplete: MatAutocomplete;
    @ViewChild('subjectAutoComplete') subjectMatAutocomplete: MatAutocomplete;
    @ViewChild('placeAutoComplete') placeMatAutocomplete: MatAutocomplete;
    @ViewChild('categoryAutoComplete') categoryMatAutocomplete: MatAutocomplete;
    @ViewChild('audienceAutoComplete') audienceMatAutocomplete: MatAutocomplete;

    @ViewChild('purposeInput') purposesField: ElementRef;
    @ViewChild('functionInput') functionsField: ElementRef;
    @ViewChild('topicInput') topicsField: ElementRef;
    @ViewChild('subjectInput') subjectsField: ElementRef;
    @ViewChild('placeInput') placesField: ElementRef;
    @ViewChild('categoryInput') categoriesField: ElementRef;
    @ViewChild('audienceInput') audienceField: ElementRef;


    formOpts : any = {};


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient
    ) {

        //initialize form controls
        Object.keys(ClassifierTypes).forEach( key => {
            if(~key.indexOf('secondary')) return;
            this.formOpts[key] =  [''];     //fields for classifiers we care about
            this.formOpts['$'+key] =  ['']; //temp fields for autocompletes
        });
        this.formGroup = this.formBuilder.group(this.formOpts);

        let client = new NG2HttpClient(http);
        this.kgService = new KGService(Config.ualUrl, client);
    }

    ngOnInit() {

        //set up filtering piping on the autocomplete fields
        Object.keys(ClassifierTypes).forEach( key => {
            let field = this.formGroup.get('$'+key);
            if(!field) return;
            // console.log("Setting up filtering for " + key);
            let sub = field.valueChanges.pipe(
                startWith(''),
                flatMap(value => this.filterResultsForType(key, value) )
            );
            this.filteredOptions[key] = sub;
        })

        this.eventsSubscription = this.appEvents.subscribe((event:AppEvent) => {
            this.onAppEvent(event);
        });
    }


    ngOnChanges( changes : SimpleChanges ) {

        //pre-populate data if the parent component injected values in
        if(changes.data) {

            let data = changes.data.currentValue;
            if(!data || !data.classifiers) {
                this.formGroup.reset();
                return;
            }

            let kg = data.classifiers;
            Object.keys(ClassifierTypes).forEach( key => {
                if(~key.indexOf('secondary')) return;
                this.updateField(key, kg);
            });
        }
    }

    ngOnDestroy() {
        this.eventsSubscription.unsubscribe();
    }

    private updateField(key, kg) {
        let value = kg[key];
        if(value && value.length) {
            this.formGroup.get(key).setValue(value);
        }
    }

    /**
     * @param {string} type - form control key to enable value filtering for
     * @param {string} value - user input to use to filter options
     * @return {Promise} resolving list of strings
     */
    private filterResultsForType(type: string, value: string): Promise<string[]> {

        //check for null/empty value (to prevent suggestions without inputs from user)
        //check for short inputs to force user to provide minimum # of chars
        if(!value || value.length < 2) return Promise.resolve([]);

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let cType = ClassifierTypes[type];
        let query = new KGQuery().q(filterValue).classifiers(cType);

        if(this.data && this.data[ModelProperties.TYPE]) //filter by Item type
            query.setTypes(this.data[ModelProperties.TYPE]);

        return this.kgService.suggest(query)
        .then( response => response.results )
        .catch(e => {
            //display error message indicating an issue searching...
        });
    }





    addPurpose(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.purposeMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_PURPOSE, event);
    }
    addFunction(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.functionMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_FUNCTION, event);
    }
    addTopic(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.topicMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, event);
    }
    addSubject(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.subjectMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, event);
    }
    addPlace(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.placeMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_PLACE, event);
    }
    addAudience(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.audienceMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_AUDIENCE, event);
    }
    addCategory(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.categoryMatAutocomplete.isOpen)
            this.addChip(ModelProperties.CLASSIFIERS_CATEGORY, event);
    }



    addChip( key: string, event: MatChipInputEvent ) {

        const input = event.input;
        const value = event.value;

        // Add our value
        if (value) {
            let val = value;
            if(typeof(value) === 'string') val = value.trim();
            let existing = this.formGroup.get(key).value || [];
            existing.push(val);
            this.formGroup.get(key).setValue(existing);
        }

        // Reset the input value
        if (input) {
            input.value = '';
        }

        //clear the local form group so the autocomplete empties
        this.formGroup.get('$'+key).setValue(null);

    }


    /**
     * @param {string} key - form control key to remove value from
     * @param {any} value - value to remove from the form control's set of values
     */
    removeValue(key: string, value: any) {
        if(!value) return;
        let existing = this.formGroup.get(key).value;
        let index = -1;
        existing.forEach( (p,i) => {
            if(p.id === value.id) {
                index = i;
            }
        });
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get(key).setValue(existing);
        }
    }



    onPurposeAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_PURPOSE, event);
        this.purposesField.nativeElement.value = '';
        this.purposesField.nativeElement.blur();
    }
    onFunctionAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_FUNCTION, event);
        this.functionsField.nativeElement.value = '';
        this.functionsField.nativeElement.blur();
    }
    onTopicAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, event);
        this.topicsField.nativeElement.value = '';
        this.topicsField.nativeElement.blur();
    }
    onSubjectAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, event);
        this.subjectsField.nativeElement.value = '';
        this.subjectsField.nativeElement.blur();
    }
    onPlaceAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_PLACE, event);
        this.placesField.nativeElement.value = '';
        this.placesField.nativeElement.blur();
    }
    onCategoryAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_CATEGORY, event);
        this.categoriesField.nativeElement.value = '';
        this.categoriesField.nativeElement.blur();
    }
    onAudienceAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection(ModelProperties.CLASSIFIERS_AUDIENCE, event);
        this.audienceField.nativeElement.value = '';
        this.audienceField.nativeElement.blur();
    }

    onAutoCompleteSelection(key: string, event: MatAutocompleteSelectedEvent) : void {
        let existing = this.formGroup.get(key).value || [];
        existing.push(event.option.value);
        this.formGroup.get(key).setValue(existing);
        this.formGroup.get('$'+key).setValue(null);
    }





    get purposes() { return this.formGroup.get(ModelProperties.CLASSIFIERS_PURPOSE).value || []; }
    get functions() { return this.formGroup.get(ModelProperties.CLASSIFIERS_FUNCTION).value || []; }
    get topics() { return this.formGroup.get(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY).value || []; }
    get subjects() { return this.formGroup.get(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY).value || []; }
    get places() { return this.formGroup.get(ModelProperties.CLASSIFIERS_PLACE).value || []; }
    get categories() { return this.formGroup.get(ModelProperties.CLASSIFIERS_CATEGORY).value || []; }
    get audience() { return this.formGroup.get(ModelProperties.CLASSIFIERS_AUDIENCE).value || []; }



    onAppEvent( event : AppEvent ) {
        console.log("EnrichStep: App Event: " + event.type);
        switch(event.type) {
            case 'reset':
                this.hasError = null;
                // this.formGroupPrivate.reset();
                break;
        }
    }
}
