import { Component, OnInit, OnDestroy, Input, Output, EventEmitter, Inject } from '@angular/core';
import { ISubscription } from "rxjs/Subscription";

import { Constraint, Constraints } from '../models/constraint';
import { Codec } from '../models/codec';

import { ConstraintEditor } from '../models/constraint';

import { EditorRegistry } from '../constraints/';

import { environment } from '../../environments/environment';


export const ConstraintProviderService = {
    listeners: { 'create': [], 'destroy': [] },

    on(type : string, fn: Function) : Function {
        if(this.listeners[type]) {
            let id = Math.random()*9999;
            this.listeners[type].push({id: id, fn: fn});
            return ((id, type) => {
                return () => {
                    let idx = this.listeners[type].findIndex(l=>l.id===id);
                    if(idx>=0)
                        this.listeners[type].splice(idx,1);
                };
            })(id, type);
        }
        return ()=> {};
    },

    registerContainer(container) {
        this.listeners['create'].forEach((l) => {
            l.fn(container);
        })
    },

    destroyContainer(container) {
        this.listeners['destroy'].forEach((l) => {
            l.fn(container);
        })
    }
};



@Component({
  selector: 'constraint-picker',
  templateUrl: './picker.component.html',
  styleUrls: ['./picker.component.css'],

  //apply class to host element based upon boolean property value
  host:     {'[class.is-collapsed]':'isClosed'}
})
export class PickerComponent implements OnInit, OnDestroy {

    @Input() constraints : Constraints;
    @Input() isClosed: boolean = false;
    @Output() onClose: EventEmitter<boolean>= new EventEmitter<boolean>();

    public inPickerMode: boolean = true;
    private activeConstraint : any = null;
    private listener : ISubscription;
    public helpBaseUrl : string = environment.helpUrl;

    private options = EditorRegistry.getEditors().sort((
        a: { key: string; label: string; component: any; },
        b: { key: string; label: string; component: any; }
    )=>{
        //DT-2166 ensure 'types' filter is always at top
        if('Types' === a.label) return -1;
        if('Types' === b.label) return 1;
        if('Type Specializations' === a.label) return -1;
        if('Type Specializations' === b.label) return 1;
        return a.label < b.label ? -1 : 1
    });

    constructor() { }

    ngOnInit() {

        //whenever a constraint is modified, added, or removed, deactivate
        // constraint editor and return to picker list
        this.listener = this.constraints.on((constraint:Constraint) => {
            this.deactivateConstraint();
        });
    }

    ngOnDestroy() {
        if(this.listener)
            this.listener.unsubscribe();
    }

    activateConstraint (option : {label:string,key:string,component:any} ) {
        this.activeConstraint = option;
        this.inPickerMode = false;
    }

    deactivateConstraint () {
        this.activeConstraint = null;
        this.inPickerMode = true;
    }

    applyConstraint (constraint) {
        this.deactivateConstraint();
    }

    toggle () {
        this.deactivateConstraint();
        this.onClose.emit(true);
    }

    onEditorClosed($event) {
        this.deactivateConstraint();
    }

}
