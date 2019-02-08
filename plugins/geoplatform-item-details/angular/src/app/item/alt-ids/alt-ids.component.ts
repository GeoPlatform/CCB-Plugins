import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-alt-ids',
  templateUrl: './alt-ids.component.html',
  styleUrls: ['./alt-ids.component.less']
})
export class AltIdsComponent implements OnInit {

    @Input() ids : string[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
