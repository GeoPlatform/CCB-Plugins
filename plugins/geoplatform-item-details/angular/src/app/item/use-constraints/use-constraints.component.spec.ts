import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UseConstraintsComponent } from './use-constraints.component';

describe('UseConstraintsComponent', () => {
  let component: UseConstraintsComponent;
  let fixture: ComponentFixture<UseConstraintsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UseConstraintsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UseConstraintsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
