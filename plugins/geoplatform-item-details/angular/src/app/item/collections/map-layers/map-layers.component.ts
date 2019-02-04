import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-map-layers',
  templateUrl: './map-layers.component.html',
  styleUrls: ['./map-layers.component.less']
})
export class MapLayersComponent implements OnInit {

    @Input() map : any;
    public isCollapsed : boolean = false;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
