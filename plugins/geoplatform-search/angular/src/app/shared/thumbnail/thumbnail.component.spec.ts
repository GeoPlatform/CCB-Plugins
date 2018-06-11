import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Constraints } from '../../models/constraint';
import { ThumbnailComponent } from './thumbnail.component';


@Component({
    selector: `host-component`,
    template: `<thumbnail [source]="'https://www.geoplatform.gov/wp-content/themes/geoplatform-portal/img/fgdc_logo_dkbg.png'"></thumbnail>`
})
class TestHostComponent {
    constraints: Constraints = new Constraints();
}

describe('ThumbnailComponent', () => {

    let component: TestHostComponent;
    let fixture: ComponentFixture<TestHostComponent>;
    let hostEl : HTMLElement;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ FormsModule, HttpClientModule ],
            declarations: [ ThumbnailComponent, TestHostComponent ]
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
