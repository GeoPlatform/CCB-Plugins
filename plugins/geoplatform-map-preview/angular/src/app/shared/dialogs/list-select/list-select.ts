import {
    Inject, Component, OnInit, OnChanges, OnDestroy
} from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { PageEvent } from '@angular/material/paginator';
import { Subject } from 'rxjs';
import { debounceTime, distinctUntilChanged } from 'rxjs/operators';
import { Item, ItemService, Query, SearchResults } from "@geoplatform/client";




export interface ListSelectDialogData {
    selected    : Item[];       //array of pre-selected values
    service     : ItemService;  //service to query for new values
    query       : Query;        //query defining how to search for new values
    subHeading ?: string;       //optional data to display under title
}




@Component({
  selector: 'gpmpp-list-select-dialog',
  templateUrl: 'list-select.html',
  styleUrls: ['./list-select.less']
})
export class ListSelectDialog {

    public  suggested : any[];
    public  termQuery : string;
    public  isLoading : boolean;

    //pagination
    public  currentPage    : number = 0;
    public  totalSuggested : number = 0;

    private query     : Query;
    private subject   : Subject<string> = new Subject<string>();


    constructor (
        public dialogRef: MatDialogRef<ListSelectDialog>,
        @Inject(MAT_DIALOG_DATA) public data: ListSelectDialogData
    ) {
        this.query = data.query.clone().page(this.currentPage).pageSize(12);

        this.subject.pipe(
            debounceTime(300),
            distinctUntilChanged()
        )
        .subscribe( term => {
            this.filterValues(term);
        });
    }

    onNoClick () : void {
        this.dialogRef.close();
    }

    onTermChange( term : string, immediate ?: boolean) {
        if(true === immediate) {        //if needing to update without debounce
            this.filterValues(term);
        } else {                        //otherwise, debounce change
            this.subject.next(term);
        }
    }

    /**
     * @param {string} value - user input to filter options with
     * @return {Promise} resolving array of string options
     */
    filterValues ( value: string ) : void {

        this.termQuery = value;

        if(!value) {    //require user to provide input before searching
            this.suggested = [];
            this.totalSuggested = 0;
            return;
        }

        this.query.q(value);

        this.isLoading = true;

        this.data.service.search(this.query)
        .then( ( response : SearchResults ) => {
            let hits = response.results;
            this.suggested = hits;
            this.totalSuggested = response.totalResults;
        })
        .catch(e => {
            //display error message indicating an issue searching...
        }).finally( () => {
            this.isLoading = false;
        });
    }

    addValue ( arg : Item) : void {
        if(this.isSelected(arg)) {  //if already selected, remove it
            this.removeValue(arg);  //...
            return;                 //...
        }
        this.data.selected.push( arg );
    }

    removeValue ( value: Item ) : void {
        let index = -1;
        this.data.selected.forEach( (p,i) => { if(p.id === value.id) { index = i; } });
        if (index >= 0) {
            this.data.selected.splice(index, 1);
        }
    }

    isSelected ( arg : Item ) : boolean {
        return this.data.selected.length > 0 &&
            !!this.data.selected.find( (s) => s.id === arg.id);
    }


    // /**
    //  * @param pageNo - new page number being requested
    //  */
    // onPageChange( pageNo : number ) {
    //     if(this.currentPage !== pageNo-1 ) {
    //         this.query.page( pageNo-1 );
    //         this.filterValues( this.termQuery );
    //     }
    // }

    /**
     * @param pageNo - new page number being requested
     */
    onPagingEvent( event : PageEvent ) {
        let previous = this.query.getPage();
        let current = event.pageIndex;
        if(previous !== current) {
            this.query.page(current);
        }
        this.query.setPageSize( event.pageSize );
        this.filterValues( this.termQuery );
    }


    getSubHeading(item : Item) : string {
        let property = this.data.subHeading;
        let value = item[property];
        return this.getLabelFrom(value);
    }

    getLabelFrom( value : any ) : string {
        if(value === null || typeof(value) === 'undefined') return '';
        if(Array.isArray(value)) {
            return (value as []).map( v => this.getLabelFrom(v) ).join(', ');
        }
        if(typeof(value) === 'object' && (value.label || value.title || value.prefLabel)) {
            return value.label || value.title || value.prefLabel;
        }
        return '';
    }

}
