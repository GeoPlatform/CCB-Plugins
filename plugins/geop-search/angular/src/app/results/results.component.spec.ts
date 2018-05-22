import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { InlineSVGModule } from 'ng-inline-svg';

import { Constraints } from '../models/constraint';
import { ResultsComponent } from './results.component';
import { PortfolioComponent } from './portfolio/portfolio.component';
import { CcbComponent } from './ccb/ccb.component';
import { FriendlyTypePipe } from '../shared/pipes';
import { ThumbnailComponent } from '../shared/thumbnail/thumbnail.component';

@Component({
    selector: `host-component`,
    template: `<search-results [constraints]="constraints"></search-results>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}

describe('ResultsComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule, InlineSVGModule ],
            declarations: [
                ResultsComponent, PortfolioComponent, CcbComponent,
                TestHostComponent, FriendlyTypePipe, ThumbnailComponent
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
