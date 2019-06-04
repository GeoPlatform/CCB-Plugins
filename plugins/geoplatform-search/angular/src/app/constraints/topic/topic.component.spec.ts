import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Constraints } from '../../models/constraint';
import { TopicComponent } from './theme.component';

@Component({
    selector: `host-component`,
    template: `<constraint-themes [constraints]="constraints"></constraint-themes>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}




describe('TopicComponent', () => {
    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [FormsModule, HttpClientModule],
            declarations: [ TopicComponent, TestHostComponent ]
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
