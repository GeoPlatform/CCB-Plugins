import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from '@geoplatform/client';

@Component({
  selector: 'gpid-details-related',
  templateUrl: './related.component.html',
  styleUrls: ['./related.component.less']
})
export class RelatedDetailsComponent implements OnInit {

    @Input() item : any;
    public TYPES : any = ItemTypes;

    constructor() { }

    ngOnInit() {
    }

}
