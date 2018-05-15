import { TestBed, inject } from '@angular/core/testing';

import { CkanService } from './ckan.service';

describe('CkanService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CkanService]
    });
  });

  it('should be created', inject([CkanService], (service: CkanService) => {
    expect(service).toBeTruthy();
  }));
});
