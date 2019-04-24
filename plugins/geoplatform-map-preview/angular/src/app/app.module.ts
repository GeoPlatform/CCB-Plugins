import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER } from '@angular/core';
import { FormsModule }   from '@angular/forms';
import { HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';


// Adds window.RPMService to global namespace
import { RPMServiceFactory } from 'geoplatform.rpm/dist/js/gp.rpm.browser.js';
import { RPMService } from 'geoplatform.rpm/src/iRPMService'

//configure the necessary environment variables needed by GeoPlatformClient
import { Config } from 'geoplatform.client';
import { environment } from '../environments/environment';


//Leaflet does some magic rewrites to css to reference images,
// so by exposing leaflet images under "assets" in .angular-cli.json
// and declaring the new paths for the images here globally, the
// images can be referenced successfully across the rest of the app
import * as L from 'leaflet';
L.Icon.Default.imagePath = environment.assets;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'marker-icon-2x.png',
  iconUrl: 'marker-icon.png',
  shadowUrl: 'marker-shadow.png',
});


import { LimitToPipe, FriendlyTypePipe, FixLabelPipe } from './shared/pipes';
import { PluginAuthService } from './shared/auth.service';

import {
    itemServiceProvider,
    serviceServiceProvider,
    utilsServiceProvider,
    kgServiceProvider
} from './shared/service.provider';

import { RPMStatsService } from './shared/rpmstats.service';
let RPMStatsServiceFactory = (http: HttpClient) => {
    return new RPMStatsService(environment.rpmUrl, environment.rpmToken, http)
}

export function initializeApp() {
    return () => {
        //initial configuration via build-time environment variables
        Config.configure(environment);
        let gpGlobal = (<any>window).GeoPlatform;
        //optionally, if run-time environment variables specified,
        // add those (overwriting any duplicates)
        if(gpGlobal && gpGlobal.config) {
            // console.log("Configuring app using run-time values");
            // console.log(gpGlobal.config);
            Config.configure(gpGlobal.config);
        }
        // console.log("Configured App using:");
        // console.log(Config);
    }
}


import { AppComponent } from './app.component';
import { MapComponent } from './map/map.component';
import {
    LayersComponent,
    AvailableLayerComponent,
    SelectedLayerComponent
} from './layers/layers.component';
import { DetailsComponent } from './details/details.component';


@NgModule({
  declarations: [
    AppComponent,
    MapComponent,
    LimitToPipe,
    FriendlyTypePipe,
    FixLabelPipe,
    LayersComponent,
    AvailableLayerComponent,
    SelectedLayerComponent,
    DetailsComponent
  ],
  imports: [
      BrowserModule,
      FormsModule,
      HttpClientModule,
      HttpClientJsonpModule
  ],
  providers: [
      {
          provide: APP_INITIALIZER,
          useFactory: initializeApp,
          multi: true
      },
      PluginAuthService,
      {
          provide: RPMStatsService,
          useFactory: RPMStatsServiceFactory,
          deps: [HttpClient]
      },
      {
          provide: RPMService,
          useValue: RPMServiceFactory()
      },
      itemServiceProvider,
      serviceServiceProvider,
      utilsServiceProvider,
      kgServiceProvider
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }