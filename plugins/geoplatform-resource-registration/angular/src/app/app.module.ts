import { BrowserModule } from '@angular/platform-browser';
import {
    NgModule, Pipe, PipeTransform, Injectable, Inject, APP_INITIALIZER
} from '@angular/core';
import {
    HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS
} from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { Config } from 'geoplatform.client';


import {
    MatCardModule, MatInputModule, MatButtonModule,
    MatListModule, MatStepperModule, MatFormFieldModule,
    MatDatepickerModule, MatNativeDateModule, MatSelectModule,
    MatCheckboxModule, MatAutocompleteModule, MatIconModule,
    MatChipsModule, MatProgressSpinnerModule, MatIconRegistry
} from '@angular/material';

import { AuthenticatedComponent } from './authenticated.component';
import { AppComponent } from './app.component';

//configure the necessary environment variables needed by GeoPlatformClient
import { environment } from '../environments/environment';
import { TypeComponent } from './steps/type/type.component';
import { AdditionalComponent } from './steps/additional/additional.component';
import { EnrichComponent } from './steps/enrich/enrich.component';
import { ReviewComponent } from './steps/review/review.component';
import { AutocompleteMatChipComponent } from "./autocomplete.component";
import { TokenInterceptor } from 'ng-gpoauth/angular'
import { PluginAuthService } from "./auth.service";

import {
    itemServiceProvider,
    serviceServiceProvider,
    utilsServiceProvider,
    kgServiceProvider
} from './item-service.provider';

export function initializeApp() {
  return () => {

      // console.log("Initializing App...");
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




@Pipe({
    name: 'prettyJson'
})
@Injectable()
export class PrettyJsonPipe implements PipeTransform {
    transform(value: any) : string {
        if(value === null || value === undefined) return '';
        return JSON.stringify(value, null, '\t');
    }
}





// //ROUTING CONFIG
// const appRoutes: Routes = [
//
//     { path: '',     component: AppComponent },
//     { path: '**',   component: AppComponent }
// ];





@NgModule({
    declarations: [
        AppComponent,
        TypeComponent,
        AdditionalComponent,
        EnrichComponent,
        ReviewComponent,
        PrettyJsonPipe,
        AutocompleteMatChipComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule, HttpClientJsonpModule,
        BrowserAnimationsModule,
        ReactiveFormsModule,
        MatCardModule, MatInputModule, MatButtonModule,
        MatListModule, MatStepperModule, MatFormFieldModule,
        MatDatepickerModule, MatNativeDateModule, MatSelectModule,
        MatCheckboxModule, MatAutocompleteModule, MatIconModule,
        MatChipsModule, MatProgressSpinnerModule
    ],
    providers: [
        {
            provide: APP_INITIALIZER,
            useFactory: initializeApp,
            multi: true
        },
        // { // Setup handler for sending and receiving tokens from backend service
        //     provide: HTTP_INTERCEPTORS,
        //     useClass: TokenInterceptor,
        //     multi: true
        // },
        PluginAuthService,
        itemServiceProvider,
        serviceServiceProvider,
        utilsServiceProvider,
        kgServiceProvider,
        MatIconRegistry
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
