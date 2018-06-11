import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Constraints } from '../../models/constraint';
import { SemanticComponent } from './semantic.component';
import { NgbdTypeaheadHttp } from '../../shared/typeahead';

@Component({
    selector: `host-component`,
    template: `<constraint-semantic [constraints]="constraints"></constraint-semantic>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}



describe('SemanticComponent', () => {
    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule, NgbModule.forRoot() ],
            declarations: [ SemanticComponent, TestHostComponent, NgbdTypeaheadHttp ]
        })
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestHostComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
