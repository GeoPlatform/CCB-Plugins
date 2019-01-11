import { BrowserModule } from '@angular/platform-browser';
import {
    NgModule, Pipe, PipeTransform, Injectable, APP_INITIALIZER, InjectionToken
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
    MatChipsModule, MatProgressSpinnerModule
} from '@angular/material';

import { AppComponent } from './app.component';

//configure the necessary environment variables needed by GeoPlatformClient
import { environment, authConfig } from '../environments/environment';
import { TypeComponent } from './steps/type/type.component';
import { AdditionalComponent } from './steps/additional/additional.component';
import { EnrichComponent } from './steps/enrich/enrich.component';
import { ReviewComponent } from './steps/review/review.component';
export function initializeApp() {
  return () => {
      Config.configure(environment);
  }
}


// import { AuthService } from "ng-gpoauth/Angular";
import { ngGpoauthFactory, AuthService } from 'ng-gpoauth/Angular';
const authService = ngGpoauthFactory(authConfig);

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
    PrettyJsonPipe
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
      {
        provide: AuthService,
        useValue: authService
      }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
