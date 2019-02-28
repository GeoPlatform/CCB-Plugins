import { NgZone } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Config, Query, QueryParameters, ItemService, ItemTypes } from 'geoplatform.client';

import { NG2HttpClient } from '../shared/NG2HttpClient';
import { itemServiceFactory } from '../shared/service.provider';

import { Constraint, Constraints } from '../models/constraint';

import { Codec } from '../models/codec';


/*
    <constraint-list
        [constraints]="..."
        [type]="..."
        [codec]="...">
    </constraint-list>

*/


export class ItemListConstraint {

    public totalResults : number = 0;
    public listQuery : Query;
    public listFilter: string = null;
    private selections : [{id:string}] = [] as [{id:string}];
    protected service : ItemService;
    private resultsSrc = new Subject<any>();
    public resultsObs$ = this.resultsSrc.asObservable();
    protected types : [string];
    protected codec : Codec;
    public error : { label: string, message: string, code?:number } = null;

    constructor(
        private _ngZone: NgZone,
        private http : HttpClient
    ) {
        this.service = itemServiceFactory(http);
        // this.service = new ItemService(Config.ualUrl, new NG2HttpClient(http));

    }

    initialize(constraints: Constraints) {

        this.listQuery = new Query().pageSize(10);
        if(this.types)
            this.listQuery.types(this.types);

        this.configureQuery(this.listQuery);

        let selections = this.codec.getValue(constraints) as [string];
        if(selections && selections.length) {
            //resolve each id in selections to get object with label
            this.service.getMultiple(selections).then(resolved => {
                (resolved as [{id:string}]).forEach( (it : {id:string}) => {
                    this.selections.push(it);
                });
            })
        }
        this.refreshOptions();
    }

    destroy() {
        this.selections = null;
        this.codec = null;
        this.service = null;
        this.listFilter = null;
        this.resultsObs$ = null;
        this.resultsSrc = null;
    }

    configureQuery(query : Query) {
        //implement in subclass
    }

    refreshOptions() {

        this.listQuery.q(this.listFilter);

        this.service.search(this.listQuery)
        .then( (response) => {

            //Should not have to wrap with zone, but for some reason, the
            // async call (despite using Angular HttpClient under the hood)
            // is happening outside of zone.
            //see: https://github.com/angular/angular/issues/7381
            this._ngZone.run(() => {
                this.totalResults = response.totalResults;
                if(this.resultsSrc) {
                    this.resultsSrc.next(response.results.slice(0));
                }
            });
        })
        .catch(e => {
            console.log("Error initializing list constraint options: " + e.message);
            this._ngZone.run(() => {
                this.error = {
                    label: 'Error populating list options',
                    message: e.message,
                    code: e.statusCode || 500
                };
            });
        });
    }

    getIndex(item) : number {
        return this.selections.findIndex(t=>t.id===item.id);
    }

    isSelected(item) : boolean {
        return this.getIndex(item) >= 0;
    }

    select(item) {
        let index = this.getIndex(item);
        if(index<0) this.selections.push(item);
        else this.selections.splice(index, 1);
    }

    apply(constraints: Constraints) {
        let constraint = this.codec.toConstraint(this.selections.slice(0));
        constraints.set(constraint);
    }


    previousPage() {
        let page: number = Math.max(0, this.listQuery.getPage()-1);
        this.listQuery.page(page);
        this.refreshOptions();
    }

    nextPage() {
        let lastPage = Math.min(this.totalResults / this.listQuery.getPageSize());
        let page:number = Math.min(this.listQuery.getPage()+1, lastPage);
        this.listQuery.page(page);
        this.refreshOptions();
    }
}
