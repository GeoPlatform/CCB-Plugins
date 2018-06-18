
import { Component, Injectable, Input, Output, EventEmitter } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import {
    catchError, debounceTime, distinctUntilChanged, map, tap, switchMap, merge
} from 'rxjs/operators';



/**
 * HttpTypeaheadService
 *
 * Service interface required to be implemented and provided to
 * an NgbdTypeaheadHttp instance in order to fetch asynchronous
 * results via an HTTP request
 */
export interface HttpTypeaheadService {
    search(text : string) : Observable<any>;
}



@Component({
  selector: 'ngbd-typeahead-http',
  template: `
      <div class="form-group">
        <input id="typeahead-http" type="text" class="form-control"
            *ngIf="!template"
            [class.is-invalid]="searchFailed"
            [(ngModel)]="model"
            [ngbTypeahead]="search"
            [resultFormatter]="formatFn"
            (selectItem)="onSelection($event)"
            placeholder="{{placeholder}}" />
        <input id="typeahead-http" type="text" class="form-control"
            *ngIf="template"
            [class.is-invalid]="searchFailed"
            [(ngModel)]="model"
            [ngbTypeahead]="search"
            [resultFormatter]="formatFn"
            [resultTemplate]="template"
            (selectItem)="onSelection($event)"
            placeholder="{{placeholder}}" />
        <span *ngIf="searching">searching...</span>
        <div class="invalid-feedback" *ngIf="searchFailed">Sorry, suggestions could not be loaded.</div>
      </div>
  `
})
export class NgbdTypeaheadHttp {

    @Input() service : HttpTypeaheadService;
    @Input() formatter : Function;
    @Input() placeholder : string = "Begin typing to see results...";
    @Input() template : string;
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
