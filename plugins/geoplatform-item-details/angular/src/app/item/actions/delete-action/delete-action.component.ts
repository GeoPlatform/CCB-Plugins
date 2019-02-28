import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "geoplatform.client";

import { NG2HttpClient } from "../../../shared/http-client";
import { environment } from '../../../../environments/environment';
import { itemServiceProvider } from '../../../shared/service.provider';

@Component({
  selector: 'gpid-delete-action',
  templateUrl: './delete-action.component.html',
  styleUrls: ['./delete-action.component.less'],
  providers: [itemServiceProvider]
})
export class DeleteActionComponent implements OnInit {

    @Input() item : any;

    constructor(private itemService : ItemService) {
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
