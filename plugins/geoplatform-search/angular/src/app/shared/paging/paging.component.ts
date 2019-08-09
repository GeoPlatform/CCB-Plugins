import {
    Component, OnInit, OnChanges, SimpleChanges, SimpleChange,
    Input, Output, EventEmitter
} from '@angular/core';

import { Query } from "@geoplatform/client";


export interface PagingEvent {
    size?: number;
    page?: number;
}

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

    constructor() { }

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
    }

    previousPage() {
        let evt : PagingEvent = { page: this.query.getPage()-1 };
        this.onEvent.emit(evt);
    }

    nextPage() {
        let evt : PagingEvent = { page: this.query.getPage()+1 };
        this.onEvent.emit(evt);
    }

    hasNextPage() {
        return this.totalResults > ((this.query.getPage()+1) * this.pageSize);
    }

    goToPage(page) {
        let evt : PagingEvent = { page: page*1 };
        this.onEvent.emit(evt);
    }

}
