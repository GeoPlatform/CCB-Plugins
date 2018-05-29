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
import {
    catchError, debounceTime, distinctUntilChanged, map, tap, switchMap, merge
} from 'rxjs/operators';
import { fromPromise } from 'rxjs/observable/fromPromise';

import { Config, KGService, KGQuery } from 'geoplatform.client';

import { NG2HttpClient } from '../../shared/NG2HttpClient';

import { Constraint, Constraints, ConstraintEditor } from '../../models/constraint';
import { Codec } from '../../models/codec';
import { SemanticCodec } from './codec';

import { HttpTypeaheadService } from '../../shared/typeahead';






/**
 * Recommender Service to populate Typeahead control
 *
 */
class RecommenderTypeaheadService implements HttpTypeaheadService {

    private service : KGService;
    private listQuery: KGQuery = new KGQuery().pageSize(10);

    constructor(private http: HttpClient) {
        let client = new NG2HttpClient(http);
        this.service = new KGService(Config.ualUrl, client);
    }

    search(term: string) {
        if (term === '') return Observable.of([]);

        this.listQuery.q(term);
        return fromPromise(this.service.suggest(this.listQuery))
        .pipe(
            map((response:any) => response.results||[]),
            //catch and gracefully handle rejections
            catchError(error => Observable.of([]))
        );
    }
}








@Component({
  selector: 'constraint-semantic',
  templateUrl: './semantic.component.html',
  styleUrls: ['./semantic.component.css']
})
export class SemanticComponent implements OnInit, OnChanges, OnDestroy, ConstraintEditor {

    @Input() constraints : Constraints;
    @Output() onConstraintEvent : EventEmitter<Constraint> = new EventEmitter<Constraint>();

    private codec : Codec = new SemanticCodec();
    // private service : KGService;
    public service : HttpTypeaheadService;
    public totalResults : number = 0;
    public listFilter: string = "";
    private listQuery: KGQuery = new KGQuery().pageSize(10);
    public selections : [{uri:string}] = [] as [{uri:string}];
    private resultsSrc = new Subject<any>();
    public resultsObs$ = this.resultsSrc.asObservable();


    public searching = false;
    public searchFailed = false;
    public hideSearchingWhenUnsubscribed = new Observable(() => () => this.searching = false);



    constructor(private _ngZone: NgZone, private http: HttpClient) {
        this.service = new RecommenderTypeaheadService(http);
    }

    ngOnInit() {
        this.selections = this.codec.getValue(this.constraints)||[];
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.constraints) {
            this.selections = this.codec.getValue(changes.constraints.currentValue)||[];
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

    search (text$: Observable<string>) {
        text$.pipe(
            debounceTime(300),
            distinctUntilChanged(),
            tap(() => this.searching = true),
            switchMap( (term:string) : Observable<any> => {
                return this.service.search(term).pipe(
                    tap(() => this.searchFailed = false),
                    catchError(() => {
                        this.searchFailed = true;
                        return Observable.of([]);
                    }))
            }),
            tap(() => this.searching = false)
            // ,
            // merge(this.hideSearchingWhenUnsubscribed)
        );
    }


    getSuggestionLabel (result) {
        return result.label;
    }

    selectSuggestion (item) {
        this.selections.push(item);
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

}
