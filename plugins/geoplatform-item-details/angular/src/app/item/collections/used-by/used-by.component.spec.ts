import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UsedByComponent } from './used-by.component';

describe('UsedByComponent', () => {
  let component: UsedByComponent;
  let fixture: ComponentFixture<UsedByComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UsedByComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UsedByComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
