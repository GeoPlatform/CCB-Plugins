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
                size: 200,
                fields: 'parentLayer_id'    //<- used to build tree structure
            })
            .then( response => {
                this.layers = response.results;
                // this.buildTree(response.results);
            })
            .catch( e => {
                //TODO display error
            })
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    buildTree(layers) {
        let cache = {};
        let result = [];

        //then attach child to its parent
        layers.forEach( layer => {

            //extract any root-level layers
            if(!layer.parentLayer_id) {
                result.push(layer);
                return;
            }

            //insert child layer into tree under it's parent's id
            cache[layer.parentLayer_id] = cache[layer.parentLayer_id] || [];
            cache[layer.parentLayer_id].push(layer);

        });

        let parentIds = Object.keys(cache);
        while(parentIds.length) {
            parentIds.forEach( parentId => {

                //find the parent and move children tree under it
                // if cannot find parent, it means its an
                // intermediate node in the tree and will have to be
                // found later
                let parents = result.filter(l=>l.id===parentId);
                if(parents && parents.length > 0) {
                    parents[0].subLayers = cache[parentId];
                    delete cache[parentId];
                }
            });
        }

        this.layers = result;

    }

}
