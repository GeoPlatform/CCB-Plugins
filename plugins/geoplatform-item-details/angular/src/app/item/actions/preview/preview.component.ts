import { Component, OnInit, Input, Inject } from '@angular/core';
import { HttpRequest } from '@angular/common/http';
import { Config, ItemTypes } from '@geoplatform/client';
import { NG2HttpClient } from '@geoplatform/client/angular';
import { ItemHelper } from '../../../shared/item-helper';

@Component({
  selector: 'gpid-preview-action',
  templateUrl: './preview.component.html',
  styleUrls: ['./preview.component.less']
})
export class PreviewActionComponent implements OnInit {

    @Input() item : any;
    public description : string = "View this resource on a map. For Service resources, " +
        "this includes all layers hosted by the service. For Dataset resources, this " +
        "includes associated layers as well as layers hosted by associated services.";
    private previewable : boolean = false;


    constructor( private client : NG2HttpClient ) { }

    ngOnInit() {
        let opts = {
            method: "GET",
            url: Config.ualUrl + '/api/items/' + this.item.id + '/preview'
        };
        let request : HttpRequest<any> = this.client.createRequestOpts(opts);
        this.client.execute(request).then( (response:any) => {
            this.previewable = response.result === true;
        }).catch( e => {
            //do nothing
        });
    }

    isSupported() {
        return this.item && this.previewable && (
            ItemTypes.DATASET === this.item.type ||
            ItemTypes.SERVICE === this.item.type ||
            ItemTypes.LAYER   === this.item.type ||
            ItemTypes.GALLERY === this.item.type
        );
    }

    getHref() {
        let type = ItemHelper.getTypeKey(this.item);
        let url = '/resources/' + type + '/' + this.item.id + '/map';
        return url;
    }

}
