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
import { PagingEvent } from '../../shared/paging/paging.component';

@Component({
  selector: 'results-ccb',
  templateUrl: './ccb.component.html',
  styleUrls: ['./ccb.component.css']
})
export class CcbComponent implements OnInit {

    @Input() constraints: Constraints;

    private service : CCBService;
    private listener : ISubscription;
    public totalResults : number = 0;
    public pageSize : number = 10;
    public query : Query;
    public sortField : string;
    private defaultQuery : Query;
    public results : any;
    public error: {label:string, message: string, code?:number} = null;
    private queryChange: Subject<Query> = new Subject<Query>();

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

        //TODO remove
        this.results = {
            results: [
                {
                    type: "Post",
                    label: "One", id: '1', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "Post",
                    label: "Two", id: '2', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "Page",
                    label: "Three", id: '3', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "Page",
                    label: "Four", id: '4', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "File",
                    label: "Five", id: '5', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "File",
                    label: "Six", id: '6', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                },
                {
                    type: "User",
                    label: "Seven", id: '7', description: "This is a temporary result",
                    created: new Date().getTime(), modified: new Date().getTime()
                }
            ]
        };
        this.totalResults = 7;

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
        // this.service.search(this.query)
        // .then( response => {
        //         this.totalResults = response.totalResults;
        //         this.results = response;
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

    /**
     *
     */
    onSortChange() {
        this.query.sort(this.sortField);
        this.queryChange.next(this.query);
    }
}
