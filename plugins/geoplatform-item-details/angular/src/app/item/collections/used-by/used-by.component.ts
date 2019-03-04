import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-used-by',
  templateUrl: './used-by.component.html',
  styleUrls: ['./used-by.component.less']
})
export class UsedByComponent implements OnInit {

    @Input() communities : any[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
