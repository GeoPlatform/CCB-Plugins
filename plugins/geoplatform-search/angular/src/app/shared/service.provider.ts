import { HttpClient } from '@angular/common/http';
import {
    Config, ItemService, ServiceService, UtilsService, KGService
} from 'geoplatform.client';
import { NG2HttpClient } from './NG2HttpClient';

var client : NG2HttpClient = null;
var itemService : ItemService = null;
var svcService : ServiceService = null;
var utilsService : UtilsService = null;
var kgService : KGService = null;

export function itemServiceFactory( http : HttpClient ) {
    if(itemService) return itemService;
    if(client === null) client = new NG2HttpClient(http);
    // console.log("Creating ItemService using:");
    // console.log(Config);
    itemService = new ItemService(Config.ualUrl, client);
    return itemService;
}
export function svcServiceFactory( http : HttpClient ) {
    if(svcService) return svcService;
    if(client === null) client = new NG2HttpClient(http);
    // console.log("Creating ServiceService using:");
    // console.log(Config);
    svcService = new ServiceService(Config.ualUrl, client);
    return svcService;
}
export function utilsServiceFactory( http : HttpClient ) {
    if(utilsService) return utilsService;
    if(client === null) client = new NG2HttpClient(http);
    // console.log("Creating UtilsService using:");
    // console.log(Config);
    utilsService = new UtilsService(Config.ualUrl, client);
    return utilsService;
}
export function kgServiceFactory( http : HttpClient ) {
    if(kgService) return kgService;
    if(client === null) client = new NG2HttpClient(http);
    // console.log("Creating KGService using:");
    // console.log(Config);
    kgService = new KGService(Config.ualUrl, client);
    return kgService;
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
