import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CcbComponent } from './ccb.component';

describe('CcbComponent', () => {
  let component: CcbComponent;
  let fixture: ComponentFixture<CcbComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CcbComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CcbComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
