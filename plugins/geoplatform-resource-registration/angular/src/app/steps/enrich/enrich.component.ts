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





//-----------------------
// TEMP until API Client is updated to remove plurality on KGClassifiers
const Classifiers = {
    PURPOSE             : 'purpose',
    FUNCTION            : 'function',
    TOPIC_PRIMARY       : 'primaryTopic',
    TOPIC_SECONDARY     : 'secondaryTopic',
    SUBJECT_PRIMARY     : 'primarySubject',
    SUBJECT_SECONDARY   : 'secondarySubject',
    COMMUNITY           : 'community',
    AUDIENCE            : 'audience',
    PLACE               : 'place',
    CATEGORY            : 'category'
};
//-----------------------




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

    //for storing intermediate internal model values only for use within this step
    public formGroupPrivate: FormGroup;

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


    formOpts = {
        purposes: [''],
        functions: [''],
        topics: [''],
        subjects: [''],
        places: [''],
        audience: [''],
        categories: ['']
    };


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient
    ) {

        //initialize form controls
        this.formGroup = this.formBuilder.group(this.formOpts);
        this.formGroupPrivate = this.formBuilder.group(Object.assign({}, this.formOpts));

        let client = new NG2HttpClient(http);
        this.kgService = new KGService(Config.ualUrl, client);
    }

    ngOnInit() {

        //set up filtering piping on the autocomplete fields
        this.filteredPurposeOptions = this.setupFilteringFor('purposes', this.filterPurposes);
        this.filteredFunctionOptions = this.setupFilteringFor('functions', this.filterFunctions);
        this.filteredTopicOptions = this.setupFilteringFor('topics', this.filterTopics);
        this.filteredSubjectOptions = this.setupFilteringFor('subjects', this.filterSubjects);
        this.filteredPlaceOptions = this.setupFilteringFor('places', this.filterPlaces);
        this.filteredAudienceOptions = this.setupFilteringFor('audience', this.filterAudience);
        this.filteredCategoryOptions = this.setupFilteringFor('categories', this.filterCategories);


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
                this.formGroupPrivate.reset();
                return;
            }

            this.formGroupPrivate.reset();

            let kg = data.classifiers;

            if(kg.purposes && kg.purposes.length) {
                this.formGroup.get("purposes").setValue(kg.purposes);
            }
            if(kg.functions && kg.functions.length) {
                this.formGroup.get("functions").setValue(kg.functions);
            }
            if(kg.primaryTopics && kg.primaryTopics.length) {
                this.formGroup.get("topics").setValue(kg.primaryTopics);
            }
            if(kg.primarySubjects && kg.primarySubjects.length) {
                this.formGroup.get("subjects").setValue(kg.primarySubjects);
            }
            if(kg.categories && kg.categories.length) {
                this.formGroup.get("categories").setValue(kg.categories);
            }
            if(kg.places && kg.places.length) {
                this.formGroup.get("places").setValue(kg.places);
            }
            if(kg.audience && kg.audience.length) {
                this.formGroup.get("audience").setValue(kg.audience);
            }
        }
    }

    ngOnDestroy() {
        this.eventsSubscription.unsubscribe();
    }


    private setupFilteringFor(key: string, method: Function) : Observable<string[]> {
        return this.formGroupPrivate.get(key).valueChanges.pipe(
            startWith(''),
            flatMap(value => method.call(this, value) )
        );
    }

    private filterPurposes(value: string): Promise<string[]> {
        return this.filterType(Classifiers.PURPOSE, value);
    }
    private filterFunctions(value: string): Promise<string[]> {
        return this.filterType(Classifiers.FUNCTION, value);
    }
    private filterTopics(value: string): Promise<string[]> {
        return this.filterType(Classifiers.TOPIC_PRIMARY, value);
    }
    private filterSubjects(value: string): Promise<string[]> {
        return this.filterType(Classifiers.SUBJECT_PRIMARY, value);
    }
    private filterPlaces(value: string): Promise<string[]> {
        return this.filterType(Classifiers.PLACE, value);
    }
    private filterCategories(value: string): Promise<string[]> {
        return this.filterType(Classifiers.CATEGORY, value);
    }
    private filterAudience(value: string): Promise<string[]> {
        return this.filterType(Classifiers.AUDIENCE, value);
    }



    private filterType(type: string, value: string): Promise<string[]> {

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let query = new KGQuery().q(filterValue).classifiers(type);

        if(this.data && this.data.type) //filter by Item type
            query.setTypes(this.data.type);

        return this.kgService.suggest(query)
        .then( response => response.results )
        .catch(e => {
            //display error message indicating an issue searching...
        });
    }





    addPurpose(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.purposeMatAutocomplete.isOpen) this.addChip('purposes', event);
    }
    addFunction(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.functionMatAutocomplete.isOpen) this.addChip('functions', event);
    }
    addTopic(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.topicMatAutocomplete.isOpen) this.addChip('topics', event);
    }
    addSubject(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.subjectMatAutocomplete.isOpen) this.addChip('subjects', event);
    }
    addPlace(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.placeMatAutocomplete.isOpen) this.addChip('places', event);
    }
    addAudience(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.audienceMatAutocomplete.isOpen) this.addChip('audience', event);
    }
    addCategory(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        if (!this.categoryMatAutocomplete.isOpen) this.addChip('categories', event);
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
        this.formGroupPrivate.get(key).setValue(null);

    }




    removePurpose(value: any) : void { this.removeValue("purposes", value); }
    removeFunction(value: any): void { this.removeValue("functions", value); }
    removeTopic(value: any)   : void { this.removeValue("topics", value); }
    removeSubject(value: any) : void { this.removeValue("subjects", value); }
    removeAudience(value: any): void { this.removeValue("audience", value); }
    removePlace(value: any)   : void { this.removeValue("places", value); }
    removeCategory(value: any): void { this.removeValue("categories", value); }

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
        this.onAutoCompleteSelection('purposes', event);
        this.purposesField.nativeElement.value = '';
        this.purposesField.nativeElement.blur();
    }
    onFunctionAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('functions', event);
        this.functionsField.nativeElement.value = '';
        this.functionsField.nativeElement.blur();
    }
    onTopicAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('topics', event);
        this.topicsField.nativeElement.value = '';
        this.topicsField.nativeElement.blur();
    }
    onSubjectAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('subjects', event);
        this.subjectsField.nativeElement.value = '';
        this.subjectsField.nativeElement.blur();
    }
    onPlaceAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('places', event);
        this.placesField.nativeElement.value = '';
        this.placesField.nativeElement.blur();
    }
    onCategoryAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('categories', event);
        this.categoriesField.nativeElement.value = '';
        this.categoriesField.nativeElement.blur();
    }
    onAudienceAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        this.onAutoCompleteSelection('audience', event);
        this.audienceField.nativeElement.value = '';
        this.audienceField.nativeElement.blur();
    }

    onAutoCompleteSelection(key: string, event: MatAutocompleteSelectedEvent) : void {
        let existing = this.formGroup.get(key).value || [];
        existing.push(event.option.value);
        this.formGroup.get(key).setValue(existing);
        this.formGroupPrivate.get(key).setValue(null);
    }





    get purposes() { return this.formGroup.get("purposes").value || []; }
    get functions() { return this.formGroup.get("functions").value || []; }
    get topics() { return this.formGroup.get("topics").value || []; }
    get subjects() { return this.formGroup.get("subjects").value || []; }
    get places() { return this.formGroup.get("places").value || []; }
    get categories() { return this.formGroup.get("categories").value || []; }
    get audience() { return this.formGroup.get("audience").value || []; }



    onAppEvent( event : AppEvent ) {
        console.log("EnrichStep: App Event: " + event.type);
        switch(event.type) {
            case 'reset':
                this.hasError = null;
                this.formGroupPrivate.reset();
                break;
        }
    }
}
