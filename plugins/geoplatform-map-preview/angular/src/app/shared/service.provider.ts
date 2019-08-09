import { HttpClient } from '@angular/common/http';
import { RPMService } from '@geoplatform/rpm/src/iRPMService'
import { environment } from '../../environments/environment';

var _rpmService : RPMService = null;

export function rpmServiceFactory() {
    if(_rpmService) return _rpmService;
    _rpmService = new RPMService(environment.rpmUrl, environment.rpmToken);
    return _rpmService;
}

export let rpmServiceProvider = {
    provide: RPMService,
    useFactory: rpmServiceFactory
}
