import { TestBed, inject } from '@angular/core/testing';

import { FcmserviceService } from './fcmservice.service';

describe('FcmserviceService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [FcmserviceService]
    });
  });

  it('should be created', inject([FcmserviceService], (service: FcmserviceService) => {
    expect(service).toBeTruthy();
  }));
});
