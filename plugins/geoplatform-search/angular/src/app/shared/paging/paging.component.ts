import {
    Component, OnInit, OnChanges, SimpleChanges, SimpleChange,
    Input, Output, EventEmitter
} from '@angular/core';

import { Query, TrackingService, TrackingEvent } from "geoplatform.client";


export interface PagingEvent {
    size?: number;
    page?: number;
}

const PAGE : string = "Page";
const RESULTS_PER_PAGE : string = 'ResultsPerPage';
const PREVIOUS : string = 'Previous';
const NEXT : string = 'Next';
const FIRST : string = 'First';



@Component({
    selector: 'gp-pagination',
    templateUrl: './paging.component.html',
    styleUrls: ['./paging.component.css']
})
export class PagingComponent implements OnInit {

    @Input() query : Query;
    @Input() totalResults: number = 0;
    @Output() onEvent : EventEmitter<PagingEvent> = new EventEmitter<PagingEvent>();
    public pageSize: number = 0;

    constructor(
        private trackingSvc : TrackingService
    ) { }

    ngOnInit() {
    }

    ngOnChanges(changes : SimpleChanges) {
        if(changes.query) {
            this.pageSize = changes.query.currentValue.getPageSize();
        }
    }

    onPageSizeChange() {
        let evt : PagingEvent = { size: this.pageSize };
        this.onEvent.emit(evt);
        this.logEvent(PAGE, RESULTS_PER_PAGE, this.pageSize);
    }

    previousPage() {
        let evt : PagingEvent = { page: this.query.getPage()-1 };
        this.onEvent.emit(evt);
        this.logEvent(PAGE, PREVIOUS);
    }

    nextPage() {
        let evt : PagingEvent = { page: this.query.getPage()+1 };
        this.onEvent.emit(evt);
        this.logEvent(PAGE, NEXT);
    }

    hasNextPage() {
        return this.totalResults > ((this.query.getPage()+1) * this.pageSize);
    }

    goToPage(page) {
        let evt : PagingEvent = { page: page*1 };
        this.onEvent.emit(evt);
        if(page === 0)
        this.logEvent(PAGE, FIRST);
    }

    logEvent(category : string, type : string, value ?: any) {
        let event : TrackingEvent = new TrackingEvent(category, type, value);
        this.trackingSvc.logEvent(event);
    }

}
