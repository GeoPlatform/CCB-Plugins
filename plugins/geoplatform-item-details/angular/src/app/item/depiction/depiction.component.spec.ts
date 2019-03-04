import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DepictionComponent } from './depiction.component';

describe('DepictionComponent', () => {
  let component: DepictionComponent;
  let fixture: ComponentFixture<DepictionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DepictionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DepictionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
