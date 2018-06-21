import {
    Component, OnInit, Input, OnChanges, OnDestroy, SimpleChanges, SimpleChange
} from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { ISubscription } from "rxjs/Subscription";

import { Config, Query, QueryParameters, ItemTypes } from 'geoplatform.client';

import { CCBService } from '../../../shared/ccb.service';
import { CreatorCodec } from '../../../constraints/creator/codec';
import { Constraints, Constraint } from '../../../models/constraint';
import { PagingEvent } from '../../../shared/paging/paging.component';

@Component({
  selector: 'ccb-typed-results',
  templateUrl: './typed-results.component.html',
  styleUrls: ['./typed-results.component.css']
})
export class TypedResultsComponent implements OnInit {

    @Input() type : string;
    @Input() constraints: Constraints;
    @Input() service : CCBService;

    private listener : ISubscription;
    public pageSize : number = 20;
    public sortField : string = 'modified,desc';
    public query : Query;
    private defaultQuery : Query;
    private queryChange: Subject<Query> = new Subject<Query>();
    public results : any[];
    public totalResults : number = 0;
    public error: {label:string, message: string, code?:number} = null;

    constructor() {

        this.defaultQuery = new Query().pageSize(this.pageSize);
        this.sortField = this.defaultQuery.getSort();

        //use a subject so we can debounce query execution events
        this.queryChange
            .debounceTime(500)
            .subscribe((query) => this.executeQuery() );
    }

    ngOnInit() {
        this.queryChange.next(this.query);
    }

    ngOnChanges( changes : SimpleChanges) {
        if(changes.type) {
            if(this.query) {
                this.query.types(changes.type.currentValue);
                this.queryChange.next(this.query);
            }
        }
        if(changes.constraints) {
            let constraints = changes.constraints.currentValue;
            this.onConstraintChange(constraints);

            if(this.listener)
                this.listener.unsubscribe();

            this.listener = constraints.on((constraint:Constraint) => {
                this.onConstraintChange();
            });
        }
    }

    onConstraintChange(constraints?: Constraints) {
        this.query = this.defaultQuery.clone();
        //apply constraints to tracked query object
        if(constraints) constraints.apply(this.query);
        else this.constraints.apply(this.query);
        this.query.types(this.type || []);
        this.queryChange.next(this.query);
    }

    executeQuery() {

        if(!this.service) return;

        this.service.search(this.query)
        .then( response => {
            console.log(`${this.type} got back ${response.totalResults} hits`);
            this.totalResults = response.totalResults;
            this.results = response.results;
        })
        .catch(e => {
            console.log("Error searching CCB for " + this.type);
        })
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
        return `wp-content/plugins/geoplatform-search/assets/${item.type}.svg`;
    }

}
