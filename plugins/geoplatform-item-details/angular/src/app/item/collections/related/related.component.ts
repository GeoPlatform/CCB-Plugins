import { Component, OnInit, Input } from '@angular/core';
import { Config } from "@geoplatform/client";


@Component({
  selector: 'gpid-related',
  templateUrl: './related.component.html',
  styleUrls: ['./related.component.less']
})
export class RelatedComponent implements OnInit {

    @Input() related   : any[];
    public isCollapsed : boolean = true;
    public apiBase     : string;

    constructor() { }

    ngOnInit() {
        this.apiBase = Config.ualUrl;
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    getRelated() {
        return this.related ? this.related.filter(r=>!!r) : [];
    }


}
