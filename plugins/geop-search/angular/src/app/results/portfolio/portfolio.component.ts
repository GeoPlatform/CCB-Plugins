import {
    NgZone, Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter, SimpleChanges, SimpleChange
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

    private queryChange: Subject<Query> = new Subject<Query>();

    constructor(
        private _ngZone: NgZone,
        private http : HttpClient
    ) {
        this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));
        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = this.defaultQuery.getSort();
        this.defaultQuery.setFields(
            this.defaultQuery.getFields().concat([ QueryFields.THUMBNAIL ])
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

    onConstraintChange(constraints?: Constraints) {

        this.query = this.defaultQuery.clone();

        //apply constraints to tracked query object
        if(constraints) constraints.apply(this.query);
        else this.constraints.apply(this.query);

        this.queryChange.next(this.query);
    }

    onPageSizeChange() {
        this.query.setPageSize(this.pageSize);
        this.queryChange.next(this.query);
    }

    onSortChange() {
        this.query.sort(this.sortField);
        this.queryChange.next(this.query);
    }

    executeQuery() {
        // console.log("Issuing Portfolio Query");
        // console.log(JSON.stringify(this.query.getQuery()));

        this.service.search(this.query)
        .then( response => {
            //Should not have to wrap with zone, but for some reason, the
            // async call (despite using Angular HttpClient under the hood)
            // is happening outside of zone.
            //see: https://github.com/angular/angular/issues/7381
            this._ngZone.run(() => {
                this.totalResults = response.totalResults;
                this.results = response;
            });
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
        })
    }

    previousPage() {
        let page: number = Math.max(0, this.query.getPage()-1);
        this.query.page(page);
        // this.executeQuery();
        this.queryChange.next(this.query);
    }

    nextPage() {
        let lastPage = Math.min(this.totalResults / this.query.getPageSize());
        let page:number = Math.min(this.query.getPage()+1, lastPage);
        this.query.page(page);
        // this.executeQuery();
        this.queryChange.next(this.query);
    }

    addCreatorConstraint(username) {
        let constraint = new CreatorCodec().toConstraint(username);
        this.constraints.set(constraint);
    }

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
        return `assets/${type}.svg`;
    }

}
