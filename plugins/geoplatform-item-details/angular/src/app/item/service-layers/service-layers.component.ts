import { Component, OnInit, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Config, ItemTypes, ItemService } from "geoplatform.client";

import { NG2HttpClient } from "../../shared/http-client";


@Component({
  selector: 'gpid-service-layers',
  templateUrl: './service-layers.component.html',
  styleUrls: ['./service-layers.component.less']
})
export class ServiceLayersComponent implements OnInit {

    @Input() serviceId : string;
    public isCollapsed : boolean = false;
    public layers : any[];
    private itemService : ItemService;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {

        if(this.serviceId) {
            this.itemService.search({
                types: ItemTypes.LAYER,
                service: this.serviceId,
                size: 200
            })
            .then( response => {
                this.layers = response.results;
            })
            .catch( e => {
                //TODO display error
            })
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
