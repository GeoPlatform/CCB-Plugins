import {
    Component,
    OnInit, OnDestroy, OnChanges,
    SimpleChanges, SimpleChange,
    Input, Output,
    EventEmitter,
    Directive,
    ViewContainerRef,
    ViewChild,
    ComponentFactoryResolver,
    Inject
} from '@angular/core';


import { ConstraintEditor, Constraints } from '../../models/constraint';

import { CreatorComponent } from '../../constraints/creator/creator.component';
import { KeywordsComponent } from '../../constraints/keywords/keywords.component';
import { ContactComponent } from '../../constraints/contact/contact.component';
import { PublisherComponent } from '../../constraints/publisher/publisher.component';
import { ExtentComponent } from '../../constraints/extent/extent.component';
import { TemporalComponent } from '../../constraints/temporal/temporal.component';
import { ThemeComponent } from '../../constraints/theme/theme.component';
import { TypeComponent } from '../../constraints/type/type.component';


import { ConstraintProviderService } from '../picker.component';






@Directive({
  selector: '[constraint-host]',
})
export class ConstraintDirective implements OnDestroy {
    constructor( public vc: ViewContainerRef,
        @Inject("constraint-provider-service") private shared) {
        shared.registerContainer(vc);
    }
    ngOnDestroy() {
        this.shared.destroyContainer(this.vc);
        this.shared = null;
        this.vc = null;
    }
}






@Component({
    selector: 'picker-editor',
    templateUrl: './editor.component.html',
    styleUrls: ['./editor.component.css'],
    providers: [
      {
        provide: 'constraint-provider-service',
        useValue: ConstraintProviderService
      }
    ]
})
export class EditorComponent implements OnInit, OnChanges, OnDestroy {

    @Input() component : any;
    @Input() constraints : Constraints;
    @Output() onClose: EventEmitter<boolean>= new EventEmitter<boolean>();


    constraintViewContainer: ViewContainerRef;
    private vcCreateListener : Function = null;
    private vcDestroyListener : Function = null;

    constructor(
        private componentFactoryResolver: ComponentFactoryResolver,
        @Inject("constraint-provider-service") shared
    ) {
        this.vcCreateListener = shared.on('create', (container) => {
            // console.log("Container Created");
            this.constraintViewContainer = container;
            if(this.component)
                this.loadComponent();
            this.vcCreateListener();
            this.vcCreateListener = null;
        });
        this.vcDestroyListener = shared.on('destroy', () => {
            // console.log("Container Destroyed");
            this.constraintViewContainer = null;
            this.vcDestroyListener();
            this.vcDestroyListener = null;
        });
    }

    ngOnInit() {
        if(this.constraintViewContainer && this.component) {
            this.loadComponent();
        }
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.component) {
            let constraints = changes.constraints.currentValue;
            if(this.constraintViewContainer) {
                this.loadComponent();
            }
        }
    }

    ngOnDestroy() {
        this.constraintViewContainer = null;
        if(this.vcCreateListener) {
            this.vcCreateListener();
            this.vcCreateListener = null;
        }
        if(this.vcDestroyListener) {
            this.vcDestroyListener();
            this.vcDestroyListener = null;
        }
    }

    deactivateConstraint() {
        this.onClose.emit(true);
    }

    loadComponent() {
        let componentFactory = this.componentFactoryResolver.resolveComponentFactory(this.component);
        let viewContainerRef = this.constraintViewContainer;
        viewContainerRef.clear();

        let componentRef = viewContainerRef.createComponent(componentFactory);
        (<ConstraintEditor>componentRef.instance).constraints = this.constraints;
    }
}
