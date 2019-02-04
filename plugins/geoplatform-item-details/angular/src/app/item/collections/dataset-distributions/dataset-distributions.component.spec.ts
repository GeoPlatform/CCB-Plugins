import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DatasetDistributionsComponent } from './dataset-distributions.component';

describe('DatasetDistributionsComponent', () => {
  let component: DatasetDistributionsComponent;
  let fixture: ComponentFixture<DatasetDistributionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DatasetDistributionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DatasetDistributionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
