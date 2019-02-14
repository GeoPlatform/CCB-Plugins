import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER } from '@angular/core';
import { HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';
// import { ActivatedRoute, Routes, RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { InlineSVGModule } from 'ng-inline-svg';
import { LimitToPipe, FriendlyTypePipe, FixLabelPipe } from './shared/pipes';

//configure the necessary environment variables needed by GeoPlatformClient
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

import { Config } from 'geoplatform.client';



import { AppComponent } from './app.component';
import { ResourceLinkComponent } from './item/resource-link/resource-link.component';
import { PropertyComponent } from './shared/property/property.component';
import { ItemComponent } from './item/item.component';
import { UsageComponent } from './item/usage/usage.component';
import { ServiceStatsComponent } from './item/service-stats/service-stats.component';
import { KgComponent } from './item/kg/kg.component';
import { AboutComponent } from './item/about/about.component';
import {
    DepictionComponent, ImageFallbackDirective
} from './item/depiction/depiction.component';
import { CoverageMapComponent } from './item/coverage-map/coverage-map.component';
import { KeywordsComponent } from './item/keywords/keywords.component';
import { AltTitlesComponent } from './item/alt-titles/alt-titles.component';
import { AltIdsComponent } from './item/alt-ids/alt-ids.component';
import { UseConstraintsComponent } from './item/use-constraints/use-constraints.component';

import { UsedByComponent } from './item/collections/used-by/used-by.component';
import { RelatedComponent } from './item/collections/related/related.component';
import { ServiceLayersComponent, ServiceLayerComponent } from './item/collections/service-layers/service-layers.component';
import { GalleryItemsComponent } from './item/collections/gallery-items/gallery-items.component';
import { MapLayersComponent } from './item/collections/map-layers/map-layers.component';
import { CommunityMembersComponent } from './item/collections/community-members/community-members.component';
import { DatasetDistributionsComponent } from './item/collections/dataset-distributions/dataset-distributions.component';

import { PrimaryActionComponent } from './item/actions/primary-action/primary-action.component';
import { ExportActionComponent } from './item/actions/export-action/export-action.component';
import { EditActionComponent } from './item/actions/edit-action/edit-action.component';
import { DeleteActionComponent } from './item/actions/delete-action/delete-action.component';
import { LikeActionComponent } from './item/actions/like-action/like-action.component';

import { ServiceDetailsComponent } from './item/details/service/service-details.component';
import { LayerDetailsComponent } from './item/details/layer/layer-details.component';
import { DatasetDetailsComponent } from './item/details/dataset/dataset-details.component';
import { ContactDetailsComponent } from './item/details/contact/contact-details.component';
import { ServicesComponent } from './item/collections/services/services.component';

import { PluginAuthService } from './shared/auth.service';



export function initializeApp() {
    return () => {
      //initial configuration via build-time environment variables
      Config.configure(environment);

      //optionally, if run-time environment variables specified,
      // add those (overwriting any duplicates)
      if((<any>window).GeoPlatformPluginEnv) {
          // console.log("Configuring app using run-time values");
          Config.configure((<any>window).GeoPlatformPluginEnv);
      }
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
        ItemComponent,
        UsageComponent,
        ServiceStatsComponent,
        KgComponent,
        AboutComponent,
        DepictionComponent,
        ImageFallbackDirective,
        LimitToPipe,
        FriendlyTypePipe,
        FixLabelPipe,
        KeywordsComponent,
        ServiceLayersComponent,
        ServiceLayerComponent,
        GalleryItemsComponent,
        MapLayersComponent,
        CoverageMapComponent,
        CommunityMembersComponent,
        PrimaryActionComponent,
        ExportActionComponent,
        ResourceLinkComponent,
        EditActionComponent,
        DeleteActionComponent,
        DatasetDistributionsComponent,
        LikeActionComponent,
        UsedByComponent,
        RelatedComponent,
        ServiceDetailsComponent,
        LayerDetailsComponent,
        DatasetDetailsComponent,
        ContactDetailsComponent,
        ServicesComponent,
        AltTitlesComponent,
        AltIdsComponent,
        UseConstraintsComponent,
        PropertyComponent
    ],
    imports: [
        // RouterModule.forRoot( appRoutes, { useHash: true } ),
        BrowserModule,
        HttpClientModule,
        HttpClientJsonpModule,
        NgbModule.forRoot(),
        InlineSVGModule
    ],
    providers: [
        {
            provide: APP_INITIALIZER,
            useFactory: initializeApp,
            multi: true
        },
        PluginAuthService
    ],
    entryComponents: [
        //dynamic components go here
    ],
    bootstrap: [ AppComponent ]
})
export class AppModule { }
