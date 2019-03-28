import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-themes',
  templateUrl: './themes.component.html',
  styleUrls: ['./themes.component.less']
})
export class ThemesComponent implements OnInit {

    @Input() themes : any[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
