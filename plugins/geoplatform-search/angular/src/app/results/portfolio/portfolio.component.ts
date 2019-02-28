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
    ItemService, ItemTypes
} from 'geoplatform.client';


import { NG2HttpClient } from '../../shared/NG2HttpClient';
import { Constraints, Constraint } from '../../models/constraint';
import { CreatorCodec } from '../../constraints/creator/codec';
import { SimilarityCodec } from '../../constraints/similarity/codec';

import { PagingEvent } from '../../shared/paging/paging.component';
// import { ServerRoutes } from '../../server-routes.enum'
import { environment } from '../../../environments/environment';
import { RPMService } from 'gp.rpm/src/iRPMService'

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

    constructor( private itemService : ItemService, public rpm: RPMService ) {
        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = '_score,desc';
        this.defaultQuery.sort(this.sortField);
        this.defaultQuery.setFields(
            this.defaultQuery.getFields().concat([
                QueryFields.THUMBNAIL,
                QueryFields.RESOURCE_TYPES,
                QueryFields.LANDING_PAGE
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
        //    JSON.stringify(this.query.getQuery()));



        this.isLoading = true;
        this.itemService.search(this.query)
        .then( response => {
            this.isLoading = false;
            this.totalResults = response.totalResults;
            this.results = response;

            //show facet counts in picker filters...
            this.constraints.updateFacetCounts(response.facets);
            this.rpm.logSearch(this.query.query, response.totalResults);
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
        this.query.sort(this.sortField);
        this.queryChange.next(this.query);
        this.rpm.logEvent('Sort', this.sortField)
    }

    /**
     *
     */
    onPagingEvent($event : PagingEvent) {
        // console.log("Paging Event: " + JSON.stringify($event));
        let changed = false;
        if(!isNaN($event.page)) {
            this.query.setPage($event.page);
            changed = true;
        }
        if(!isNaN($event.size)) {
            this.query.setPageSize($event.size);
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

    /**
     *
     */
    getIconPath(item) {
        let type = "dataset";
        switch(item.type) {
            case ItemTypes.DATASET:         type =  'dataset'; break;
            case ItemTypes.SERVICE:         type =  'service'; break;
            case ItemTypes.LAYER:           type =  'layer'; break;
            case ItemTypes.MAP:             type =  'map'; break;
            case ItemTypes.GALLERY:         type =  'gallery'; break;
            case ItemTypes.ORGANIZATION:    type =  'organization'; break;
            case ItemTypes.CONTACT:         type =  'vcard'; break;
            case ItemTypes.COMMUNITY:       type =  'community'; break;
            case ItemTypes.CONCEPT:         type =  'concept'; break;
            case ItemTypes.CONCEPT_SCHEME:  type =  'conceptscheme'; break;
            default: type = 'post';
        }
        // return `../${ServerRoutes.ASSETS}${type}.svg`;
        return `../${environment.assets}${type}.svg`;
    }

    /**
     *
     */
    getActivationUrl(item) {
        let type = null;
        switch(item.type) {
            case ItemTypes.LAYER:
            case ItemTypes.MAP: type = item.type.toLowerCase() + 's'; break;
            case ItemTypes.GALLERY: type = "galleries"; break;
            case ItemTypes.COMMUNITY: type = "communities"; break;
            case ItemTypes.CONTACT: type = "contacts"; break;
            case ItemTypes.SERVICE:
            case ItemTypes.DATASET:
            case ItemTypes.ORGANIZATION:
            case ItemTypes.CONCEPT:
            case ItemTypes.CONCEPT_SCHEME:
                type = item.type.split(':')[1].toLowerCase() + 's'; break;
        }
        if(type) return `${environment.wpUrl}/resources/${type}/${item.id}`;
        else return '/resources';

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
