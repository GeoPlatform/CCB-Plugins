import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { NG2HttpClient } from "../../../shared/http-client";
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'gpid-delete-action',
  templateUrl: './delete-action.component.html',
  styleUrls: ['./delete-action.component.less']
})
export class DeleteActionComponent implements OnInit {

    @Input() item : any;
    private itemService : ItemService;

    constructor(http : HttpClient) {
        let client = new NG2HttpClient(http);
        this.itemService = new ItemService(Config.ualUrl, client);
    }

    ngOnInit() {
    }

    ngOnChanges( changes : SimpleChanges ) {

    }

    isAuthorized() : boolean {
        if(!this.item) return false;
        //TODO check user credentials for 'gp_editor' role or ownership of this item
        return false;
    }

    doAction() : string {
        if(!this.item) return;

        this.itemService.delete(this.item)
        .then( (response) => {
            //success!
            //show message and then re-direct back to ... ???
        })
        .catch(e => {
            //show error message
        });
    }

}
