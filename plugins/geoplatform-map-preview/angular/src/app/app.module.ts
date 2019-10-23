import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER } from '@angular/core';
import { FormsModule }   from '@angular/forms';
import { HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';
import { DragDropModule } from '@angular/cdk/drag-drop';

// Adds window.RPMService to global namespace
import { RPMServiceFactory } from '@geoplatform/rpm/dist/js/geoplatform.rpm.browser.js';
import { RPMService } from '@geoplatform/rpm/src/iRPMService'

//configure the necessary environment variables needed by GeoPlatformClient
import { Config } from '@geoplatform/client';
import { GeoPlatformClientModule, NG2HttpClient } from '@geoplatform/client/angular';
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

// import {
//     itemServiceProvider,
//     serviceServiceProvider,
//     layerServiceProvider,
//     utilsServiceProvider,
//     kgServiceProvider
// } from './shared/service.provider';

import { RPMStatsService } from './shared/rpmstats.service';
let RPMStatsServiceFactory = (http: HttpClient) => {
    return new RPMStatsService(http)
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
    AvailableLayerComponent
} from './layers/layers.component';
import {
    DetailsComponent, ArrayPropertyComponent
} from './details/details.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { ThumbnailComponent, ImageFallbackDirective } from './thumbnail/thumbnail.component';

@NgModule({
  declarations: [
    AppComponent,
    MapComponent,
    LimitToPipe,
    FriendlyTypePipe,
    FixLabelPipe,
    LayersComponent,
    AvailableLayerComponent,
    DetailsComponent,
    ArrayPropertyComponent,
    SidebarComponent,
    ThumbnailComponent,
    ImageFallbackDirective
  ],
  imports: [
      BrowserModule,
      FormsModule,
      HttpClientModule,
      HttpClientJsonpModule,
      DragDropModule,
      GeoPlatformClientModule
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
      }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
