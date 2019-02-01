import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LikeActionComponent } from './like-action.component';

describe('LikeActionComponent', () => {
  let component: LikeActionComponent;
  let fixture: ComponentFixture<LikeActionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LikeActionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LikeActionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
