import {
    Component, OnInit, Input, Directive, HostBinding
} from '@angular/core';
import {
    DomSanitizer, SafeResourceUrl, SafeUrl
} from '@angular/platform-browser';

import { Config, ItemTypes } from "geoplatform.client";

import { environment } from '../../../environments/environment';


interface Thumbnail {
    url ?: string;
    contentData ?: string;
    width ?: number;
    height ?: number;
    mediaType ?: string;
}



@Directive({
    selector: 'img[fallback]',
    host: {
        '(error)':'onImgError()',
        '(load)': 'onImgLoad()',
        '[src]':'src'
     }
})
export class ImageFallbackDirective {
    @Input() src : string;
    @Input() fallback : string = `${environment.assets}/img-404.png`;
    @HostBinding('class') className
    onImgError() { this.src = this.fallback; }
    onImgLoad() { this.className = 'is-image-loaded'; }
}



@Component({
  selector: 'gpid-depiction',
  templateUrl: './depiction.component.html',
  styleUrls: ['./depiction.component.less']
})
export class DepictionComponent implements OnInit {

    @Input() item : any;

    constructor(private sanitizer: DomSanitizer) { }

    ngOnInit() {
    }

    getThumbnailUrl() {
        if(this.item.thumbnail && this.item.thumbnail.url)
            return this.item.thumbnail.url;
        return Config.ualUrl + '/api/items/' + this.item.id + '/thumbnail';
    }

    getBackgroundImage() {
        if(!this.item || !this.item.thumbnail) {
            return this.getFallbackBackgroundImage();
        }

        let thumbnail = this.item.thumbnail;
        let type = thumbnail.mediaType || 'image/png';

        if(thumbnail.contentData) {
            let content = thumbnail.contentData;
            return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
        }
    }

    getFallbackBackgroundImage() {
        let url = this.getFallbackUrl();
        return `url(${url})`;
    }

    isEmpty() {
        return !this.item || !this.item.thumbnail ||
            ( !this.item.thumbnail.url && !this.item.thumbnail.contentData );
    }

    hasURL() {
        return this.item.thumbnail && !!this.item.thumbnail.url;
    }

    hasContentData() {
        return this.item.thumbnail && !!this.item.thumbnail.contentData && !this.item.thumbnail.url;
    }

    getFallbackUrl() {
        let path = 'no-thumb.png';
        switch(this.item.type) {
            case ItemTypes.DATASET :        path = 'icons/dataset.svg'; break;
            case ItemTypes.SERVICE :        path = 'icons/service.svg'; break;
            case ItemTypes.LAYER :          path = 'icons/layer.svg'; break;
            case ItemTypes.MAP :            path = 'icons/map.svg'; break;
            case ItemTypes.GALLERY :        path = 'icons/gallery.svg'; break;
            case ItemTypes.COMMUNITY :      path = 'icons/community.svg'; break;
            case ItemTypes.ORGANIZATION :   path = 'icons/organization.svg'; break;
            case ItemTypes.CONTACT :        path = 'icons/vcard.svg'; break;
            case ItemTypes.PERSON :         path = 'icons/vcard.svg'; break;
            case ItemTypes.CONCEPT :        path = 'icons/concept.svg'; break;
            case ItemTypes.CONCEPT_SCHEME : path = 'icons/conceptscheme.svg'; break;
        }
        return `${environment.assets}/${path}`;
    }
}
