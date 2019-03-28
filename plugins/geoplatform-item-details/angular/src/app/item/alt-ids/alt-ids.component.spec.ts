import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AltIdsComponent } from './alt-ids.component';

describe('AltIdsComponent', () => {
  let component: AltIdsComponent;
  let fixture: ComponentFixture<AltIdsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AltIdsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AltIdsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
