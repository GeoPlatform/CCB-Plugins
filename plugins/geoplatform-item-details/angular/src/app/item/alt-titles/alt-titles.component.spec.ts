import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AltTitlesComponent } from './alt-titles.component';

describe('AltTitlesComponent', () => {
  let component: AltTitlesComponent;
  let fixture: ComponentFixture<AltTitlesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AltTitlesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AltTitlesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
