import { Inject, Component, OnInit, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Config, ItemTypes, ItemService } from "@geoplatform/client";

import { NG2HttpClient } from "../../../shared/http-client";

@Component({
  selector: 'gpid-service-layers',
  templateUrl: './service-layers.component.html',
  styleUrls: ['./service-layers.component.less']
})
export class ServiceLayersComponent implements OnInit {

    @Input() serviceId : string;
    public isCollapsed : boolean = false;
    public layers : any[];
    public layerTotal: number = 0;  //total layers including nested ones
    private itemService : ItemService;

    constructor( @Inject(ItemService) itemService : ItemService ) {
        this.itemService = itemService;
    }

    ngOnInit() {

        if(this.serviceId) {
            this.itemService.search({
                types: ItemTypes.LAYER,
                service: this.serviceId,
                size: 300,
                fields: 'parentLayer_id'    //<- used to build tree structure
            })
            .then( response => {
                // this.layers = response.results;
                this.layerTotal = response.totalResults;
                this.buildTree(response.results);
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
            //loop check... if the number of items in the cache didn't change
            // this iteration, then we have run out of things to do, so
            // just end the loop prematurely
            let oldLength = parentIds.length;
            parentIds = Object.keys(cache);
            if(oldLength === parentIds.length) {
                parentIds = [];
                console.log("WARN : layer tree building aborted early");
            }
        }

        this.layers = result;

    }

}


















@Component({
  selector: 'gpid-service-layer',
  template: `
  <div>
      <gpid-resource-link [item]="layer"></gpid-resource-link>
      <div class="m-list--tree" *ngIf="layer.subLayers">
          <gpid-service-layer *ngFor="let child of layer.subLayers"
            [layer]="child">
          </gpid-service-layer>
      </div>
  </div>
  `,
  styleUrls: ['./service-layer.component.less']
})
export class ServiceLayerComponent implements OnInit {

    @Input() layer : any;

    constructor() {

    }

    ngOnInit() {

    }

}
