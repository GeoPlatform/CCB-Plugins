import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Constraints } from '../../models/constraint';
import { TemporalComponent } from './temporal.component';


@Component({
    selector: `host-component`,
    template: `<constraint-temporal [constraints]="constraints"></constraint-temporal>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}



describe('TemporalComponent', () => {
    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule ],
            declarations: [ TemporalComponent, TestHostComponent ]
        })
        .compileComponents();
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
