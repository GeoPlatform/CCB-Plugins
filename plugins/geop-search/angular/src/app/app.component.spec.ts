import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
// import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';
import { RouterTestingModule } from '@angular/router/testing';

import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { InlineSVGModule } from 'ng-inline-svg';

import { AppComponent } from './app.component';
import { PickerComponent } from './picker/picker.component';
import { EditorComponent } from './picker/editor/editor.component';
import { CurrentComponent } from './constraints/current/current.component';
import { ResultsComponent } from './results/results.component';
import { PortfolioComponent } from './results/portfolio/portfolio.component';
import { CcbComponent } from './results/ccb/ccb.component';
import { ThumbnailComponent } from './shared/thumbnail/thumbnail.component';

import { FriendlyTypePipe } from './shared/pipes';


describe('AppComponent', () => {

    let component: AppComponent;
    let fixture: ComponentFixture<AppComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [
                HttpClientModule,
                FormsModule,
                InlineSVGModule,
                RouterTestingModule
            ],
            declarations: [
                AppComponent, PickerComponent, EditorComponent, CurrentComponent,
                ResultsComponent, PortfolioComponent, CcbComponent,
                ThumbnailComponent,
                FriendlyTypePipe
            ],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AppComponent);
        component = fixture.componentInstance;
        hostEl = fixture.debugElement.nativeElement;
    });

    it('should create the app', () => {
        expect(component).toBeTruthy();
    });

    it('should have a layout', () => {
        expect(hostEl.querySelector('.l-body')).toBeDefined();

        // expect(compiled.querySelector('h1').textContent).toContain('Welcome to app!');
    });
});
