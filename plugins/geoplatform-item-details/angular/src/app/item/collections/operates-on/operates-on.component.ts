import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';


@Component({
  selector: 'gpid-operates-on',
  templateUrl: './operates-on.component.html',
  styleUrls: ['./operates-on.component.less']
})
export class OperatesOnComponent implements OnInit {

    //the input field that gets injected array
    @Input() data : any[];
    //the field we'll display from. see 'filterAssets' for why
    public assets : any[] = [];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    ngOnChanges(changes: SimpleChanges) {
        if(changes.data) {
            let data = changes.data.currentValue;
            if(!data) this.assets = [];
            else {
                this.assets = this.filterAssets(data);
            }
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    /**
     * Because all assets associated show up here, including layers,
     * we should separate out layers so that they don't show up twice
     * (once here and again in their own section)
     * @param {array} data - array of associated resources
     * @return {array} associated non-layer assets
     */
    filterAssets(data) {
        if(!data) return [];
        return data.filter(d=>'Layer'!==d.type);
    }

}
