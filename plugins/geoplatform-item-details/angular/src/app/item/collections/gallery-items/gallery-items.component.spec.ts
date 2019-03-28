import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryItemsComponent } from './gallery-items.component';

describe('GalleryItemsComponent', () => {
  let component: GalleryItemsComponent;
  let fixture: ComponentFixture<GalleryItemsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GalleryItemsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryItemsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
