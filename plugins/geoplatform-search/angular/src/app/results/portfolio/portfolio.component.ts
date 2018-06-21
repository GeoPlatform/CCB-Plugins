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
import { PagingEvent } from '../../shared/paging/paging.component';
import { ServerRoutes } from '../../server-routes.enum'

@Component({
    selector: 'results-portfolio',
    templateUrl: './portfolio.component.html',
    styleUrls: ['./portfolio.component.css']
})
export class PortfolioComponent implements OnInit, OnChanges, OnDestroy {

    @Input() constraints: Constraints;

    private service : ItemService;
    private listener : ISubscription;
    public totalResults : number = 0;
    public pageSize : number = 10;
    public sortField : string;
    private defaultQuery : Query;
    public query : Query;
    public results : any;
    public error: {label:string, message: string, code?:number} = null;
    public isLoading : boolean = false;
    private queryChange: Subject<Query> = new Subject<Query>();

    constructor( private http : HttpClient ) {
        this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = this.defaultQuery.getSort();
        this.defaultQuery.setFields(
            this.defaultQuery.getFields().concat([
                QueryFields.THUMBNAIL,
                QueryFields.RESOURCE_TYPES,
                QueryFields.LANDING_PAGE
            ])
        );

        //use a subject so we can debounce query execution events
        this.queryChange
            .debounceTime(500)
            .subscribe((query) => this.executeQuery() );
    }

    ngOnInit() { }

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
        this.service.search(this.query)
        .then( response => {
            this.isLoading = false;
            this.totalResults = response.totalResults;
            this.results = response;
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
            case ItemTypes.VCARD:           type =  'vcard'; break;
            case ItemTypes.COMMUNITY:       type =  'community'; break;
            case ItemTypes.CONCEPT:         type =  'concept'; break;
            case ItemTypes.CONCEPT_SCHEME:  type =  'conceptscheme'; break;
        }
        return `../${ServerRoutes.ASSETS}${type}.svg`;
    }

    /**
     *
     */
    getActivationUrl(item) {
        let url = null;
        switch(item.type) {

            case ItemTypes.MAP:
                let types = item.resourceTypes;
                let AGOL_MAP_TYPE = 'http://www.geoplatform.gov/ont/openmap/AGOLMap';
                if(types && types.length && ~types.indexOf(AGOL_MAP_TYPE))
                    url = item.landingPage;
                else
                    url = this.getMapViewerURL() + '?id=' + item.id;
                break;

            case ItemTypes.GALLERY:
                url = this.getGalleryURL() + '/galleries/' + item.id;
                break;

            case ItemTypes.SERVICE:
                url = this.getDashboardURL() + '/sd/details/' + item.id;
                break;

            case ItemTypes.DATASET:
                url = this.getDashboardURL() + '/dd/details/' + item.id;
                break;

            case ItemTypes.LAYER:
            case ItemTypes.ORGANIZATION:
            case ItemTypes.VCARD:
            case ItemTypes.COMMUNITY:
            case ItemTypes.CONCEPT:
            case ItemTypes.CONCEPT_SCHEME:
            default:
                return this.getObjectEditorURL() + '/view/' + item.id;
        }
        return url;
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
