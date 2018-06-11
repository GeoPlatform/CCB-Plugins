import { Component } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Constraints } from '../../models/constraint';
import { CreatorComponent } from './creator.component';


@Component({
    selector: `host-component`,
    template: `<constraint-creator [constraints]="constraints"></constraint-creator>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}



describe('CreatorComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule ], 
            declarations: [ CreatorComponent, TestHostComponent ]
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
