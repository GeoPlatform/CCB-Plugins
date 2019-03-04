import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-use-constraints',
  templateUrl: './use-constraints.component.html',
  styleUrls: ['./use-constraints.component.less']
})
export class UseConstraintsComponent implements OnInit {

    @Input() rights : any[];
    public isCollapsed : boolean = false;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
