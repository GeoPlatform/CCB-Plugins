import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-services',
  templateUrl: './services.component.html',
  styleUrls: ['./services.component.less']
})
export class ServicesComponent implements OnInit {

    @Input() item : any;
    public isCollapsed : boolean = false;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }


}
