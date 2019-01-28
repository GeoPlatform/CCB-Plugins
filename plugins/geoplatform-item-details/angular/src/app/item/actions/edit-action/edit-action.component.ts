import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';

import { environment } from '../../../../environments/environment';

@Component({
  selector: 'gpid-edit-action',
  templateUrl: './edit-action.component.html',
  styleUrls: ['./edit-action.component.less']
})
export class EditActionComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

    ngOnChanges( changes : SimpleChanges ) {

    }

    isAuthorized() : boolean {
        if(!this.item || !this.item.id) return false;
        //TODO check user credentials for 'gp_editor' role or ownership of this item
        return false;
    }

    getEditUrl() : string {
        if(!this.item || !this.item.id) return '';
        let url = environment.editUrl;
        if(!url) {
            console.log("EditAction - URL to edit items is not configured");
        }
        return url.replace(/\{id\}/, this.item.id);
    }

}
