import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OperatesOnComponent } from './operates-on.component';

describe('OperatesOnComponent', () => {
  let component: OperatesOnComponent;
  let fixture: ComponentFixture<OperatesOnComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OperatesOnComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OperatesOnComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
