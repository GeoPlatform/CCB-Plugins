import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TypedResultsComponent } from './typed-results.component';

describe('TypedResultsComponent', () => {
  let component: TypedResultsComponent;
  let fixture: ComponentFixture<TypedResultsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TypedResultsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TypedResultsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
