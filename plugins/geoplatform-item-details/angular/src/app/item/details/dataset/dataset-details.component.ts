import { Component, OnInit, Input } from '@angular/core';

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

}
