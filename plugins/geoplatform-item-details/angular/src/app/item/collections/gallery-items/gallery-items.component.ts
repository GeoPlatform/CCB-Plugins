import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-gallery-items',
  templateUrl: './gallery-items.component.html',
  styleUrls: ['./gallery-items.component.less']
})
export class GalleryItemsComponent implements OnInit {

    @Input() items : any[];
    public isCollapsed : boolean = false;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
