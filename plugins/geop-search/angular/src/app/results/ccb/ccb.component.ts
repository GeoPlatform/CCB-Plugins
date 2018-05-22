import {
    NgZone, Component, OnInit, OnChanges, OnDestroy,
    Input, Output, EventEmitter, SimpleChanges, SimpleChange
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ISubscription } from "rxjs/Subscription";
import { Config, Query, QueryParameters, ItemService, ItemTypes } from 'geoplatform.client';

import { CCBService } from '../../shared/ccb.service';
import { Constraints, Constraint } from '../../models/constraint';

@Component({
  selector: 'results-ccb',
  templateUrl: './ccb.component.html',
  styleUrls: ['./ccb.component.css']
})
export class CcbComponent implements OnInit {

    @Input() constraints: Constraints;

    private service : ItemService;
    private listener : ISubscription;
    public totalResults : number = 0;
    public pageSize : number = 10;
    public query : Query;
    public results : any;

    constructor(
        private _ngZone: NgZone,
        private http : HttpClient
    ) {
        this.service = new CCBService(http);
        this.query = new Query().pageSize(this.pageSize);
    }

    ngOnInit() { }

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
        this.query = new Query().pageSize(this.pageSize);
        //apply constraints to tracked query object
        if(constraints) constraints.apply(this.query);
        else            this.constraints.apply(this.query);
        this.executeQuery();                //then search
    }

    onPageSizeChange() {
        this.query.setPageSize(this.pageSize);
        this.executeQuery();
    }

    executeQuery() {
        // this.service.search(this.query)
        // .then( response => {
        //     //Should not have to wrap with zone, but for some reason, the
        //     // async call (despite using Angular HttpClient under the hood)
        //     // is happening outside of zone.
        //     //see: https://github.com/angular/angular/issues/7381
        //     this._ngZone.run(() => {
        //         this.totalResults = response.totalResults;
        //         this.results = response;
        //     });
        // })
        // .catch( e => {
        //     console.log("An error occurred: " + e.message);
        // })
    }

    previousPage() {
        let page: number = Math.max(0, this.query.getPage()-1);
        this.query.page(page);
        this.executeQuery();
    }

    nextPage() {
        let lastPage = Math.min(this.totalResults / this.query.getPageSize());
        let page:number = Math.min(this.query.getPage()+1, lastPage);
        this.query.page(page);
        this.executeQuery();
    }

}
