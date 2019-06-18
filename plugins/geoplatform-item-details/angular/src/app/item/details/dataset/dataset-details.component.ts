import { Component, OnInit, Input } from '@angular/core';

const EO_RT = 'http://www.geoplatform.gov/ont/dataset-types/EOImagery';

@Component({
  selector: 'gpid-dataset-details',
  templateUrl: './dataset-details.component.html',
  styleUrls: ['./dataset-details.component.less']
})
export class DatasetDetailsComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

    isEO() : boolean {
        return this.item && this.item.resourceTypes &&
            this.item.resourceTypes.length &&
            this.item.resourceTypes.indexOf(EO_RT) >= 0;
    }

}
