import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Constraints } from '../../models/constraint';
import { CkanComponent } from './ckan.component';
import { LimitToPipe } from '../../shared/pipes';

@Component({
    selector: `host-component`,
    template: `<results-ckan [constraints]="constraints"></results-ckan>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}

describe('CkanComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule ],
            declarations: [ CkanComponent, TestHostComponent, LimitToPipe ]
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
