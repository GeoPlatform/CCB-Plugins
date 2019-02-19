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

import { ModelProperties } from '../../model';


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

    public filteredPurposeOptions: Observable<string[]>;
    public filteredFunctionOptions: Observable<string[]>;
    public filteredTopicOptions: Observable<string[]>;
    public filteredSubjectOptions: Observable<string[]>;
    public filteredPlaceOptions: Observable<string[]>;
    public filteredCategoryOptions: Observable<string[]>;
    public filteredAudienceOptions: Observable<string[]>;

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
        this.formOpts[ModelProperties.CLASSIFIERS_PURPOSE] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_FUNCTION] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_TOPIC_PRIMARY] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_PLACE] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_AUDIENCE] =  [''];
        this.formOpts[ModelProperties.CLASSIFIERS_CATEGORY] =  [''];

        //temp fields for autocompletes
        this.formOpts['$'+ModelProperties.CLASSIFIERS_PURPOSE] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_FUNCTION] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_TOPIC_PRIMARY] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_PLACE] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_AUDIENCE] =  [''];
        this.formOpts['$'+ModelProperties.CLASSIFIERS_CATEGORY] =  [''];

        this.formGroup = this.formBuilder.group(this.formOpts);
        
        let client = new NG2HttpClient(http);
        this.kgService = new KGService(Config.ualUrl, client);
    }

    ngOnInit() {

        //set up filtering piping on the autocomplete fields
        this.filteredPurposeOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_PURPOSE, this.filterPurposes);
        this.filteredFunctionOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_FUNCTION, this.filterFunctions);
        this.filteredTopicOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, this.filterTopics);
        this.filteredSubjectOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, this.filterSubjects);
        this.filteredPlaceOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_PLACE, this.filterPlaces);
        this.filteredAudienceOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_AUDIENCE, this.filterAudience);
        this.filteredCategoryOptions = this.setupFilteringFor(
            '$'+ModelProperties.CLASSIFIERS_CATEGORY, this.filterCategories);


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
                // this.formGroupPrivate.reset();
                return;
            }

            // this.formGroupPrivate.reset();

            let kg = data.classifiers;
            this.updateField(ModelProperties.CLASSIFIERS_PURPOSE, kg);
            this.updateField(ModelProperties.CLASSIFIERS_FUNCTION, kg);
            this.updateField(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, kg);
            this.updateField(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, kg);
            this.updateField(ModelProperties.CLASSIFIERS_PLACE, kg);
            this.updateField(ModelProperties.CLASSIFIERS_AUDIENCE, kg);
            this.updateField(ModelProperties.CLASSIFIERS_CATEGORY, kg);

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


    private setupFilteringFor(key: string, method: Function) : Observable<string[]> {
        return this.formGroup.get(key).valueChanges.pipe(
            startWith(''),
            flatMap(value => method.call(this, value) )
        );
    }

    private filterPurposes(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_PURPOSE, value);
    }
    private filterFunctions(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_FUNCTION, value);
    }
    private filterTopics(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, value);
    }
    private filterSubjects(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, value);
    }
    private filterPlaces(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_PLACE, value);
    }
    private filterCategories(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_CATEGORY, value);
    }
    private filterAudience(value: string): Promise<string[]> {
        return this.filterType(ModelProperties.CLASSIFIERS_AUDIENCE, value);
    }



    private filterType(type: string, value: string): Promise<string[]> {

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let query = new KGQuery().q(filterValue).classifiers(type);

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



    private addChip( key: string, event: MatChipInputEvent ) {

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




    removePurpose(value: any) : void {
        this.removeValue(ModelProperties.CLASSIFIERS_PURPOSE, value);
    }
    removeFunction(value: any): void {
        this.removeValue(ModelProperties.CLASSIFIERS_FUNCTION, value);
    }
    removeTopic(value: any)   : void {
        this.removeValue(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, value);
    }
    removeSubject(value: any) : void {
        this.removeValue(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, value);
    }
    removeAudience(value: any): void {
        this.removeValue(ModelProperties.CLASSIFIERS_AUDIENCE, value);
    }
    removePlace(value: any)   : void {
        this.removeValue(ModelProperties.CLASSIFIERS_PLACE, value);
    }
    removeCategory(value: any): void {
        this.removeValue(ModelProperties.CLASSIFIERS_CATEGORY, value);
    }

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
