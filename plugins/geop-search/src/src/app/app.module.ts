import { BrowserModule } from '@angular/platform-browser';
import { NgModule, Pipe, PipeTransform } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';
import { ActivatedRoute, Routes, RouterModule } from '@angular/router';



/*
 * Raise the value exponentially
 * Takes an exponent argument that defaults to 1.
 * Usage:
 *   value | exponentialStrength:exponent
 * Example:
 *   {{ 2 | exponentialStrength:10 }}
 *   formats to: 1024
*/
@Pipe({name: 'limitTo'})
export class LimitToPipe implements PipeTransform {
    transform(value: any[], num: number): any[] {
        if(value && value.length > num) {
            return value.slice(0, num);
        }
        return value;
    }
}



import { Config } from 'geoplatform.client';
Config.configure({
    ualUrl: 'https://sit-ual.geoplatform.us'
});

//Leaflet does some magic rewrites to css to reference images,
// so by exposing leaflet images under "assets" in .angular-cli.json
// and declaring the new paths for the images here globally, the
// images can be referenced successfully across the rest of the app
import * as L from 'leaflet';
L.Icon.Default.imagePath = 'assets/';
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'marker-icon-2x.png',
  iconUrl: 'marker-icon.png',
  shadowUrl: 'marker-shadow.png',
});



import { AppComponent } from './app.component';
import { PickerComponent } from './picker/picker.component';

import { Constraints } from './models/constraint';

import { KeywordsComponent } from './constraints/keywords/keywords.component';
import { ThemeComponent } from './constraints/theme/theme.component';
import { CurrentComponent } from './constraints/current/current.component';
import { ResultsComponent } from './results/results.component';
import { PublisherComponent } from './constraints/publisher/publisher.component';
import { CreatorComponent } from './constraints/creator/creator.component';
import { ExtentComponent } from './constraints/extent/extent.component';
import { TemporalComponent } from './constraints/temporal/temporal.component';
import { CcbComponent } from './results/ccb/ccb.component';

import { CCBService } from './shared/ccb.service';
import { PortfolioComponent } from './results/portfolio/portfolio.component';
import { TypeComponent } from './constraints/type/type.component';
import { CkanComponent } from './results/ckan/ckan.component';



//ROUTING CONFIG
const appRoutes: Routes = [

    { path: '',         component: AppComponent }
    // ,
    // { path: '**',     component: PageNotFoundComponent }
];






@NgModule({
  declarations: [
    AppComponent,
    PickerComponent,
    KeywordsComponent,
    ThemeComponent,
    CurrentComponent,
    ResultsComponent,
    PublisherComponent,
    CreatorComponent,
    ExtentComponent,
    TemporalComponent,
    CcbComponent,
    PortfolioComponent,
    TypeComponent,
    CkanComponent,
    LimitToPipe
  ],
  imports: [
    RouterModule.forRoot( appRoutes, { enableTracing: false } ),
    BrowserModule,
    FormsModule,
    HttpClientModule
  ],
  providers: [
      CCBService
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