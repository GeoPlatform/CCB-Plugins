import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { APP_BASE_HREF } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { async, inject, fakeAsync, tick, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { InlineSVGModule } from 'ng-inline-svg';
import { Config, QueryParameters } from 'geoplatform.client';

import { Constraint, Constraints } from '../../models/constraint';
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

    //setup testing configuration before running the tests
    beforeAll( () => {
        Config.configure({
            env: 'test',
            ualUrl: 'https://sit-ual.geoplatform.us'
        });
    });


    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [
                FormsModule,
                HttpClientModule,
                HttpClientTestingModule,
                InlineSVGModule
            ],
            declarations: [
                PortfolioComponent, TestHostComponent, FriendlyTypePipe,
                ThumbnailComponent
            ],
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
        expect(hostEl.querySelector('.c-message--error')).toBeFalsy();
        expect(hostEl.querySelector('.c-pagination')).toBeTruthy();
        expect(hostEl.querySelector('.c-results')).toBeTruthy();
        expect(hostEl.querySelectorAll('.c-results-item').length).toEqual(0);
    });


    it('should show search results', () => {

        let pcomp = fixture.debugElement.query(By.css('results-portfolio')).componentInstance;
        expect(pcomp).toBeTruthy();

        pcomp.results = {
            results: [
                { label: "One", type: "dcat:Dataset", uri: "http://www.geoplatform.gov/dataset/one" },
                { label: "Two", type: "dcat:Dataset", uri: "http://www.geoplatform.gov/dataset/two" },
                { label: "Three", type: "dcat:Dataset", uri: "http://www.geoplatform.gov/dataset/three" }
            ]
        };
        pcomp.totalResults = 3;

        fixture.detectChanges();

        //TODO how to do this???

        expect(hostEl.querySelector('.c-message--error')).toBeFalsy();
        expect(hostEl.querySelector('.c-pagination')).toBeTruthy();
        expect(hostEl.querySelector('.c-results')).toBeTruthy();
        expect(hostEl.querySelectorAll('.c-results-item').length).toEqual(3);

    });


    it('should show errors', () => {

        let pcomp = fixture.debugElement.query(By.css('results-portfolio')).componentInstance;
        expect(pcomp).toBeTruthy();

        pcomp.error = {
            error: "An Error",
            message: "This is an error"
        };

        fixture.detectChanges();

        expect(hostEl.querySelector('.c-message--error')).toBeTruthy();
        // expect(hostEl.querySelector('.c-pagination')).toBeTruthy();
        // expect(hostEl.querySelector('.c-results')).toBeTruthy();
        // expect(hostEl.querySelectorAll('.c-results-item').length).toEqual(3);

    });

});
