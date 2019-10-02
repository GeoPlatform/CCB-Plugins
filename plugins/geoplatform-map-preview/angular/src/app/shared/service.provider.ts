import { HttpClient } from '@angular/common/http';
import { RPMService } from '@geoplatform/rpm/src/iRPMService';
import { RPMServiceFactory } from '@geoplatform/rpm/dist/js/geoplatform.rpm.browser.js';
import { environment } from '../../environments/environment';

var _rpmService : RPMService = null;

export function rpmServiceFactory() {
    if(_rpmService) return _rpmService;
    //rpm lib expects protocol-less urls
    let href = environment.rpmUrl.replace('https://','');
    _rpmService = RPMServiceFactory(href, environment.rpmToken);
    return _rpmService;
}

export let rpmServiceProvider = {
    provide: RPMService,
    useFactory: rpmServiceFactory
}
