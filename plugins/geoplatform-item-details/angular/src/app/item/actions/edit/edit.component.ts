import { Component, OnInit, OnChanges, OnDestroy, SimpleChanges, Input } from '@angular/core';
import { Config } from '@geoplatform/client';

import { AuthenticatedComponent } from '../../../shared/authenticated.component';
import { PluginAuthService } from '../../../shared/auth.service';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'gpid-edit-action',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.less']
})
export class EditActionComponent extends AuthenticatedComponent implements OnInit {

    @Input() item : any;

    constructor( authService : PluginAuthService ) {
        super(authService);
    }

    ngOnInit() {
        super.init();
    }

    ngOnChanges( changes : SimpleChanges ) {

    }

    ngOnDestroy() {
        super.destroy();
    }

    isAuthorized() : boolean {
        if(!this.item || !this.item.id) return false;
        //TODO check user credentials for 'gp_editor' role or ownership of this item

        let user = this.getUser();
        if(!user) return false;

        //owner
        if(this.item.createdBy && user.username === this.item.createdBy)
            return true;

        //gp editor
        if(user.groups && user.groups.filter(g=>'gp_editor'===g.name).length)
            return true;

        return false;
    }

    getEditUrl() : string {
        if(!this.item || !this.item.id) return '';
        return Config.ualUrl.replace('ual','oe') + '/edit/' + this.item.id;
    }

}
