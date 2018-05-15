import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CkanComponent } from './ckan.component';

describe('CkanComponent', () => {
  let component: CkanComponent;
  let fixture: ComponentFixture<CkanComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CkanComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CkanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
