import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Constraints } from '../../models/constraint';
import { CurrentComponent } from './current.component';

// import { KeywordsComponent } from '../keywords/keywords.component';


@Component({
    selector: `host-component`,
    template: `<constraints-current [constraints]="constraints"></constraints-current>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}





describe('CurrentComponent', () => {

    let component: TestHostComponent;
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
        component = fixture.componentInstance;
        hostEl = fixture.nativeElement;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
