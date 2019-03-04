import { Component, OnInit, OnDestroy, Output, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { StepperSelectionEvent } from '@angular/cdk/stepper';
import { Observable, Subject } from 'rxjs';

import { MatStepper } from '@angular/material';
import { ItemTypes } from 'geoplatform.client';


import { StepComponent, StepEvent } from './steps/step.component';
import { TypeComponent } from './steps/type/type.component';
import { AdditionalComponent } from './steps/additional/additional.component';
import { EnrichComponent } from './steps/enrich/enrich.component';
import { ReviewComponent } from './steps/review/review.component';

import { AuthenticatedComponent } from './authenticated.component';
import { GeoPlatformUser } from 'geoplatform.ngoauth/angular';

import { ModelProperties } from './model';
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

    @Output() appEvents: Subject<AppEvent>
        = new Subject<AppEvent>();

    @ViewChild('stepper')           stepper: MatStepper;
    @ViewChild(TypeComponent)       step1: StepComponent;
    @ViewChild(AdditionalComponent) step2: StepComponent;
    @ViewChild(EnrichComponent)     step3: StepComponent;
    @ViewChild(ReviewComponent)     step4: StepComponent;

    public item : any;

    constructor( private formBuilder: FormBuilder ) {
        super();
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
                'serviceType' !== key && 'href' !== key
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
                this.item.distributions = [{
                    type: 'dcat:Distribution',
                    accessURL: value,
                    title: "Dataset distribution link"
                }];

            } else if(this.item.type === ItemTypes.SERVICE) {
                this.item.href = value;
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
        let formGroup : FormGroup = prevStep.stepControl as FormGroup;
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

        console.log("Item updated:");
        console.log(this.item);

        this.triggerChangeDetection();
    }


    /**
     *
     */
    onStepEvent( event : StepEvent ) {
        switch(event.type) {

            case 'app.reset' :

            this.stepper.reset();

            //clear item
            this.initItem();
            //notify each step to reset their internal form data
            // ...
            //reset stepper to first step
            // this.stepper.selectedIndex = 0;

            let appEvent : AppEvent = { type:'reset', value: true };
            this.appEvents.next(appEvent);
            break;


            case 'service.about' :
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
        // if('development' === environment.env) {
        //     this.item.createdBy = 'tester';
        // }
        if(this.user) {
            this.item[ModelProperties.CREATED_BY] = this.user.username;
        }

        let searchParams = window.location.search;
        if(searchParams) {
            let params : any = {};
            searchParams.replace('?','').split('&').forEach(p => {
                let param = p.split('='), key = param[0], value = param[1];
                params[key] = value;
            });

            if(params.type) {
                this.item.type = params.type;
            }
        }
    }
}
