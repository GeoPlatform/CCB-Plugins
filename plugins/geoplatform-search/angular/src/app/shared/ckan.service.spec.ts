import { HttpClientModule } from '@angular/common/http';
import { TestBed, inject } from '@angular/core/testing';

import { CkanService } from './ckan.service';

describe('CkanService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
        imports: [HttpClientModule],
      providers: [CkanService]
    });
  });

  it('should be created', inject([CkanService], (service: CkanService) => {
    expect(service).toBeTruthy();
  }));
});
