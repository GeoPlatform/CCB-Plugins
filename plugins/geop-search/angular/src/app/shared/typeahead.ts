
import { Component, Injectable, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import {
    catchError, debounceTime, distinctUntilChanged, map, tap, switchMap, merge
} from 'rxjs/operators';



// const WIKI_URL = 'https://en.wikipedia.org/w/api.php';
// const PARAMS = new HttpParams({
//   fromObject: {
//     action: 'opensearch',
//     format: 'json',
//     origin: '*'
//   }
// });
//
// @Injectable()
// export class WikipediaService {
//   constructor(private http: HttpClient) {}
//
//   search(term: string) {
//     if (term === '') {
//       return of([]);
//     }
//
//     return this.http
//       .get(WIKI_URL, {params: PARAMS.set('search', term)}).pipe(
//         map(response => response[1])
//       );
//   }
// }




export interface HttpTypeaheadService {
    search(text : string) : Observable<any>;
}



@Component({
  selector: 'ngbd-typeahead-http',
  template: `
      <div class="form-group">
        <input id="typeahead-http" type="text" class="form-control"
            [class.is-invalid]="searchFailed"
            [(ngModel)]="model"
            [ngbTypeahead]="search"
            [resultFormatter]="formatFn"
            (selectItem)="onSelection($event)"
            placeholder="Find..." />
        <span *ngIf="searching">searching...</span>
        <div class="invalid-feedback" *ngIf="searchFailed">Sorry, suggestions could not be loaded.</div>
      </div>
  `
})
export class NgbdTypeaheadHttp {

    @Input() service : HttpTypeaheadService;
    @Input() formatter : Function;
    @Output() resultSelected : EventEmitter<any> = new EventEmitter<any>();

    model: any;
    searching = false;
    searchFailed = false;
    hideSearchingWhenUnsubscribed = new Observable(() => () => this.searching = false);

    constructor() {}

    formatFn = (result: any) => {
        return this.formatter ? this.formatter(result) : result;
    }

    search = (text$: Observable<string>) =>
        text$.pipe(
            debounceTime(300),
            distinctUntilChanged(),
            tap(() => this.searching = true),
            switchMap(term =>
                this.service.search(term).pipe(
                    tap(() => this.searchFailed = false),
                    catchError(() => {
                        this.searchFailed = true;
                        return Observable.of([]);
                    })
                )
            ),
            tap(() => this.searching = false),
            merge(this.hideSearchingWhenUnsubscribed)
        );

    onSelection = ($event) => {
        $event.preventDefault();    //don't set model to selected item
        if(this.resultSelected)
            this.resultSelected.emit($event.item);
            this.model = null;
    }
}
