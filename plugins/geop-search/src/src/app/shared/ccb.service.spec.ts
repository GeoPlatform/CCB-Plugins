import { TestBed, inject } from '@angular/core/testing';

import { CcbService } from './ccb.service';

describe('CcbService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CcbService]
    });
  });

  it('should be created', inject([CcbService], (service: CcbService) => {
    expect(service).toBeTruthy();
  }));
});
