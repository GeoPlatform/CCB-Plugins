import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExportActionComponent } from './export-action.component';

describe('ExportActionComponent', () => {
  let component: ExportActionComponent;
  let fixture: ComponentFixture<ExportActionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExportActionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExportActionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
