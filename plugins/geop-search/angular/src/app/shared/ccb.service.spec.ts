import { HttpClientModule } from '@angular/common/http';
import { TestBed, inject } from '@angular/core/testing';

import { CCBService } from './ccb.service';

describe('CCBService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
        imports: [HttpClientModule],
        providers: [CCBService]
    });
  });

  it('should be created', inject([CCBService], (service: CCBService) => {
    expect(service).toBeTruthy();
  }));
});
