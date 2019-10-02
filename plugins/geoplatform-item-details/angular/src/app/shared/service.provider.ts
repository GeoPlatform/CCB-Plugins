import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {
    Config, ItemService, ServiceService, UtilsService, KGService
} from '@geoplatform/client';
import { NG2HttpClient } from '@geoplatform/client/angular';
import { RPMService } from '@geoplatform/rpm/src/iRPMService';
import { RPMServiceFactory } from '@geoplatform/rpm/dist/js/geoplatform.rpm.browser.js';
import { environment } from '../../environments/environment';


//singleton instances
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
