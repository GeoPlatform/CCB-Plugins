import {
    NgZone,
    Component,
    OnInit, OnChanges, OnDestroy,
    Input, Output,
    EventEmitter,
    SimpleChanges
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';

import { Config, KGService, KGQuery } from 'geoplatform.client';

import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';
import { SemanticCodec } from './codec';


@Component({
  selector: 'app-semantic',
  templateUrl: './semantic.component.html',
  styleUrls: ['./semantic.component.css']
})
export class SemanticComponent implements OnInit, OnChanges, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    private codec : Codec = new SemanticCodec();
    private service : KGService;
    public totalResults : number = 0;
    public listFilter: string = null;
    private listQuery: KGQuery = new KGQuery().pageSize(10);
    private selections : [{uri:string}] = [] as [{uri:string}];
    private resultsSrc = new Subject<any>();
    public resultsObs$ = this.resultsSrc.asObservable();



    constructor(private _ngZone: NgZone, private http: HttpClient) {
        let client = new NG2HttpClient(http);
        this.service = new KGService(Config.ualUrl, client);
    }

    ngOnInit() {
        this.selections = this.codec.getValue(this.constraints);
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.constraints) {
            this.selections = this.codec.getValue(changes.constraints.currentValue);
        }
    }

    ngOnDestroy() {
        this.selections = null;
        this.codec = null;
        this.service = null;
        this.listFilter = null;
        this.resultsObs$ = null;
        this.resultsSrc = null;
    }

    getCodec() : Codec { return this.codec; }

    apply() {
        let constraint = this.codec.toConstraint(this.selections.slice(0));
        if(!this.selections || !this.selections.length) {
            this.constraints.unset(constraint);
        } else {
            this.constraints.set(constraint);
        }
    }




    refreshOptions() {
        // console.log("Issuing Portfolio Query");
        this.listQuery.q(this.listFilter);
        this.service.suggest(this.listQuery)
        .then( response => {
            //Should not have to wrap with zone, but for some reason, the
            // async call (despite using Angular HttpClient under the hood)
            // is happening outside of zone.
            //see: https://github.com/angular/angular/issues/7381
            this._ngZone.run(() => {
                this.totalResults = response.totalResults;
                this.resultsSrc.next(response.results.slice(0));
            });
        })
        .catch( e => {
            console.log("An error occurred: " + e.message);
        })
    }


    getIndex(item) : number {
        if(!this.selections || !this.selections.length) return -1;
        return this.selections.findIndex(t=>t.uri===item.uri);
    }

    isSelected(item) : boolean {
        return this.getIndex(item) >= 0;
    }

    select(item) {
        let index = this.getIndex(item);
        if(index<0) this.selections.push(item);
        else this.selections.splice(index, 1);
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
