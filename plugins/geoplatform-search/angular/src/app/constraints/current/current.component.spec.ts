import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { Constraint, MultiValueConstraint, Constraints } from '../../models/constraint';
import { CurrentComponent } from './current.component';


@Component({
    selector: `host-component`,
    template: `<constraints-current [constraints]="constraints"></constraints-current>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}


describe('CurrentComponent', () => {

    let hostComponent: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule ],
            declarations: [ CurrentComponent, TestHostComponent ]
        })
        .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestHostComponent);
        hostComponent = fixture.componentInstance;
        hostEl = fixture.nativeElement;
        fixture.detectChanges();
    });

    it('should create and display initial state', () => {
        expect(hostComponent).toBeTruthy();

        let component = fixture.debugElement.query(By.css('constraints-current')).componentInstance;
        expect(component).toBeTruthy();

        expect(hostEl.querySelector('.c-current-constraints')).toBeTruthy();
        expect(hostEl.querySelector('.heading')).toBeTruthy();
        expect(hostEl.querySelector(".c-constraints")).toBeTruthy();

        //should be empty initially
        expect(hostEl.querySelector('em')).toBeTruthy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(0);

    });

    it('should list current constraints', () => {

        //set a constraint
        hostComponent.constraints.set(new Constraint("keywords", "testing", "Keywords"));
        fixture.detectChanges();    //trigger digest

        let component = fixture.debugElement.query(By.css('constraints-current')).componentInstance;
        expect(component).toBeTruthy();

        expect(hostEl.querySelector('.c-current-constraints')).toBeTruthy();
        expect(hostEl.querySelector('.heading')).toBeTruthy();
        expect(hostEl.querySelector(".c-constraints")).toBeTruthy();

        //should be empty initially
        expect(hostEl.querySelector('em')).toBeFalsy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(1);

        let label = hostEl.querySelector('.c-constraint > .heading > span').textContent;
        expect(label).toEqual("Keywords")

        let value = hostEl.querySelector('.c-constraint > .c-constraint__value .flex-1');
        expect(value.textContent).toEqual('testing');

        //remove only constraint
        let btn : HTMLElement = hostEl.querySelector('.c-constraint > .c-constraint__value > button') as HTMLElement;
        btn.click();
        fixture.detectChanges();
        expect(hostEl.querySelector('em')).toBeTruthy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(0);
    });


    it('should list current constraints with multiple values', () => {

        //set a constraint
        let constraint = new MultiValueConstraint("keywords", ["testing","another","too"], "Keywords");
        hostComponent.constraints.set(constraint);
        fixture.detectChanges();    //trigger digest

        let component = fixture.debugElement.query(By.css('constraints-current')).componentInstance;
        expect(component).toBeTruthy();

        expect(hostEl.querySelector('.c-current-constraints')).toBeTruthy();
        expect(hostEl.querySelector('.heading')).toBeTruthy();
        expect(hostEl.querySelector(".c-constraints")).toBeTruthy();

        //should be empty initially
        expect(hostEl.querySelector('em')).toBeFalsy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(1);

        let conEl = hostEl.querySelector('.c-constraint');
        let label = hostEl.querySelector('.c-constraint > .heading > span').textContent;
        expect(label).toEqual("Keywords")

        let valueEls = hostEl.querySelectorAll('.c-constraint .c-constraint__value');
        expect(valueEls.length).toEqual(3);

        expect(valueEls.item(0).querySelector('.flex-1').textContent).toEqual('testing');
        expect(valueEls.item(1).querySelector('.flex-1').textContent).toEqual('another');
        expect(valueEls.item(2).querySelector('.flex-1').textContent).toEqual('too');

        //remove one of the values
        let btn : HTMLElement = valueEls.item(0).querySelector('button') as HTMLElement;
        btn.click();
        fixture.detectChanges();
        expect(hostEl.querySelector('em')).toBeFalsy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(1);
        expect(hostEl.querySelectorAll('.c-constraint .c-constraint__value').length).toEqual(2);

        //clear out entire constraint, which should empty current too
        btn = hostEl.querySelector('.c-constraint > .heading > button') as HTMLElement;
        btn.click();
        fixture.detectChanges();
        expect(hostEl.querySelector('em')).toBeTruthy();
        expect(hostEl.querySelectorAll('.c-constraint').length).toEqual(0);


    });

});
