import {
    Component, OnInit, OnChanges, OnDestroy, SimpleChanges,
    Input, Output, EventEmitter, ViewChild, ElementRef
} from '@angular/core';
import {
    HttpClient, HttpRequest, HttpHeaders, HttpParams,
    HttpResponse, HttpEvent, HttpErrorResponse, HttpEventType
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


const URL_VALIDATOR = Validators.pattern("https?://.+");

interface ISize { width: number; height: number; }


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
    public thumbError : Error;

    private eventsSubscription: any;
    private authToken : string;

    @ViewChild('keywordsInput') keywordsField: ElementRef;
    @ViewChild('thumbFileInput') thumbFile: ElementRef;

    formOpts : any = {};


    constructor(
        private formBuilder: FormBuilder,
        private http : HttpClient,
        private itemService : ItemService
    ) {
        //initialize form controls
        this.formOpts[ModelProperties.KEYWORDS] = [''];
        this.formOpts['$'+ModelProperties.KEYWORDS] = [''];
        this.formOpts[ModelProperties.COMMUNITIES] = [''];
        this.formOpts['$'+ModelProperties.COMMUNITIES] = [''];
        this.formOpts[ModelProperties.LANDING_PAGE] = ['', URL_VALIDATOR];
        this.formOpts[ModelProperties.THUMBNAIL_URL] = ['', URL_VALIDATOR];
        this.formOpts[ModelProperties.THUMBNAIL_CONTENT] = [''];
        this.formGroup = this.formBuilder.group(this.formOpts);

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

                if(data[ModelProperties.COMMUNITIES] && data[ModelProperties.COMMUNITIES].length) {
                    this.formGroup.get(ModelProperties.COMMUNITIES)
                        .setValue(data[ModelProperties.COMMUNITIES]);
                }

                if(data[ModelProperties.LANDING_PAGE]) {
                    this.formGroup.get(ModelProperties.LANDING_PAGE)
                        .setValue(data[ModelProperties.LANDING_PAGE]);
                }

                if(data[ModelProperties.THUMBNAIL_URL]) {
                    this.formGroup.get(ModelProperties.THUMBNAIL_URL)
                        .setValue(data[ModelProperties.THUMBNAIL_URL]);
                    this.formGroup.get(ModelProperties.THUMBNAIL_CONTENT).setValue(null);
                }
                if(data[ModelProperties.THUMBNAIL_CONTENT]) {
                    this.formGroup.get(ModelProperties.THUMBNAIL_CONTENT)
                        .setValue(data[ModelProperties.THUMBNAIL_CONTENT]);
                    this.formGroup.get(ModelProperties.THUMBNAIL_URL).setValue(null);
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
                this.clearThumbnailFile();
                this.thumbError = null;
                break;
            case 'auth':
                this.authToken = event.value.token;
                break;
        }
    }

    /**
     * @param {string} field
     * @return {any}
     */
    getValue(field) { return this.formGroup.get(field).value; }

    /**
     * @param {string} field
     * @param {any} value
     */
    setValue(field, value) { this.formGroup.get(field).setValue(value); }

    /** */
    hasValue(field) { return !!this.formGroup.get(field).value; }

    /**
     * @param {string} fieldName
     * @return {boolean}
     */
    isInvalid(fieldName) { return this.formGroup.get(fieldName).invalid; }


    /**
     *
     */
    getErrorMessage(fieldName) {
        if(this.formGroup.get(fieldName).hasError('required'))
            return 'You must enter a value';
        if(this.formGroup.get(fieldName).hasError('pattern'))
            return 'You must provide a valid url';
        return null;
    }

    triggerFileUpload() {
        this.thumbFile.nativeElement.click();
    }

    onFileUpload() {
        let file = this.thumbFile.nativeElement.files[0];
        var reader = new FileReader();
        reader.onload = (e) => {

            this.getImgSize(reader.result).subscribe( (dims) => {

                if(dims.width > 800 || dims.height > 600) {
                    this.thumbError = new Error(
                        "Image size is too large. Either reduce it's size, " +
                        "choose another image to use, or use the URL of a " +
                        "web-accessible image. Max size is 800px x 600px"
                    );

                } else {
                    this.thumbError = null;
                    let encoded = reader.result.replace(/^data:(.*;base64,)?/, '');
                    if ((encoded.length % 4) > 0) {
                        encoded += '='.repeat(4 - (encoded.length % 4));
                    }
                    this.formGroup.get(ModelProperties.THUMBNAIL_URL).setValue(null);
                    this.formGroup.get(ModelProperties.THUMBNAIL_CONTENT).setValue(encoded);
                }
            });


        };
        reader.readAsDataURL(file);

    }

    onThumbnailUrlChange($event) {
        this.clearThumbnailFile();  //remove any uploaded file if there is one
    }

    clearThumbnailFile() {
        if(this.thumbFile) {
            this.thumbFile.nativeElement.value = "";
            this.setValue(ModelProperties.THUMBNAIL_CONTENT, null);
        }
    }

    /**
     * @param imageSrc - base64 encoded datastr or URL of image to load to determine size
     * @return Observable resolving loaded image size
     */
    getImgSize(imageSrc: string): Observable<ISize> {
        let mapLoadedImage = (event): ISize => {
            return {
                width: event.target.width,
                height: event.target.height
            };
        }
        var image = new Image();
        let $loadedImg = Observable.fromEvent(image, "load").take(1).map(mapLoadedImage);
        image.src = imageSrc;
        return $loadedImg;
    }
}
