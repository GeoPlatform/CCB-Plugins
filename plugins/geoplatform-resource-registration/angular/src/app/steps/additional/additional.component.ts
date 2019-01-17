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


@Component({
  selector: 'wizard-step-additional',
  templateUrl: './additional.component.html',
  styleUrls: ['./additional.component.less']
})
export class AdditionalComponent implements OnInit, OnDestroy, StepComponent {

    //for pre-populating values from the parent component
    @Input() data : any = null;
    @Input() appEvents: Observable<AppEvent>;

    //for notifying parent component when necessary
    @Output() onEvent : EventEmitter<StepEvent> = new EventEmitter<StepEvent>();


    //for storing model values for usage in the workflow proper
    public formGroup: FormGroup;

    //for storing intermediate internal model values only for use within this step
    public formGroupPrivate: FormGroup;

    public hasError : StepError;

    public filteredPublisherOptions: Observable<string[]>;
    public filteredCommunityOptions: Observable<string[]>;

    itemService : ItemService = null;
    private eventsSubscription: any;

    @ViewChild('pubAutoComplete') pubMatAutocomplete: MatAutocomplete;
    @ViewChild('comAutoComplete') comMatAutocomplete: MatAutocomplete;

    @ViewChild('keywordsInput') keywordsField: ElementRef;
    @ViewChild('publishersInput') publishersField: ElementRef;
    @ViewChild('communitiesInput') communitiesField: ElementRef;


    formOpts = {
        keywords: [''],
        publishers: [''],
        communities: ['']
    };


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient
    ) {
        //initialize form controls
        this.formGroup = this.formBuilder.group(this.formOpts);
        this.formGroupPrivate = this.formBuilder.group(Object.assign({}, this.formOpts));

        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    /**
     *
     */
    ngOnInit() {

        //set up filtering piping on the autocomplete fields
        this.filteredPublisherOptions = this.formGroupPrivate.get('publishers').valueChanges.pipe(
            startWith(''),
            flatMap(value => {
                let result = this.filterPublishers(value);
                return result;
            })
        );
        this.filteredCommunityOptions = this.formGroupPrivate.get('communities').valueChanges.pipe(
            startWith(''),
            flatMap(value => this.filterCommunities(value))
        );


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
                this.formGroupPrivate.reset();

            } else {

                //clear the current internal forms (maybe?)
                this.formGroupPrivate.reset();

                if(data.keywords && data.keywords.length) {
                    this.formGroup.get("keywords").setValue(data.keywords);
                }
                if(data.publishers && data.publishers.length) {
                    this.formGroup.get("publishers").setValue(data.publishers);
                }
                if(data.communities && data.communities.length) {
                    this.formGroup.get("communities").setValue(data.communities);
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



    private filterPublishers(value: string): Promise<string[]> {
        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;
        let query = new Query().types(ItemTypes.ORGANIZATION).q(filterValue);
        return this.itemService.search(query)
        .then( response => {
            return response.results
        })
        .catch(e => {
            //display error message indicating an issue searching...
            this.hasError = new StepError("Error Searching Publishers", e.message);
        });
    }
    private filterCommunities(value: string): Promise<string[]> {
        const filterValue = typeof(value) === 'string' ? value.toLowerCase() : null;
        let query = new Query().types(ItemTypes.COMMUNITY).q(filterValue);
        return this.itemService.search(query)
        .then( response => response.results )
        .catch(e => {
            //display error message indicating an issue searching...
            this.hasError = new StepError("Error Searching Communities", e.message);
        });
    }


    addKeyword(event: MatChipInputEvent): void {
        this.addChip('keywords', event);
    }

    addPublisher(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        // To make sure this does not conflict with OptionSelected Event
        if (!this.pubMatAutocomplete.isOpen) {
            this.addChip('publishers', event);
        }
    }

    addCommunity(event: MatChipInputEvent): void {
        // Add only when MatAutocomplete is not open
        // To make sure this does not conflict with OptionSelected Event
        if (!this.comMatAutocomplete.isOpen) {
            this.addChip('communities', event);
        }
    }


    private addChip( type: string, event: MatChipInputEvent ) {

        const input = event.input;
        const value = event.value;

        // Add our value
        if (value) {
            let val = value;
            if(typeof(value) === 'string') val = value.trim();
            let existing = this.formGroup.get(type).value || [];
            existing.push(val);
            this.formGroup.get(type).setValue(existing);
        }

        // Reset the input value
        if (input) {
            input.value = '';
        }

        //clear the local form group so the autocomplete empties
        this.formGroupPrivate.get(type).setValue(null);

    }


    removeKeyword(key: string): void {
        let existing = this.formGroup.get("keywords").value;
        const index = existing.indexOf(key);
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get("keywords").setValue(existing);
        }
    }
    removePublisher(pub: any): void {
        let existing = this.formGroup.get("publishers").value;
        let index = -1;
        existing.forEach( (p,i) => { if(p.id === pub.id) { index = i; } });
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get("publishers").setValue(existing);
        }
    }
    removeCommunity(com: any): void {
        let existing = this.formGroup.get("communities").value;
        let index = -1;
        existing.forEach( (p,i) => { if(p.id === com.id) { index = i; } });
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get("communities").setValue(existing);
        }
    }



    onPubAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        let existing = this.formGroup.get("publishers").value || [];
        existing.push(event.option.value);
        this.formGroup.get("publishers").setValue(existing);

        //clear input and blur so autocomplete isn't left in weird state after selection
        this.publishersField.nativeElement.value='';
        this.publishersField.nativeElement.blur();
        this.formGroupPrivate.get("publishers").setValue(null);
    }

    onComAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        let existing = this.formGroup.get("communities").value || [];
        existing.push(event.option.value);
        this.formGroup.get("communities").setValue(existing);

        //clear input and blur so autocomplete isn't left in weird state after selection
        this.communitiesField.nativeElement.value='';
        this.communitiesField.nativeElement.blur();
        this.formGroupPrivate.get("communities").setValue(null);
    }



    get keywords() {
        return this.formGroup.get("keywords").value || [];
    }

    get publishers() {
        return this.formGroup.get("publishers").value || [];
    }

    get communities() {
        return this.formGroup.get("communities").value || [];
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
                this.formGroupPrivate.reset();
                break;
        }
    }

}
