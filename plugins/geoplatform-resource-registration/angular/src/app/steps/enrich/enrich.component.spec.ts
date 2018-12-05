import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EnrichComponent } from './enrich.component';

describe('EnrichComponent', () => {
  let component: EnrichComponent;
  let fixture: ComponentFixture<EnrichComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EnrichComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EnrichComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
