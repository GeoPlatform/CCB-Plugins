import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CoverageMapComponent } from './coverage-map.component';

describe('CoverageMapComponent', () => {
  let component: CoverageMapComponent;
  let fixture: ComponentFixture<CoverageMapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CoverageMapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CoverageMapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
