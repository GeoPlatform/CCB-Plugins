import { Component, OnInit, OnDestroy, Input } from '@angular/core';

import { DataProvider } from '../shared/data.provider';

const TABS = {
    DETAILS : "details",
    LAYERS : "layers"
};



@Component({
  selector: 'gpmp-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.less']
})
export class SidebarComponent implements OnInit {

    @Input() data : DataProvider;
    public activeTab : string = TABS.DETAILS;
    public Tabs = TABS;

    constructor() { }

    ngOnInit() {
    }

}
