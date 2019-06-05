import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter,
    SimpleChanges, SimpleChange
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ISubscription } from "rxjs/Subscription";
import { Subject } from 'rxjs/Subject';
import 'rxjs/add/operator/debounceTime';
import {
    Config,
    Query, QueryParameters, QueryFields,
    ItemService, ItemTypes,
    TrackingService, TrackingEvent
} from 'geoplatform.client';


import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraints, Constraint } from '../../models/constraint';
import { CreatorCodec } from '../../constraints/creator/codec';
import { SimilarityCodec } from '../../constraints/similarity/codec';

import { PagingEvent } from '../../shared/paging/paging.component';
// import { ServerRoutes } from '../../server-routes.enum'
import { environment } from '../../../environments/environment';

import { itemServiceProvider } from '../../shared/service.provider';


@Component({
    selector: 'results-portfolio',
    templateUrl: './portfolio.component.html',
    styleUrls: ['./portfolio.component.css'],
    providers: [itemServiceProvider]
})
export class PortfolioComponent implements OnInit, OnChanges, OnDestroy {

    @Input() constraints: Constraints;
    private listener : ISubscription;
    public totalResults : number = 0;
    public pageSize : number = 10;
    public sortField : string;
    private defaultQuery : Query;
    public query : Query;
    public results : any;
    public error: {label:string, message: string, code?:number} = null;
    public isLoading : boolean = false;
    public showLegend : boolean = false;
    private queryChange: Subject<Query> = new Subject<Query>();

    constructor(
        private itemService : ItemService,
        private trackingSvc : TrackingService
    ) {
        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = '_score,desc';
        this.defaultQuery.sort(this.sortField);
        this.defaultQuery.setFields(
            this.defaultQuery.getFields().concat([
                QueryFields.THUMBNAIL,
                QueryFields.RESOURCE_TYPES,
                QueryFields.LANDING_PAGE,
                '_cloneOf'   //DT-2621
            ])
        );
        this.defaultQuery.setFacets('type');

        //use a subject so we can debounce query execution events
        this.queryChange
            .debounceTime(500)
            .subscribe((query) => this.executeQuery() );
    }

    ngOnInit() {

        /* FOR TESTING PURPOSES ONLY */
        // setTimeout(() => {
        //     this.error = {
        //         label:"An Error Occurred",
        //         message: "This is just a test",
        //         code: 500
        //     };
        // }, 3000);

     }

    ngOnChanges( changes : SimpleChanges) {
        if(changes.constraints) {
            let constraints = changes.constraints.currentValue;
            // console.log("Portfolio Query Updating: " + JSON.stringify(constraints.list()));
            this.onConstraintChange(constraints);

            if(this.listener)
                this.listener.unsubscribe();

            this.listener = constraints.on((constraint:Constraint) => {
                // console.log("PortfolioComponent.ngOnChanges() - Constraints changed: " +
                //     this.constraints.toString());
                this.onConstraintChange();
            });
        }
    }

    ngOnDestroy() {
        if(this.listener)
            this.listener.unsubscribe();
    }

    /**
     *
     */
    onConstraintChange(constraints?: Constraints) {

        this.query = this.defaultQuery.clone();

        //apply constraints to tracked query object
        if(constraints) constraints.apply(this.query);
        else this.constraints.apply(this.query);

        this.queryChange.next(this.query);
    }

    /**
     *
     */
    executeQuery() {

        // console.log("PortfolioComponent.executeQuery() - " +
        //    JSON.stringify(this.query.getQuery(), null, ' '));


        this.isLoading = true;
        this.itemService.search(this.query)
        .then( response => {
            this.isLoading = false;
            this.totalResults = response.totalResults;
            this.results = response;

            //show facet counts in picker filters...
            this.constraints.updateFacetCounts(response.facets);
            this.trackingSvc.logSearch(this.query.query, response.totalResults);
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
            this.isLoading = false;
            this.error = {
                label:"Unable to load results",
                message: e.message,
                code: e.status || 500
            };
        })
    }

    /**
     *
     */
    onSortChange() {
        //update current query
        this.query.sort(this.sortField);
        // and default query so we don't lose the selected sort on a constraint change
        this.defaultQuery.sort(this.sortField);
        //let listeners know the query has changed
        this.queryChange.next(this.query);
    }

    /**
     *
     */
    onPagingEvent($event : PagingEvent) {
        // console.log("Paging Event: " + JSON.stringify($event));
        let changed = false;
        if(!isNaN($event.page)) {
            this.query.setPage($event.page);

            //don't update default query page because we want constraint
            // changes to restart paging at the first page of results
            changed = true;
        }
        if(!isNaN($event.size)) {
            this.query.setPageSize($event.size);

            //update default query because we don't want to lose user-selected
            // page size value when the constraints change
            this.defaultQuery.setPageSize($event.size);
            changed = true;
        }
        if(!changed) return;
        this.queryChange.next(this.query);
    }

    /**
     *
     */
    addCreatorConstraint(username) {
        let constraint = new CreatorCodec().toConstraint(username);
        this.constraints.set(constraint);
    }

    findSimilarTo(item) {
        let http = this.itemService.client;
        let constraint = new SimilarityCodec(http).toConstraint(item);
        this.constraints.set(constraint);
    }

    getIconClass(item) {
        let type = item.type.replace(/^.+\:/i, '').toLowerCase();
        return 'icon-' + type;
    }

    /**
     *
     */
    getActivationUrl(item) {
        let type = null;
        switch(item.type) {
            case ItemTypes.GALLERY: type = "galleries"; break;
            case ItemTypes.COMMUNITY: type = "communities"; break;
            case ItemTypes.CONTACT: type = "contacts"; break;
            case ItemTypes.IMAGE_PRODUCT: type = "products"; break;
            default: type = item.type.replace(/^.+\:/i, '').toLowerCase() + 's'; break;
        }
        if(type) return `${environment.wpUrl}/resources/${type}/${item.id}`;
        else return '/resources';

    }

    getCloneOfActivationUrl(item) {
        let type = item.type;
        let id = item._cloneOf;
        if(!type || !id) return '';
        if(type) return `${environment.wpUrl}/resources/${type}/${id}`;
    }


    getMapViewerURL () {
        return Config.wmvUrl || Config.ualUrl.replace('ual', 'viewer');
    }

    getGalleryURL () {
        return Config.mmUrl || Config.ualUrl.replace('ual', 'maps');
    }

    getDashboardURL() {
        return Config.pdUrl || Config.ualUrl.replace('ual', 'dashboard');
    }

    getObjectEditorURL() {
        return Config.pdUrl || Config.ualUrl.replace('ual', 'oe');
    }

}
