import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-assets',
  templateUrl: './assets.component.html',
  styleUrls: ['./assets.component.less']
})
export class AssetsComponent implements OnInit {

    @Input() assets : any[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }
}
