import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { APP_BASE_HREF } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { InlineSVGModule } from 'ng-inline-svg';

import { Constraints } from '../../models/constraint';
import { TypeComponent } from './type.component';


@Component({
    selector: `host-component`,
    template: `<constraint-types [constraints]="constraints"></constraint-types>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}



describe('TypeComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule, InlineSVGModule ],
            declarations: [ TypeComponent, TestHostComponent ],
            providers: [{ provide: APP_BASE_HREF, use: '/' }]
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
