import { Inject, Component, Input, OnInit, OnDestroy } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Config, ItemTypes, ItemService, Query, QueryParameters } from '@geoplatform/client';

import { NG2HttpClient } from "../../../shared/http-client";
import { AuthenticatedComponent } from '../../../shared/authenticated.component';
import { PluginAuthService } from '../../../shared/auth.service';
import { ItemHelper } from '../../../shared/item-helper';


@Component({
  selector: 'gpid-gallery-action',
  templateUrl: './gallery.component.html',
  styleUrls: ['./gallery.component.less']
})
export class GalleryActionComponent extends AuthenticatedComponent implements OnInit {

    @Input() item : any;
    public type : string;
    public awaitingInput : boolean = false;
    public searching : boolean = false;
    public totalSuggested : number;
    public maxSuggested : number;
    public suggested : any[];
    public keywords : string;
    public query : Query;
    public error : Error;
    private itemService : ItemService;


    constructor(
        @Inject(ItemService) itemService : ItemService,
        authService : PluginAuthService
    ) {
        super(authService);
        this.itemService = itemService;
    }

    ngOnInit() {
        super.init();
        this.type = ItemHelper.getTypeLabel(this.item);
        this.keywords = null;
        this.maxSuggested = 5;
        this.totalSuggested = 0;
        this.query = new Query()
        .types(ItemTypes.GALLERY)
        .pageSize(this.maxSuggested);
        //don't bother with galleries already containing this item
        this.query.setParameter('facet.items.asset_id.not', this.item.id);
        this.awaitingInput = false;
        this.searching = false;
    }

    ngOnDestroy() {
        super.destroy();
    }

    onUserChange(user) {
        super.onUserChange(user);

        if(!this.canUserEdit()) {
            //normal users can only add to galleries they have created
            this.query.setParameter( QueryParameters.CREATED_BY, user ? user.username : null );
        } //else editors can add to any gallery
    }

    /**
     * @return boolean
     */
    isSupported () {
        return this.item && this.item.id && this.isAuthenticated();
    }

    doAction () {  }

    promptForInput() {
        this.awaitingInput = true;
        this.suggestGalleries(this.keywords);
    }

    suggestGalleries(keywords ?: string, resetPaging ?: boolean) {
        this.searching = true;
        this.keywords = keywords;
        this.query.q(this.keywords);
        if(true === resetPaging) {
            this.query.setPage(0);
        }
        this.itemService.search(this.query)
        .then( response => {
            this.suggested = response.results;
            this.totalSuggested = response.totalResults;
        })
        .catch( (e) => { this.error = e; })
        .finally( () => { this.searching = false; });
    }

    /**
     * @param gallery - Gallery object
     */
    addToGallery(gallery) {

        let patch = [{
            op:"add",
            path:"/items",
            value: [{
                label: this.item.label,
                description: this.item.description,
                assetId: this.item.id,
                assetType: ItemTypes.MAP,
                asset: {
                    id: this.item.id,
                    uri: this.item.uri,
                    type: this.item.type
                }
            }]
        }];

        this.itemService.patch(gallery.id, patch)
        .then( updated => { //open gallery it was added to
            let url = Config.wpUrl + '/resources/' +
                ItemHelper.getTypeKey(updated) + '/' + updated.id;
            window.location.href = url;
        })
        .catch( (err) => {
            this.error = new Error("Failed to add to selected gallery: " + err.message);
        });
    }

    /**
     *
     */
    addToNewGallery() {
        let user = this.getUser();
        if(!user) {
            this.error = new Error("You are not signed in or your session has expired");
            return;
        };

        let gallery = {
            type: ItemTypes.GALLERY,
            title: "My New Gallery",
            label: "My New Gallery",
            items: [{
                label: this.item.label,
                description: this.item.description,
                assetId: this.item.id,
                assetType: ItemTypes.MAP,
                asset: {
                    id: this.item.id,
                    uri: this.item.uri,
                    type: this.item.type,
                    title: this.item.title
                }
            }],
            createdBy: user.username
        };

        this.ensureUnique(gallery)
        .then( gallery => this.itemService.save(gallery) )
        .then( created => {
            let url = Config.wpUrl + '/resources/' +
                ItemHelper.getTypeKey(created) + '/' + created.id;
            window.location.href = url;
        })
        .catch( (err) => {
            this.error = new Error("Failed to create a new gallery: " + err.message);
        });
    }

    /**
     * @param gallery to-be-created Gallery object
     * @return Promise resolving the Gallery with a guaranteed unique URI
     */
    ensureUnique(gallery) {
        let regex = /.+\s(\d+)$/;
        return this.itemService.getUri(gallery)
        .then( uri => {
            gallery.uri = uri;
            return this.itemService.exists([uri]);
        })
        .then( response => {
            if(!response.length || !response[0].id) return gallery;

            let matches = regex.exec(gallery.title);
            if(matches && matches.length) {
                //already ends with a number.  increment and try again...
                let idx = gallery.title.lastIndexOf(matches[1]);
                let newCount = parseInt(matches[1]) + 1;
                gallery.title = gallery.label = gallery.title.substring(0, idx) + newCount;
            } else {
                //doesn't end with a number. add one and try again...
                gallery.title = gallery.label = gallery.title + ' 2';
            }
            return this.ensureUnique(gallery);
        });
    }


    getPagingInfo () {
        let page = this.query.getPage();
        let size = this.query.getPageSize();
        return ((page * size) + 1) + " - " + Math.min(this.totalSuggested, (page+1) * size);
    }

    firstPage() {
        this.query.setPage(0);
        this.suggestGalleries(this.keywords);
    }
    prevPage() {
        this.query.setPage(this.query.getPage()-1);
        this.suggestGalleries(this.keywords);
    }
    nextPage() {
        this.query.setPage(this.query.getPage()+1);
        this.suggestGalleries(this.keywords);
    }
    hasNextPage() {
        let page = this.query.getPage();
        let size = this.query.getPageSize();
        return ((page+1)*size) < this.totalSuggested;
    }

}
