
import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, ItemService } from 'geoplatform.client';
import { ItemHelper } from '../../../shared/item-helper';
import { AuthenticatedComponent } from '../../../shared/authenticated.component';
import { PluginAuthService } from '../../../shared/auth.service';
import { environment } from '../../../../environments/environment';
import { NG2HttpClient } from '../../../shared/http-client';
import { itemServiceProvider } from '../../../shared/service.provider';

@Component({
  selector: 'gpid-clone-action',
  templateUrl: './clone.component.html',
  styleUrls: ['./clone.component.less'],
  providers: [itemServiceProvider]
})
export class CloneActionComponent extends AuthenticatedComponent implements OnInit {

    @Input() item : any;
    public canClone : boolean;
    public awaitingInput : boolean = false;
    public overrides : {[key:string]:any};
    public error : Error;

    constructor(
        private itemService : ItemService,
        authService : PluginAuthService
    ) {
        super(authService);
    }

    ngOnInit() {
        super.init();
        this.overrides = {
            label : "Clone of " + this.item.label
        };
    }

    ngOnDestroy() {
        super.destroy();
    }

    // onUserChange(user) {
    //     super.onUserChange(user);
    //     console.log("Clone.onUserChange() : " + JSON.stringify(user, null, ' '));
    // }

    isSupported() {
        return this.item && ItemHelper.isAsset(this.item);
    }

    isAuthenticated() {
        if(!environment.production) return true;
        return super.canUserEdit(); //must be a "gp_editor" to clone
    }

    canDoAction() {
        return this.isAuthenticated() && this.isSupported();
    }

    /**
     *
     */
    doAction() {

        //first, ensure token isn't in weird expired/revoked state
        this.checkAuth().then( user => {

            if(!user) throw new Error("Not signed in");
            let token = this.getAuthToken();
            this.itemService.client.setAuthToken(token);

            //then trigger the clone operation
            return this.itemService.clone(this.item.id, this.overrides);
        })
        .then( clone => {
            this.awaitingInput = false;

            //then redirect browser to clone's ID page
            let type = ItemHelper.getTypeKey(clone);
            let url = '/resources/' + type + '/' + clone.id;
            (window as any).location.href = url;
        })
        .catch( (e:Error) => {
            this.awaitingInput = false;
            this.error = e;
        });
    }

    promptForInput() {
        this.awaitingInput = true;
    }


}
