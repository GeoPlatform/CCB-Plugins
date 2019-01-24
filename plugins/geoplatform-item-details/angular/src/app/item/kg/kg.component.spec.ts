import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { KgComponent } from './kg.component';

describe('KgComponent', () => {
  let component: KgComponent;
  let fixture: ComponentFixture<KgComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ KgComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(KgComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
