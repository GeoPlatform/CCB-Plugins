import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SemanticComponent } from './semantic.component';

describe('SemanticComponent', () => {
  let component: SemanticComponent;
  let fixture: ComponentFixture<SemanticComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SemanticComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SemanticComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
