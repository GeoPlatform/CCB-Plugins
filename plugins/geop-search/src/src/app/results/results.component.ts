import { Component, OnInit, Input } from '@angular/core';
import { Query } from 'geoplatform.client';
import { Constraints } from '../models/constraint';

@Component({
  selector: 'search-results',
  templateUrl: './results.component.html',
  styleUrls: ['./results.component.css']
})
export class ResultsComponent implements OnInit {

    @Input() constraints : Constraints;

    public totalResults : number = 0;
    public pageSize : number = 10;
    public query : Query = new Query().pageSize(this.pageSize);
    public results : any;

    public currentTab : string = "portfolio";

    constructor() { }

    ngOnInit() {
    }

    isActive(id) { return this.currentTab === id; }

    setActive(id) { this.currentTab = id; }
}
