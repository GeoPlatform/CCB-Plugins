import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';
import { ActivatedRoute, Routes, RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg';
// import { ServerRoutes } from './server-routes.enum';

import { LimitToPipe, FriendlyTypePipe, FixLabelPipe } from './shared/pipes';

//configure the necessary environment variables needed by GeoPlatformClient
import { environment } from '../environments/environment';

// Adds window.RPMService to global namespace
import { RPMServiceFactory } from 'gp.rpm/dist/js/gp.rpm.browser.js';
import { RPMService } from 'gp.rpm/src/iRPMService'



//Leaflet does some magic rewrites to css to reference images,
// so by exposing leaflet images under "assets" in .angular-cli.json
// and declaring the new paths for the images here globally, the
// images can be referenced successfully across the rest of the app
import * as L from 'leaflet';
L.Icon.Default.imagePath = environment.assets; //ServerRoutes.ASSETS
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'marker-icon-2x.png',
  iconUrl: 'marker-icon.png',
  shadowUrl: 'marker-shadow.png',
});

import { Config } from 'geoplatform.client';


import { AppComponent } from './app.component';
import { PickerComponent } from './picker/picker.component';
import { EditorComponent, ConstraintDirective } from './picker/editor/editor.component';

import {
    CurrentComponent,
    KeywordsComponent,
    ThemeComponent,
    ContactComponent,
    CreatorComponent,
    PublisherComponent,
    CommunityComponent,
    ExtentComponent,
    SemanticComponent,
    TemporalComponent,
    TypeComponent
} from './constraints';

import {
    ResultsComponent,
    PortfolioComponent,
    CcbComponent,
    CkanComponent
} from './results';

import { CCBService } from './shared/ccb.service';

import { NgbdTypeaheadHttp } from './shared/typeahead';
import {
    ImageFallbackDirective,
    ThumbnailComponent
} from './shared/thumbnail/thumbnail.component';
import { DebugComponent } from './shared/debug/debug.component';
import { PagingComponent } from './shared/paging/paging.component';
import { TypedResultsComponent } from './results/ccb/typed-results/typed-results.component';
import { SimilarityComponent } from './constraints/similarity/similarity.component';
import { LegendComponent } from './results/portfolio/legend/legend.component';


import {
    itemServiceProvider,
    serviceServiceProvider,
    utilsServiceProvider,
    kgServiceProvider
} from './shared/service.provider';




//ROUTING CONFIG
const appRoutes: Routes = [

    { path: '',     component: AppComponent },
    { path: '**',   component: AppComponent }
];


// import { EnvironmentSettings } from './shared/env.service';
// export function initializeApp(env: EnvironmentSettings) {
//   return () => env.load();
// }
export function initializeApp() {
  return () => {
      console.log("Initializing App...");
      //initial configuration via build-time environment variables
      Config.configure(environment);

      let gpGlobal = (<any>window).GeoPlatform;
      //optionally, if run-time environment variables specified,
      // add those (overwriting any duplicates)
      if(gpGlobal && gpGlobal.config) {
          console.log("Configuring app using run-time values");
          console.log(gpGlobal.config);
          Config.configure(gpGlobal.config);
      }

      console.log("Configured App using:");
      console.log(Config);
  }
}

@NgModule({
  declarations: [
    AppComponent,
    PickerComponent,
    ConstraintDirective,
    KeywordsComponent,
    ThemeComponent,
    CurrentComponent,
    ResultsComponent,
    PublisherComponent,
    CommunityComponent,
    CreatorComponent,
    ExtentComponent,
    TemporalComponent,
    CcbComponent,
    PortfolioComponent,
    TypeComponent,
    CkanComponent,
    LimitToPipe,
    FriendlyTypePipe,
    FixLabelPipe,
    ContactComponent,
    SemanticComponent,
    EditorComponent,
    NgbdTypeaheadHttp,
    ImageFallbackDirective,
    ThumbnailComponent,
    DebugComponent,
    PagingComponent,
    TypedResultsComponent,
    CommunityComponent,
    SimilarityComponent,
    LegendComponent
  ],
  imports: [
    RouterModule.forRoot( appRoutes, { useHash: true } ),
    BrowserModule,
    FormsModule,
    HttpClientModule,
    HttpClientJsonpModule,
    NgbModule.forRoot(),
    InlineSVGModule
  ],
  providers: [
      CCBService,
      // EnvironmentSettings,
      // {
      //     provide: APP_INITIALIZER,
      //     useFactory: initializeApp,
      //     deps: [EnvironmentSettings], multi: true
      // }
      {
        provide: RPMService,
        useValue: RPMServiceFactory()
      },
      {
          provide: APP_INITIALIZER,
          useFactory: initializeApp,
          multi: true
      },
      itemServiceProvider,
      serviceServiceProvider,
      utilsServiceProvider,
      kgServiceProvider
  ],
  entryComponents: [
      KeywordsComponent,
      ThemeComponent,
      ContactComponent,
      CreatorComponent,
      PublisherComponent,
      CommunityComponent,
      ExtentComponent,
      SemanticComponent,
      TemporalComponent,
      TypeComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }








// https://tc39.github.io/ecma262/#sec-array.prototype.findIndex
if (!Array.prototype.findIndex) {
  Object.defineProperty(Array.prototype, 'findIndex', {
    value: function(predicate) {
     // 1. Let O be ? ToObject(this value).
      if (this == null) {
        throw new TypeError('"this" is null or not defined');
      }

      var o = Object(this);

      // 2. Let len be ? ToLength(? Get(O, "length")).
      var len = o.length >>> 0;

      // 3. If IsCallable(predicate) is false, throw a TypeError exception.
      if (typeof predicate !== 'function') {
        throw new TypeError('predicate must be a function');
      }

      // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
      var thisArg = arguments[1];

      // 5. Let k be 0.
      var k = 0;

      // 6. Repeat, while k < len
      while (k < len) {
        // a. Let Pk be ! ToString(k).
        // b. Let kValue be ? Get(O, Pk).
        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
        // d. If testResult is true, return k.
        var kValue = o[k];
        if (predicate.call(thisArg, kValue, k, o)) {
          return k;
        }
        // e. Increase k by 1.
        k++;
      }

      // 7. Return -1.
      return -1;
    },
    configurable: true,
    writable: true
  });
}
