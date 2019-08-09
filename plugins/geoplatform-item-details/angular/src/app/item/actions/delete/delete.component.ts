import { Inject, Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ItemTypes, Config, ItemService } from "@geoplatform/client";

import { NG2HttpClient } from "../../../shared/http-client";
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'gpid-delete-action',
  templateUrl: './delete.component.html',
  styleUrls: ['./delete.component.less']
})
export class DeleteActionComponent implements OnInit {

    @Input() item : any;
    private itemService : ItemService;

    constructor( @Inject(ItemService) itemService : ItemService ) {
        this.itemService = itemService;
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

        this.itemService.remove(this.item.id)
        .then( (response) => {
            //success!
            //show message and then re-direct back to ... ???
        })
        .catch(e => {
            //show error message
        });
    }

}
