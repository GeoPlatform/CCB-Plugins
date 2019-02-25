import { Component, OnInit, Input } from '@angular/core';
import { Config, ItemTypes } from "geoplatform.client";


@Component({
  selector: 'gpid-download-action',
  templateUrl: './download.component.html',
  styleUrls: ['./download.component.less']
})
export class DownloadComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }


    doAction() {
        let url = Config.ualUrl + '/api/items/' + this.item.id + '/source';
        window.open(url, '_blank');
    }
}
