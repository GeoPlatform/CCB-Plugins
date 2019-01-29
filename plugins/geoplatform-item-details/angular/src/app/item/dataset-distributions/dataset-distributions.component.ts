import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-dataset-distributions',
  templateUrl: './dataset-distributions.component.html',
  styleUrls: ['./dataset-distributions.component.less']
})
export class DatasetDistributionsComponent implements OnInit {

    @Input() item : any;
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
