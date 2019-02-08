import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-alt-titles',
  templateUrl: './alt-titles.component.html',
  styleUrls: ['./alt-titles.component.less']
})
export class AltTitlesComponent implements OnInit {

    @Input() titles : string[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
