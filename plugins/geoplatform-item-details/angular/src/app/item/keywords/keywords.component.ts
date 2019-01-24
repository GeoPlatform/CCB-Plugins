import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-keywords',
  templateUrl: './keywords.component.html',
  styleUrls: ['./keywords.component.less']
})
export class KeywordsComponent implements OnInit {

    @Input() keywords : string[];
    public isCollapsed : boolean = false;

    constructor() { }

    ngOnInit() {
    }

    toggleDisplay() {
        this.isCollapsed = !this.isCollapsed;
    }

}
