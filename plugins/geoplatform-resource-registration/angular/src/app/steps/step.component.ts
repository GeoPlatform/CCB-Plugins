import { Component, Output, EventEmitter } from '@angular/core';
import { FormGroup } from '@angular/forms';


export interface StepEvent {
    type: string;
    value: any;
}


export interface StepComponent {

    //for sending data upstream
    onEvent : EventEmitter<StepEvent>;

    //for storing local values needed to complete this portion of the process
    formGroup: FormGroup;

}


export class StepError {

    public label : string = "An Error Occurred";
    public message : string = "Something unexpected happened and an unknonwn error has occurred.";

    constructor(...args: any[]) {
        if(args.length < 1) throw new Error("Must specify at least an error message");

        if( args[0] instanceof Error ) {
            this.message = args[0].message;

        } else if(args.length >= 2) {
            this.label = args[0];
            this.message = args[1];
        } else {
            this.message = args[0];
        }
    }
}
