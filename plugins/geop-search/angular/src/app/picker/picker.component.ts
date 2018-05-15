import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { ISubscription } from "rxjs/Subscription";

import { Constraint, Constraints } from '../models/constraint';
import { Codec } from '../models/codec';


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

    constructor() {

    }

    ngOnInit() {
        this.listener = this.constraints.on((constraint:Constraint) => {
            this.deactivateConstraint();
        });
    }

    ngOnDestroy() {
        this.listener.unsubscribe();
    }

    activateConstraint (type : any) {
      this.activeConstraint = type;
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

}
