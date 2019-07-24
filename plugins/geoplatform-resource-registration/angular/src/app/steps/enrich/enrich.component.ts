import {
    Inject, Component, OnInit, OnChanges, OnDestroy, SimpleChanges,
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
} from '@geoplatform/client';

import { AppEvent } from '../../app.component';
import {
    StepComponent, StepEvent, StepError
} from '../step.component';
import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../http-client';

import {
    ModelProperties, ClassifierTypes, AppEventTypes
} from '../../model';
import { kgServiceFactory } from '../../item-service.provider';



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
    public PROPS : any = ModelProperties;

    private eventsSubscription: any;
    private kgService : KGService;

    formOpts : any = {};


    constructor(
        private formBuilder: FormBuilder,
        @Inject(KGService) kgService : KGService
    ) {
        this.kgService = kgService;

        //initialize form controls
        Object.keys(ClassifierTypes).forEach( key => {
            if(~key.indexOf('secondary')) return;
            this.formOpts[key] =  [''];     //fields for classifiers we care about
            this.formOpts['$'+key] =  ['']; //temp fields for autocompletes
        });
        this.formGroup = this.formBuilder.group(this.formOpts);
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


    filterPurposes = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_PURPOSE, value);
    }
    filterFunctions = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_FUNCTION, value);
    }
    filterTopics = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_TOPIC_PRIMARY, value);
    }
    filterSubjects = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY, value);
    }
    filterAudiences = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_AUDIENCE, value);
    }
    filterCategories = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_CATEGORY, value);
    }
    filterPlaces = (value: string): Promise<string[]> => {
        return this.filterResultsForType(ModelProperties.CLASSIFIERS_PLACE, value);
    }

    /**
     * @param {string} type - form control key to enable value filtering for
     * @param {string} value - user input to use to filter options
     * @return {Promise} resolving list of strings
     */
    private filterResultsForType(type: string, value: string): Promise<string[]> {

        let current = this.getValues(type);
        current = current.map(c=>c.uri);

        //check for null/empty value (to prevent suggestions without inputs from user)
        //check for short inputs to force user to provide minimum # of chars
        if(!value || value.length < 2) return Promise.resolve([]);

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;

        let cType = ClassifierTypes[type];
        let query = new KGQuery().q(filterValue).classifiers(cType);

        if(this.data && this.data[ModelProperties.TYPE]) //filter by Item type
            query.setTypes(this.data[ModelProperties.TYPE]);

        return this.kgService.suggest(query)
        .then( response => {
            let hits = response.results;
            if(current && current.length) {
                hits = hits.filter(o => { return current.indexOf(o.uri)<0; });
            }
            return hits;
        })
        .catch(e => {
            //display error message indicating an issue searching...
        });
    }



    onAppEvent( event : AppEvent ) {
        console.log("EnrichStep: App Event: " + event.type);
        switch(event.type) {
            case AppEventTypes.RESET:
                this.hasError = null;
                break;
        }
    }


    public getValues ( key : string ) : any[] {
        let existing = this.formGroup.get(key).value;
        if(!existing) return [];
        if(!Array.isArray(existing)) return [existing];
        return existing;
    }

}
