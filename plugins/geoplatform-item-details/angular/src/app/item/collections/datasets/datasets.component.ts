import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';

@Component({
  selector: 'gpid-datasets',
  templateUrl: './datasets.component.html',
  styleUrls: ['./datasets.component.less']
})
export class DatasetsComponent implements OnInit {

    //the input field that gets injected array
    @Input() data : any[];
    //the field we'll display from. see 'filterDatasets' for why
    public datasets : any[] = [];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.data) {
            let data = changes.data.currentValue;
            if(!data) this.datasets = [];
            else {
                this.datasets = this.filterDatasets(data);
            }
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    /**
     * Due to DT-2250 complications (see ticket for details),
     * the list of resources contain more than just datasets, so
     * we need to filter out only datasets.
     * @param {array} data - array of associated resources
     * @return {array} associated dataset resources
     */
    filterDatasets(data) {
        if(!data) return [];
        return data.filter(d=>'dcat:Dataset'===d.type);
    }


}
