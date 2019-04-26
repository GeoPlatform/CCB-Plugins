import {
    Component, OnInit, OnDestroy, OnChanges,
    Input, Output, EventEmitter, SimpleChanges, HostBinding
} from '@angular/core';
import { ISubscription } from "rxjs/Subscription";

import { DataProvider, DataEvent, Events } from '../shared/data.provider';


const SECTIONS = {
    MAP : "map",
    AVAILABLE : "available"
};



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
    private dataSubscription : ISubscription;


    constructor() { }

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
        let evt : DataEvent = {
            type: Events.VIZ,
            data: [item]
        }
        this.data.trigger(evt);
        // item.visibility = !item.visibility;
    }



    onDataEvent( event : DataEvent ) {
        // console.log("MapLayers.onDataEvent(" + event.type.toString() + ")");
        switch(event.type) {

            case Events.ON :
            case Events.OFF :
                this.activeLayers = this.data.getDataWithState(true);
                break;

            case Events.ADD :
                this.organizeLayers();
                if(event.warning) {
                    this.warning = event.warning;
                }

                //if it's a small set of layers, add them to the map automatically
                if(this.available.length < 10) {
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
        this.available = [];

        let data : any[] = this.data.getData();
        data.forEach( (d:any) => {

            //if layer is not a child of another layer, add it to the list directly
            if(!d.layer.parentLayer) {
                if(this.available.findIndex( l => l.layerId === d.layerId ) < 0) {
                    this.available.push(d);
                }

            } else {

                let idx = this.available.findIndex( l => l.layerId === d.layer.parentLayer.id );
                if(idx > -1) {  //add immediately following parent layer
                    this.available.splice(idx+1, 0, d);

                } else {

                    idx = data.findIndex( l => l.layerId === d.layer.parentLayer.id );
                    if(idx < 0) {
                        console.log("Could not find parent for " + d.layerId);
                        this.available.push(d);
                    } else {
                        this.available.push(data[idx]);
                        this.available.push(d);
                    }
                }

            }

        });
    }

}








@Component({
    selector: 'gpmp-layer-available',
    template: `
        <div class="m-layer-item">
            <div class="d-flex flex-justify-between flex-align-center">
                <button type="button" class="btn btn-sm btn-link" (click)="onClick()">
                    <span *ngIf="!isSelected" class="far fa-square"></span>
                    <span *ngIf="isSelected" class="far fa-check-square"></span>
                </button>
                <span class="flex-1 d-flex flex-justify-between flex-align-center">
                    <span *ngIf="item.layer.parentLayer"
                        class="u-mg-left--sm u-mg-right--xs fas fa-level-up-alt fa-rotate-90 t-fg--gray-md">
                    </span>
                    <span class="flex-1">{{item.layer.label}}</span>
                    <a href="/resources/layers/{{item.layer.id}}" target="_blank"
                        title="View details about this layer">
                        <span class="fas fa-info-circle"></span>
                    </a>
                    <button type="button" class="btn btn-sm btn-link"
                        (click)="isCollapsed=!isCollapsed">
                        <span class="fas"
                            [ngClass]="{'fa-chevron-up':!isCollapsed,'fa-chevron-down':isCollapsed}">
                        </span>
                    </button>
                </span>
            </div>
            <div class="m-layer-item__additional" [ngClass]="{'is-collapsed':isCollapsed}">
                <a href="/resources/services/{{item.layer.services[0].id}}" target="_blank"
                    title="View details about this layer's service">
                    <span class="icon-service"></span>
                    {{item.layer.services[0].label}}
                </a>
            </div>
        </div>
    `,
    styleUrls: ['./layer.component.less']
})
export class AvailableLayerComponent {

    @Input() item : any;
    @Input() isSelected: boolean = false;
    @Output() onActivate : EventEmitter<any> = new EventEmitter<any>();

    public isCollapsed : boolean = true;

    constructor() {}

    onClick() {
        this.onActivate.emit(this.item);
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
      <span>{{item.layer.label}}</span>
  </div>
  `,
  styleUrls: ['./layer.component.less']
})
export class SelectedLayerComponent {

    @Input() item : any;
    @Output() onActivate : EventEmitter<any> = new EventEmitter<any>();

    constructor() {}

    onClick() {
        this.onActivate.emit(this.item);
    }
}
