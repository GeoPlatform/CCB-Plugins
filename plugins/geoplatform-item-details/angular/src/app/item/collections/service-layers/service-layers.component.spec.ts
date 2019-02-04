import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ServiceLayersComponent } from './service-layers.component';

describe('ServiceLayersComponent', () => {
  let component: ServiceLayersComponent;
  let fixture: ComponentFixture<ServiceLayersComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ServiceLayersComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ServiceLayersComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
