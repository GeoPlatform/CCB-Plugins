import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-asset-details',
  templateUrl: './asset.component.html',
  styleUrls: ['./asset.component.less']
})
export class AssetDetailsComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

}
