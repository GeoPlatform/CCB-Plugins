import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER } from '@angular/core';
import { FormsModule } from '@angular/forms';
import {
    HttpClientModule, HttpClientJsonpModule, HTTP_INTERCEPTORS,
    HttpClient, HttpHeaders, HttpParams, HttpResponse
} from '@angular/common/http';
// import { ActivatedRoute, Routes, RouterModule } from '@angular/router';

/*
The following was necessary for some async processing (forgotten which), but
is causing max call stack exceeded errors when running in latest angular (8.1.2)
 */
// import "zone.js/dist/zone-patch-rxjs";

import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { LimitToPipe, FriendlyTypePipe, FixLabelPipe } from './shared/pipes';
import { ChartsModule } from 'ng2-charts';

// Adds window.RPMService to global namespace
import { RPMServiceFactory } from '@geoplatform/rpm/dist/js/geoplatform.rpm.browser.js';
import { RPMService } from '@geoplatform/rpm/src/iRPMService'


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

import { Config, ItemService } from '@geoplatform/client';
import { GeoPlatformClientModule, NG2HttpClient } from '@geoplatform/client/angular';



import { AppComponent } from './app.component';
import { ResourceLinkComponent } from './item/resource-link/resource-link.component';
import { PropertyComponent } from './shared/property/property.component';
import { ItemComponent } from './item/item.component';
import { UsageComponent } from './item/usage/usage.component';
import { ServiceStatsComponent } from './item/service-stats/service-stats.component';
import { KgComponent } from './item/kg/kg.component';
import { ClassifierComponent } from './item/kg/classifier.component';
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
import {
    ServiceLayersComponent, ServiceLayerComponent
} from './item/collections/service-layers/service-layers.component';
import {
    GalleryItemsComponent
} from './item/collections/gallery-items/gallery-items.component';
import { MapLayersComponent } from './item/collections/map-layers/map-layers.component';
import {
    CommunityMembersComponent
} from './item/collections/community-members/community-members.component';
import {
    DatasetDistributionsComponent
} from './item/collections/dataset-distributions/dataset-distributions.component';
import { ServicesComponent } from './item/collections/services/services.component';
import { DatasetsComponent } from './item/collections/datasets/datasets.component';
import { ThemesComponent } from './item/collections/themes/themes.component';
import { TopicsComponent } from './item/collections/topics/topics.component';
import { OperatesOnComponent } from './item/collections/operates-on/operates-on.component';
import { AssetsComponent } from './item/collections/assets/assets.component';

import { AssetDetailsComponent } from './item/details/asset/asset.component'
import { ServiceDetailsComponent } from './item/details/service/service-details.component';
import { LayerDetailsComponent } from './item/details/layer/layer-details.component';
import { DatasetDetailsComponent } from './item/details/dataset/dataset-details.component';
import { ContactDetailsComponent } from './item/details/contact/contact-details.component';
import { RelatedDetailsComponent } from './item/details/related/related.component';
import { ProductComponent } from './item/details/product/product.component';

import { PrimaryActionComponent } from './item/actions/primary/primary.component';
import { ExportActionComponent } from './item/actions/export/export.component';
import { EditActionComponent } from './item/actions/edit/edit.component';
import { DeleteActionComponent } from './item/actions/delete/delete.component';
import { LikeActionComponent } from './item/actions/like/like.component';
import { DownloadActionComponent } from './item/actions/download/download.component';
import { PreviewActionComponent } from './item/actions/preview/preview.component';
import { CloneActionComponent } from './item/actions/clone/clone.component';
import { GalleryActionComponent } from './item/actions/gallery/gallery.component';
import { EmbedActionComponent, EmbedModalContent } from './item/actions/embed/embed.component';
import { ActionMenuComponent } from './item/actions/menu/menu.component';


import { RPMStatsService } from './shared/rpmstats.service';
let RPMStatsServiceFactory = (http: HttpClient) => {
    return new RPMStatsService(environment.rpmUrl, environment.rpmToken, http)
}

import { PluginAuthService } from './shared/auth.service';
import { rpmServiceProvider } from './shared/service.provider';




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
        ClassifierComponent,
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
        ResourceLinkComponent,
        PrimaryActionComponent,
        ExportActionComponent,
        LikeActionComponent,
        EditActionComponent,
        DeleteActionComponent,
        DownloadActionComponent,
        PreviewActionComponent,
        CloneActionComponent,
        DatasetDistributionsComponent,
        UsedByComponent,
        RelatedComponent,
        ServiceDetailsComponent,
        LayerDetailsComponent,
        DatasetDetailsComponent,
        ContactDetailsComponent,
        AssetDetailsComponent,
        RelatedDetailsComponent,
        ServicesComponent,
        DatasetsComponent,
        AltTitlesComponent,
        AltIdsComponent,
        UseConstraintsComponent,
        PropertyComponent,
        ThemesComponent,
        TopicsComponent,
        OperatesOnComponent,
        ProductComponent,
        AssetsComponent,
        GalleryActionComponent,
        ActionMenuComponent,
        EmbedActionComponent, EmbedModalContent
    ],
    imports: [
        // RouterModule.forRoot( appRoutes, { useHash: true } ),
        BrowserModule,
        FormsModule,
        HttpClientModule,
        HttpClientJsonpModule,
        NgbModule,
        ChartsModule,
        GeoPlatformClientModule
    ],
    providers: [
        {
            provide: APP_INITIALIZER,
            useFactory: initializeApp,
            multi: true
        },
        rpmServiceProvider,
        PluginAuthService,
        {
            provide: RPMStatsService,
            useFactory: RPMStatsServiceFactory,
            deps: [HttpClient]
        }
    ],
    entryComponents: [
        //dynamic components go here
        EmbedModalContent
    ],
    bootstrap: [ AppComponent ]
})
export class AppModule { }
