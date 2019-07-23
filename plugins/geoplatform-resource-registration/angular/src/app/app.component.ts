import { Component, OnInit, OnDestroy, Output, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { StepperSelectionEvent } from '@angular/cdk/stepper';
import { Observable, Subject } from 'rxjs';
import { MatStepper, MatIconRegistry } from '@angular/material';
import { ItemTypes, ItemService } from '@geoplatform/client';
import * as Q from "q";

import { itemServiceFactory } from './item-service.provider';
import { StepComponent, StepEvent } from './steps/step.component';
import { TypeComponent } from './steps/type/type.component';
import { AdditionalComponent } from './steps/additional/additional.component';
import { EnrichComponent } from './steps/enrich/enrich.component';
import { ReviewComponent } from './steps/review/review.component';

import { PluginAuthService }    from './auth.service';
import { AuthenticatedComponent } from './authenticated.component';
import { GeoPlatformUser } from '@geoplatform/oauth-ng/angular/angular';

import {
    ModelProperties, AppEventTypes, StepEventTypes
} from './model';
import { environment } from '../environments/environment';



export interface AppEvent {
    type   : string;
    value ?: any;
}


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.less']
})
export class AppComponent extends AuthenticatedComponent implements OnInit {

    @Output() appEvents: Subject<AppEvent> = new Subject<AppEvent>();

    /*
     * Using "static:true" to prevent ExpressionHasChanged errors involving the
     * active stepControl set in the app.component template
     */
    @ViewChild('stepper', {static:true})           stepper: MatStepper;
    @ViewChild(TypeComponent, {static:true})       step1: StepComponent;
    @ViewChild(AdditionalComponent, {static:true}) step2: StepComponent;
    @ViewChild(EnrichComponent, {static:true})     step3: StepComponent;
    @ViewChild(ReviewComponent, {static:true})     step4: StepComponent;

    public item : any;
    private itemService : ItemService;


    constructor(
        private formBuilder: FormBuilder,
        matIconRegistry: MatIconRegistry,
        authService : PluginAuthService,
        http: HttpClient
    ) {
        super(authService);
        this.itemService = itemServiceFactory(http);
        matIconRegistry.registerFontClassAlias('fontawesome', 'fas');
        matIconRegistry.registerFontClassAlias('geoplatform-icons-font', 'gp');

    }

    ngOnInit() {
        super.init();
        this.initItem();
    }

    ngOnDestroy() {
        super.destroy();
        this.item = null;
    }


    /**
     * @param {GeoPlatformUser} user
     * @override AuthenticatedComponent.onUserChange
     */
    onUserChange(user) {
        // console.log("User Event: " + JSON.stringify(user));
        if(this.item && user) {
            //update editable resource's createdBy property
            this.item[ModelProperties.CREATED_BY] = user.username;
        }
        // console.log("Setting created by: " + this.item[ModelProperties.CREATED_BY]);
        let appEvent : AppEvent = {
            type:'auth',
            value: {
                user: user,
                token: this.getAuthToken()
            }
        };
        this.appEvents.next(appEvent);
    }


    /**
     *
     */
    get stepOneForm() { return this.step1 ? this.step1.formGroup : null; }
    get stepTwoForm() { return this.step2 ? this.step2.formGroup : null; }
    get stepThreeForm() { return this.step3 ? this.step3.formGroup : null; }
    get stepFourForm() { return this.step4 ? this.step4.formGroup : null; }


    /**
     *
     */
    prePopulateService(data) {

        if(!data) return;

        Object.keys(data).forEach( key => {
            if( typeof(data[key]) !== 'undefined' &&
                data[key] !== null &&
                ModelProperties.SERVICE_TYPE !== key &&
                ModelProperties.ACCESS_URL !== key
            ) {

                //if user provided a title, ignore whatever the harvest returned for label
                if(ModelProperties.LABEL === key && this.item[ModelProperties.TITLE]) return;

                this.applyItemData(key, data[key]);
            }
        });
    }



    /**
     *
     */
    applyItemData( key : string, value : any ) {
        if(value === null || value === undefined ||
            ( typeof(value) === 'string' && !value.length) ) return;
        if(Array.isArray(value)) {
            value = value.filter(v=>!!v);   //filter out null values
            if(!value.length ) return;  //empty arrays
        }

        if(ModelProperties.ACCESS_URL === key) {
            if(this.item.type === ItemTypes.DATASET) {
                //set as distro link
                let distro = {type: 'dcat:Distribution',title: "Dataset distribution link"};
                distro[ModelProperties.ACCESS_URL] = value;
                this.item.distributions = [distro];

            } else {
                this.item[ModelProperties.ACCESS_URL] = value;
            }

        } else if( ModelProperties.TITLE === key || ModelProperties.LABEL === key ) {
            this.item[ModelProperties.TITLE] = this.item[ModelProperties.LABEL] = value;

        }
        //knowledge graph enrichment fields
        else if(
            ModelProperties.CLASSIFIERS_PURPOSE === key ||
            ModelProperties.CLASSIFIERS_FUNCTION === key ||
            ModelProperties.CLASSIFIERS_TOPIC_PRIMARY === key ||
            ModelProperties.CLASSIFIERS_TOPIC_SECONDARY === key ||
            ModelProperties.CLASSIFIERS_SUBJECT_PRIMARY === key ||
            ModelProperties.CLASSIFIERS_SUBJECT_SECONDARY === key ||
            ModelProperties.CLASSIFIERS_PLACE === key ||
            ModelProperties.CLASSIFIERS_AUDIENCE === key ||
            ModelProperties.CLASSIFIERS_CATEGORY === key
        ) {
            if(!this.item.classifiers) {    //initialize kg if not present
                this.item.classifiers = { type: 'regp:KnowledgeGraph' }
            }
            this.item.classifiers[key] = value;

        } else if(ModelProperties.RESOURCE_TYPES === key) {
            //resource types are selected using objects, but the model accepts
            // them as uris, so we have to transform them
            this.item[key] = value.map(v => v.uri);

        } else if(ModelProperties.FORM_THUMBNAIL_URL === key) {
            if(value && value.length) {
                this.item[ModelProperties.THUMBNAIL] = this.item[ModelProperties.THUMBNAIL] || {};
                this.item[ModelProperties.THUMBNAIL][ModelProperties.THUMBNAIL_URL] = value;
            } else {
                this.item[ModelProperties.THUMBNAIL] = null;
            }

        } else if(ModelProperties.FORM_THUMBNAIL_CONTENT === key) {
            if(value && value.length) {
                this.item[ModelProperties.THUMBNAIL] = this.item[ModelProperties.THUMBNAIL] || {};
                this.item[ModelProperties.THUMBNAIL][ModelProperties.THUMBNAIL_CONTENT] = value;
            } else {
                this.item[ModelProperties.THUMBNAIL] = null;
            }

        } else if(ModelProperties.THEME_SCHEME === key) {
            //ignore

        } else { //generic property
            this.item[key] = value;
        }
    }




    /**
     * change the root object in order to trigger Angular change detection
     */
    triggerChangeDetection() {
        this.item = JSON.parse(JSON.stringify(this.item));
    }



    /**
     *
     */
    onStepperEvent( event : StepperSelectionEvent ) {
        // console.log("Stepper Event");
        // console.log(event);

        let prevStep = event.previouslySelectedStep;
        if(!prevStep) { console.log("Stepper error!"); return; }
        let formGroup : FormGroup = (prevStep.stepControl as any) as FormGroup;
        if(!formGroup) { console.log("Stepper error!"); return; }

        //handle type first since it's needed by other fields
        // but remember type is only set on the first step of the wizard
        if(0 === event.previouslySelectedIndex) {
            this.applyItemData(ModelProperties.TYPE,
                formGroup.get(ModelProperties.TYPE).value);
        }

        let keys = Object.keys(formGroup.controls);
        keys.forEach( key => {
            if(ModelProperties.TYPE === key) return;
            if(key[0] === '$') return;  //ignore 'hidden' properties

            let ctrl = formGroup.controls[key];
            this.applyItemData(key, ctrl.value);
        });

        if(!environment.production) {
            console.log("Item updated:");
            console.log(this.item);
        }

        this.triggerChangeDetection();
    }


    /**
     *
     */
    onStepEvent( event : StepEvent ) {
        switch(event.type) {

            case StepEventTypes.RESET :

            this.stepper.reset();

            //clear item
            this.initItem();
            //notify each step to reset their internal form data
            // ...
            //reset stepper to first step
            // this.stepper.selectedIndex = 0;

            let appEvent : AppEvent = {
                type: AppEventTypes.RESET,
                value: true
            };
            this.appEvents.next(appEvent);
            break;


            case StepEventTypes.SERVICE_INFO :
            // console.log("Caching service harvest for steps : ");
            // console.log(event.value);

            // this.serviceInfo = event.value;
            this.prePopulateService(event.value);

            break;
            default : console.log("Unrecognized step event '" + event.type + "'");
        }
    }



    /**
     *
     */
    initItem () {
        this.item = {
            // TEMPORARY for dev purposes only...
            // type: 'dcat:Dataset',
            // title: 'test2',
            // ----------------------------------
        };
        if('development' === environment.env) {
           this.item.createdBy = 'tester';
        }
        if(this.user) {
            this.item[ModelProperties.CREATED_BY] = this.user.username;
        }

        //pre-populate some fields based upon query parameters provided
        let searchParams: string = window.location.search;
        if(searchParams) {
            let params : { [key:string]:any } = {} as { [key:string]:any };
            searchParams.replace('?','').split('&').forEach(p => {
                let param = p.split('='), key = param[0], value = param[1];
                params[key] = value;
            });

            this.applyPresets(params)
            .then( () => {

                if(!environment.production) {
                    console.log("Item Initialized:");
                    console.log(this.item);
                }
                this.triggerChangeDetection();

                //notify children that the item has changed and
                // should trigger a refresh
                let appEvent : AppEvent = {
                    type: AppEventTypes.CHANGE,
                    value: this.item
                };
                this.appEvents.next(appEvent);
            });
        }

    }


    /**
     *
     */
    applyPresets(params : { [key:string]:any } ) : Promise<void> {

        if(params.type) {
            this.item[ModelProperties.TYPE] = params.type;
        }

        if(params.title) {
            this.item[ModelProperties.TITLE] =
                this.item[ModelProperties.LABEL] =
                    (decodeURIComponent(params.title) || "").trim();
        }

        let keys = params.keywords || params.keyword;
        this.item[ModelProperties.KEYWORDS] = Array.isArray(keys) ? keys : [];


        if(params.createdBy && 'development' === environment.env) {
            this.item[ModelProperties.CREATED_BY] = params.createdBy;
        }


        //Now, resolve any presets that are assets (values are IDs but we need
        //to fetch their full object representation before setting)

        let cids = params.communities || params.community;
        if(cids && !Array.isArray(cids)) cids = [cids];

        let thids = params.themes || params.theme;
        if(thids && !Array.isArray(thids)) thids = [thids];

        let toids = params.topics || params.topic;
        if(toids && !Array.isArray(toids)) toids = [toids];


        let tbd = [].concat(cids||[], thids||[], toids||[]);

        if(!tbd.length) return Promise.resolve();

        return this.itemService.getMultiple(tbd)
        .then( ( results : {[key:string]:any}[] ) => {

            results.forEach( result => {
                //TODO eventually the items being resolved will be of the same type
                // but for different association properties. but for now, each
                // association is of a specific type, so we determine where to put
                // each resolved asset according to its type...
                switch(result.type) {
                    case ItemTypes.COMMUNITY :
                    if(!this.item[ModelProperties.COMMUNITIES])
                        this.item[ModelProperties.COMMUNITIES] = [];
                    this.item[ModelProperties.COMMUNITIES].push(result);
                    break;
                    case ItemTypes.CONCEPT :
                    if(!this.item[ModelProperties.THEMES])
                        this.item[ModelProperties.THEMES] = [];
                    this.item[ModelProperties.THEMES].push(result);
                    break;
                    case ItemTypes.ORGANIZATION :
                    if(!this.item[ModelProperties.PUBLISHERS])
                        this.item[ModelProperties.PUBLISHERS] = [];
                    this.item[ModelProperties.PUBLISHERS].push(result);
                    break;
                    case ItemTypes.TOPIC :
                    if(this.item[ModelProperties.ASSETS])
                        this.item[ModelProperties.ASSETS] = [];
                    this.item[ModelProperties.ASSETS].push(result);
                }
            });

            return;

        })
        .catch( (error:Error) => {
            console.log("Error resolving preset assets: " + error.message);
            //TODO display warning to user that some values couldn't be preset...
            return Promise.resolve();
        })

    }
}
