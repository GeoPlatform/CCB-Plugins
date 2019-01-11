import { Component, OnInit, Output, ViewChild } from '@angular/core';
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

// <reference path="ng-gpoauth/src/AuthService" />

import { AuthService, GeoPlatformUser } from 'ng-gpoauth/Angular'



export interface AppEvent {
    type   : string;
    value ?: any;
}



@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.less']
})
export class AppComponent implements OnInit {

    @Output() appEvents: Subject<AppEvent>
        = new Subject<AppEvent>();

    @ViewChild('stepper')           stepper: MatStepper;
    @ViewChild(TypeComponent)       step1: StepComponent;
    @ViewChild(AdditionalComponent) step2: StepComponent;
    @ViewChild(EnrichComponent)     step3: StepComponent;
    @ViewChild(ReviewComponent)     step4: StepComponent;

    public item : any;

    //flag indicating user's signed in status
    public isAuthenticated : boolean = false;
    public user : GeoPlatformUser = null;


    constructor(
        private formBuilder: FormBuilder,
        private authService : AuthService
    ) {

        authService.getUser().then( user => {
            this.user = user;
            this.isAuthenticated = user !== null;
            if(!this.item.createdBy) {  //update editable resource's createdBy property
                this.item.createdBy = user ? user.username : null;
            }
        })
        .catch(err => console.log("error fetching user: ", err));
    }

    ngOnInit() {
        this.initItem();
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
            if( typeof(data[key]) !== 'undefined' &&  data[key] !== null &&
                'serviceType' !== key && 'href' !== key
            ) {

                //if user provided a title, ignore whatever the harvest returned for label
                if('label' === key && this.item.title) return;

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

        if('accessURL' === key) {
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

        } else if( 'title' === key || 'label' === key ) {
            this.item.title = this.item.label = value;

        }
        //knowledge graph enrichment fields
        else if(
            'purposes' === key ||  'functions' === key || 'topics' === key ||
            'subjects' === key ||  'places' === key ||  'audience' === key ||
            'categories' === key
        ) {
            if(!this.item.classifiers) {    //initialize kg if not present
                this.item.classifiers = { type: 'regp:KnowledgeGraph' }
            }
            this.item.classifiers[key] = value;

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
            this.applyItemData('type', formGroup.get('type').value);
        }

        let keys = Object.keys(formGroup.controls);
        keys.forEach( key => {
            if('type' === key) return;

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
            // title: 'test',
            // ----------------------------------
            createdBy: this.user ? this.user.username : null
        };
    }
}
