import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-related',
  templateUrl: './related.component.html',
  styleUrls: ['./related.component.less']
})
export class RelatedComponent implements OnInit {

    @Input() related : any[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    getRelated() {
        return this.related ? this.related.filter(r=>!!r) : [];
    }


}
