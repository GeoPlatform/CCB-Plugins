import {
    Component, OnInit, OnChanges, OnDestroy, SimpleChanges,
    Input, Output, EventEmitter, ViewChild, ElementRef
} from '@angular/core';
import {
    HttpClient, HttpRequest, HttpHeaders, HttpParams,
    HttpResponse, HttpEvent, HttpErrorResponse
} from '@angular/common/http';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';

import {
    MatAutocompleteSelectedEvent,
    MatChipInputEvent,
    MatAutocomplete
} from '@angular/material';

import { Observable, Subject } from 'rxjs';
import {map, flatMap, startWith} from 'rxjs/operators';
import {
    Config, ItemService, Query, QueryParameters, ItemTypes
} from 'geoplatform.client';


import { AppEvent } from '../../app.component';
import { StepComponent, StepEvent, StepError } from '../step.component';
import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../http-client';

import { ModelProperties } from '../../model';
import { itemServiceProvider } from '../../item-service.provider';


@Component({
  selector: 'wizard-step-additional',
  templateUrl: './additional.component.html',
  styleUrls: ['./additional.component.less'],
  providers: [ itemServiceProvider ]
})
export class AdditionalComponent implements OnInit, OnDestroy, StepComponent {

    //for pre-populating values from the parent component
    @Input() data : any = null;
    @Input() appEvents: Observable<AppEvent>;

    //for notifying parent component when necessary
    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();


    //for storing model values for usage in the workflow proper
    public formGroup: FormGroup;
    public hasError : StepError;

    // private itemService : ItemService = null;
    private eventsSubscription: any;

    @ViewChild('keywordsInput') keywordsField: ElementRef;

    formOpts : any = {};


    constructor(
        private formBuilder: FormBuilder,
        // private http : HttpClient
        private itemService : ItemService
    ) {
        //initialize form controls
        this.formOpts[ModelProperties.KEYWORDS] = [''];
        this.formOpts['$'+ModelProperties.KEYWORDS] = [''];
        // this.formOpts[ModelProperties.PUBLISHERS] = [''];
        // this.formOpts['$'+ModelProperties.PUBLISHERS] = [''];
        this.formOpts[ModelProperties.COMMUNITIES] = [''];
        this.formOpts['$'+ModelProperties.COMMUNITIES] = [''];
        this.formGroup = this.formBuilder.group(this.formOpts);

        // let client = new NG2HttpClient(http);
        // this.itemService = new ItemService(Config.ualUrl, client);
    }

    /**
     *
     */
    ngOnInit() {

        this.eventsSubscription = this.appEvents.subscribe( (event:AppEvent) => {
            this.onAppEvent(event);
        });
    }

    /**
     *
     */
    ngOnChanges( changes : SimpleChanges ) {

        //pre-populate data if the parent component injected values in
        if(changes.data) {

            let data = changes.data.currentValue;
            if(!data) {
                this.formGroup.reset();

            } else {

                if(data[ModelProperties.KEYWORDS] && data[ModelProperties.KEYWORDS].length) {
                    this.formGroup.get(ModelProperties.KEYWORDS)
                        .setValue(data[ModelProperties.KEYWORDS]);
                }
                // if(data[ModelProperties.PUBLISHERS] && data[ModelProperties.PUBLISHERS].length) {
                //     this.formGroup.get(ModelProperties.PUBLISHERS)
                //         .setValue(data[ModelProperties.PUBLISHERS]);
                // }
                if(data[ModelProperties.COMMUNITIES] && data[ModelProperties.COMMUNITIES].length) {
                    this.formGroup.get(ModelProperties.COMMUNITIES)
                        .setValue(data[ModelProperties.COMMUNITIES]);
                }
            }
        }
    }

    /**
     *
     */
    ngOnDestroy() {
        this.eventsSubscription.unsubscribe();
    }


    // private filterCommunities(value: string): Promise<string[]> {
    filterCommunities = (value: string) : Promise<string[]> => {
        let current = this.getValues(ModelProperties.COMMUNITIES);
        current = current.map(c=>c.id);

        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;
        let query = new Query().types(ItemTypes.COMMUNITY).q(filterValue);
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
            this.hasError = new StepError("Error Searching Communities", e.message);
        });
    }


    addKeyword(event: MatChipInputEvent): void {

        const type = ModelProperties.KEYWORDS;
        const input = event.input;
        const value = event.value;

        // Add our value
        if (value) {
            let val = value;
            if(typeof(value) === 'string') val = value.trim();
            let existing = this.formGroup.get(type).value || [];
            if(existing.indexOf(value) < 0) {
                existing.push(val);
                this.formGroup.get(type).setValue(existing);
            }
        }

        // Reset the input value
        if (input) {
            input.value = '';
            input.blur();
        }

        //clear the local form group so the autocomplete empties
        this.formGroup.get('$'+type).setValue(null);

    }


    removeKeyword(key: string): void {
        let existing = this.formGroup.get(ModelProperties.KEYWORDS).value;
        const index = existing.indexOf(key);
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get(ModelProperties.KEYWORDS).setValue(existing);
        }
    }


    get keywords() {
        return this.formGroup.get(ModelProperties.KEYWORDS).value || [];
    }

    public getValues ( key : string ) : any[] {
        let existing = this.formGroup.get(key).value;
        if(!existing) return [];
        if(!Array.isArray(existing)) return [existing];
        return existing;
    }

    onAppEvent( event : AppEvent ) {
        console.log("AdditionalStep: App Event: " + event.type);
        switch(event.type) {
            case 'reset':
                this.hasError = null;
                break;
        }
    }

}
