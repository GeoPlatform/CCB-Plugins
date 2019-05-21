
import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes, ItemService } from 'geoplatform.client';
import { ItemHelper } from '../../../shared/item-helper';
import { AuthenticatedComponent } from '../../../shared/authenticated.component';
import { environment } from '../../../../environments/environment';
import { NG2HttpClient } from '../../../shared/http-client';
import { itemServiceProvider } from '../../../shared/service.provider';


@Component({
  selector: 'gpid-clone-action',
  templateUrl: './clone.component.html',
  styleUrls: ['./clone.component.less'],
  providers: [itemServiceProvider]
})
export class CloneComponent extends AuthenticatedComponent implements OnInit {

    @Input() item : any;
    public canClone : boolean;
    public awaitingInput : boolean = false;
    public overrides : {[key:string]:any};
    public error : Error;

    constructor(private itemService : ItemService) {
        super();
    }

    ngOnInit() {
        super.init();

        this.canClone = this.isAuthenticated();
        this.overrides = {
            label : "Clone of " + this.item.label
        };
        let token = this.authService.getJWTfromLocalStorage();
        this.itemService.client.setAuthToken(token);
    }

    ngOnDestroy() {
        super.destroy();
    }

    isSupported() {
        return this.item &&
            this.canClone &&
            ItemHelper.isAsset(this.item);
    }

    doAction() {
        this.itemService.clone(this.item.id, this.overrides)
        .then( clone => {
            let type = ItemHelper.getTypeKey(clone);
            let url = '/resources/' + type + '/' + clone.id;
            (window as any).location.href = url;
        })
        .catch( (e:Error) => {
            this.error = e;
        })
        .finally( () => {
            this.awaitingInput = false;
        });
    }

    promptForInput() {
        this.awaitingInput = true;
    }


}
