import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { InlineSVGModule } from 'ng-inline-svg';

import { Constraints } from '../../models/constraint';
import { PortfolioComponent } from './portfolio.component';
import { FriendlyTypePipe } from '../../shared/pipes';
import { ThumbnailComponent } from '../../shared/thumbnail/thumbnail.component';

@Component({
    selector: `host-component`,
    template: `<results-portfolio [constraints]="constraints"></results-portfolio>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}

describe('PortfolioComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule, InlineSVGModule ],
            declarations: [
                PortfolioComponent, TestHostComponent, FriendlyTypePipe,
                ThumbnailComponent 
            ]
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
