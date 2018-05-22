
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
// import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
// import { InlineSVGModule } from 'ng-inline-svg';

import { Constraints } from '../models/constraint';
import { PickerComponent } from './picker.component';
import { EditorComponent } from './editor/editor.component';


@Component({
    selector: `host-component`,
    template: `<constraint-picker [constraints]="constraints" [isClosed]="false"></constraint-picker>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}



describe('PickerComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule /*, HttpClientModule, InlineSVGModule*/ ],
            declarations: [ PickerComponent, EditorComponent, TestHostComponent ]
        })
        .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestHostComponent);
        component = fixture.componentInstance;
        hostEl = fixture.nativeElement;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });

});
