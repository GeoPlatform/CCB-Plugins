import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter, SimpleChanges, SimpleChange
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Subject } from 'rxjs/Subject';
import { ISubscription } from "rxjs/Subscription";
import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

import { CCBService } from '../../shared/ccb.service';
import { Constraints, Constraint } from '../../models/constraint';
import { CreatorCodec } from '../../constraints/creator/codec';
import { PagingEvent } from '../../shared/paging/paging.component';
import { ServerRoutes } from '../../server-routes.enum'

@Component({
  selector: 'results-ccb',
  templateUrl: './ccb.component.html',
  styleUrls: ['./ccb.component.css']
})
export class CcbComponent implements OnInit {

    @Input() constraints: Constraints;

    public service : CCBService;
    private listener : ISubscription;
    public totalResults : number = 0;
    private defaultQuery : Query;
    public pageSize : number = 20;
    public sortField : string = 'modified,desc';
    public query : Query;
    public results : any;
    public error: {label:string, message: string, code?:number} = null;
    private queryChange: Subject<Query> = new Subject<Query>();
    public currentTab : string = "pages";

    public mixedResults : any = {};


    constructor( private http : HttpClient ) {
        this.service = new CCBService(http);
        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = this.defaultQuery.getSort();

        //use a subject so we can debounce query execution events
        this.queryChange
            .debounceTime(500)
            .subscribe((query) => this.executeQuery() );
    }

    ngOnInit() {

    }

    ngOnChanges( changes : SimpleChanges) {
        if(changes.constraints) {
            let constraints = changes.constraints.currentValue;
            this.onConstraintChange(constraints);

            if(this.listener)
                this.listener.unsubscribe();

            this.listener = constraints.on((constraint:Constraint) => {
                // console.log("CcbComponent.ngOnChanges() - Constraints changed: " +
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

    executeQuery() {

        this.query.types('pages');
        this.service.search(this.query)
        .then( response => {
            this.mixedResults['pages'] = {
                totalResults : response.totalResults,
                results: response.results
            };
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
        })

        this.query.types('posts');
        this.service.search(this.query)
        .then( response => {
            this.mixedResults['posts'] = {
                totalResults : response.totalResults,
                results: response.results
            };
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
        })

        this.query.types('media');
        this.service.search(this.query)
        .then( response => {
            this.mixedResults['media'] = {
                totalResults : response.totalResults,
                results: response.results
            };
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
        })


        // this.service.search(this.query)
        // .then( response => {
        //     this.totalResults = response.totalResults;
        //     this.results = response;
        // })
        // .catch( e => {
        //     console.log("An error occurred: " + e.message);
        // })
    }

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

    previousPage() {
        this.query.setPage(this.query.getPage()-1);
        this.queryChange.next(this.query);
    }

    nextPage() {
        this.query.setPage(this.query.getPage()+1);
        this.queryChange.next(this.query);
    }

    /**
     *
     */
    onSortChange() {
        this.query.sort(this.sortField);
        this.queryChange.next(this.query);
    }


    constrainToUser (user) {
        let constraint = new CreatorCodec().toConstraint(user);
        this.constraints.set(constraint);
    }

    /**
     *
     */
    getIconPath(item) {
        return `../${ServerRoutes.ASSETS}${item.type}.svg`;
    }

    isActive(id) { return this.currentTab === id; }

    setActive(id) { this.currentTab = id; }
}
