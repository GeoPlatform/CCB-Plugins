import {
    Inject, Component, OnInit, OnDestroy, OnChanges,
    Input, Output, EventEmitter, SimpleChanges, HostBinding
} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Subscription } from "rxjs";
import { Query, LayerService } from "@geoplatform/client";

import { DataProvider, DataEvent, Events } from '../shared/data.provider';
import { logger } from "../shared/logger";



const SECTIONS = {
    MAP : "map",
    AVAILABLE : "available",
    BASE: "base"
};

const BASE_LAYER_RT = 'http://www.geoplatform.gov/ont/openlayer/BaseLayer';



@Component({
  selector: 'gpmp-layer-list',
  templateUrl: './layers.component.html',
  styleUrls: ['./layers.component.less']
})
export class LayersComponent implements OnInit {

    @Input() data : DataProvider;
    @Input() isCollapsed: boolean = true;
    @HostBinding('class.isCollapsed') hostCollapsed: boolean = true;
    public currentSection : string = SECTIONS.AVAILABLE;
    public Sections = SECTIONS;
    public warning : string;
    public activeLayers : any[];
    public available : any[] = [];
    public totalAvailable : number = 0;
    public services : any[];
    public baseLayers : any[] = [];
    public selectedBaseLayer : any;
    private dataSubscription : Subscription;

    constructor( @Inject(LayerService) layerService : LayerService ) {

        let query = new Query().resourceTypes(BASE_LAYER_RT)
            .fields('*').facets([]).pageSize(20);
        layerService.search(query).then( (response:any) => {
            this.baseLayers = response.results;
        })
        .catch( (e: Error) => {
            logger.error("Unable to fetch available base layer options because of ", e.message);
        });

    }

    ngOnInit() {
        if(this.data) {
            this.dataSubscription = this.data.subscribe( (event : DataEvent) => {
                this.onDataEvent(event);
            });
        }
    }

    ngOnChanges (changes : SimpleChanges) {
        if(changes.isCollapsed) {
            this.hostCollapsed = changes.isCollapsed.currentValue;
        }
    }

    ngOnDestroy() {
        this.dataSubscription.unsubscribe();
        this.dataSubscription = null;
    }

    toggleLayer( item : any ) {
        this.data.toggleData(item);
    }

    toggleLayerViz(item : any) {
        this.data.toggleVisibility(item);
    }


    selectBaseLayer(layer) {
        this.selectedBaseLayer = layer;
        let evt : DataEvent = {
            type: Events.BASE,
            data: [layer]
        }
        this.data.trigger(evt);
    }

    isLayerSelected(layer) {
        return this.data.isSelected(layer);
    }

    isVisible(layer) {
        return this.data.isVisible(layer);
    }



    onDataEvent( event : DataEvent ) {
        logger.debug("AvailableLayers.onDataEvent(" + event.type.toString() + ")");
        switch(event.type) {

            case Events.ON :
            case Events.OFF :
                this.activeLayers = this.data.getSelected(true);
                break;

            case Events.ADD :
                this.organizeLayers();
                if(event.warning) {
                    this.warning = event.warning;
                }

                //if it's a small set of layers, add them to the map automatically
                if(this.data.getData().length < 10) {
                    //but only add the root layers
                    this.data.toggleData(this.available);
                }

                break;

            case Events.DEL :
                break;
        }
    }

    /**
     * In order to show heirarchy relationships between parent and child layers,
     * we need to walk the list and make sure that children are located
     * immediately following their parent, so the UI can display them appropriately
     */
    organizeLayers() {
        this.totalAvailable = 0;
        this.available = [];
        this.services = [];

        let data : any[] = this.data.getData();

        this.available = data.map( (layer : any) => {
            if(!layer.parentLayer) {
                this.totalAvailable++;
                this.available.push(layer);
                // console.log("Adding root layer to list (" + layer.id + ")");
                this.processSubLayers(layer, data);
                return layer;
            }
            return null;
        }).filter(a=>!!a);

        //sort services alphabetically
        this.services.sort( (a:any,b:any) => { return a.label < b.label ? -1 : 1; } );
        //sort root layers alphabetically
        this.available.sort( (a:any,b:any) => { return a.label < b.label ? -1 : 1; } );

    }

    /**
     * For a given layer, walk its list of subLayers to ensure that any
     * of _their_ subLayers are fully resolved using the provided list of layers.
     * If only a list of ids are provided, extract the full objects from the list
     * and put onto their parent.
     * @param parent - Layer
     * @param data - array of Layers to resolve against
     */
    processSubLayers(parent : any, data : any[]) {

        if(parent.subLayers && parent.subLayers.length) {
            // console.log("Walking subLayers for (" + parent.id + ")");
            parent.subLayers.forEach( (child:any) => {
                this.totalAvailable++;
                this.processSubLayers(child, data);

                if(!child.services && parent.services) {
                    child.services = parent.services.slice(0);
                }
            });

        } else if(parent.subLayer_id && parent.subLayer_id.length) {
            let ids = parent.subLayer_id;
            // console.log("Resolving subLayers for (" + parent.id + "): " + ids.join(', '));
            parent.subLayers = data.filter( (layer:any) => ~ids.indexOf(layer.id) );
            this.processSubLayers(parent, data);
        }
    }


}








@Component({
    selector: 'gpmp-layer-available',
    template: `
        <div class="m-layer-item">
            <div class="d-flex flex-justify-between flex-align-center">
                <button class="btn btn-sm btn-link" type="button" (click)="onClick()">
                    <span *ngIf="!isLayerSelected()" class="far fa-square t-fg--gray-md"></span>
                    <span *ngIf="isLayerSelected()" class="far fa-check-square"
                        [ngClass]="{'t-fg--gray-lt':!isSelected&&isParentSelected}">
                    </span>
                    <span class="sr-only">Add or remove this layer from the map</span>
                </button>
                <div [style.width.em]="level>1?level:0" [style.height.px]="1"></div>
                <div *ngIf="item.parentLayer||item.parentLayer_id"
                    class="fas fa-level-up-alt fa-rotate-90  u-mg-left--xxs u-mg-right--sm t-fg--gray-lt">
                </div>
                <a class="flex-1" href="/resources/layers/{{item.id}}" target="_blank"
                    title="View details about this layer">
                    {{item.label}}
                </a>
            </div>
            <div *ngFor="let child of item.subLayers">
                <gpmp-layer-available
                    [item]="child" [data]="data" [level]="level+1"
                    [isSelected]="data.isSelected(child)"
                    [isParentSelected]="isSelected||isParentSelected"
                    (onActivate)="toggleLayer($event)">
                </gpmp-layer-available>
            </div>
        </div>
    `,
    styleUrls: ['./layer.component.less']
})
export class AvailableLayerComponent implements OnInit {

    @Input() item : any;
    @Input() data : DataProvider;
    @Input() isSelected: boolean = false;
    @Input() isParentSelected: boolean = false;
    @Input() level : number = 0;
    @Output() onActivate : EventEmitter<any> = new EventEmitter<any>();

    public isCollapsed : boolean = true;
    public levelArr : any[];

    constructor() {}

    ngOnInit() {
        this.levelArr = new Array(this.level);
    }

    onClick() {
        this.onActivate.emit(this.item);
    }
    toggleLayer( item : any ) {
        this.data.toggleData(item);
    }

    isLayerSelected() {
        return this.isSelected || this.isParentSelected;
    }

}






@Component({
  selector: 'gpmp-layer-selected',
  template: `
  <div class="m-layer-item">
      <button type="button" class="btn btn-sm btn-link" (click)="onClick()">
          <span *ngIf="!item.visibility" class="far fa-eye-slash"></span>
          <span *ngIf="item.visibility" class="far fa-eye"></span>
      </button>
      <span>{{item.label}}</span>
  </div>
  `,
  styleUrls: ['./layer.component.less']
})
export class SelectedLayerComponent {

    @Input() item : any;
    @Input() isVisible : boolean = true;
    @Output() onActivate : EventEmitter<any> = new EventEmitter<any>();

    constructor() {}

    onClick() {
        this.onActivate.emit(this.item);
    }
}
