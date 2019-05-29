import { HttpClient } from '@angular/common/http';
import {
    Config, ItemService, ServiceService, UtilsService, KGService
} from 'geoplatform.client';

import { NG2HttpClient } from './http-client';


//singleton instances
var _client : NG2HttpClient = null;
var _itemService : ItemService = null;
var _svcService : ServiceService = null;
var _utilsService : UtilsService = null;
var _kgService : KGService = null;



export function itemServiceFactory( http : HttpClient ) {
    if(_itemService) return _itemService;
    if(_client === null) _client = new NG2HttpClient(http);
    // console.log("Creating ItemService using:");
    // console.log(Config);
    _itemService = new ItemService(Config.ualUrl, _client);
    return _itemService;
}
export function svcServiceFactory( http : HttpClient ) {
    if(_svcService) return _svcService;
    if(_client === null) _client = new NG2HttpClient(http);
    // console.log("Creating ServiceService using:");
    // console.log(Config);
    _svcService = new ServiceService(Config.ualUrl, _client);
    return _svcService;
}
export function utilsServiceFactory( http : HttpClient ) {
    if(_utilsService) return _utilsService;
    if(_client === null) _client = new NG2HttpClient(http);
    // console.log("Creating UtilsService using:");
    // console.log(Config);
    _utilsService = new UtilsService(Config.ualUrl, _client);
    return _utilsService;
}
export function kgServiceFactory( http : HttpClient ) {
    if(_kgService) return _kgService;
    if(_client === null) _client = new NG2HttpClient(http);
    // console.log("Creating KGService using:");
    // console.log(Config);
    _kgService = new KGService(Config.ualUrl, _client);
    return _kgService;
}


export let itemServiceProvider = {
    provide: ItemService,
    useFactory: itemServiceFactory,
    deps: [ HttpClient ]
}

export let serviceServiceProvider = {
    provide: ServiceService,
    useFactory: svcServiceFactory,
    deps: [ HttpClient ]
}

export let utilsServiceProvider = {
    provide: UtilsService,
    useFactory: utilsServiceFactory,
    deps: [ HttpClient ]
}

export let kgServiceProvider = {
    provide: KGService,
    useFactory: kgServiceFactory,
    deps: [ HttpClient ]
}
