import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-details-related',
  templateUrl: './related.component.html',
  styleUrls: ['./related.component.less']
})
export class RelatedDetailsComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

}
