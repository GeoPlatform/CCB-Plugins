import {
    Component, OnInit, Input, Directive, HostBinding
} from '@angular/core';
import {
    DomSanitizer, SafeResourceUrl, SafeUrl
} from '@angular/platform-browser';

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
    @Input() fallback : string = `../${environment.assets}img-404.png`;
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

    @Input() thumbnail : Thumbnail;
    @Input() fallback : string = `/${environment.assets}no-thumb.png`;


    constructor(private sanitizer: DomSanitizer) { }

    ngOnInit() {
    }

    getBackgroundImage() {
        if(!this.thumbnail)
            return `url(${this.fallback})`;

        let type = this.thumbnail.mediaType || 'image/png';
        let content = this.thumbnail.contentData;
        return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
    }

    isEmpty() {
        return !this.thumbnail || ( !this.thumbnail.url && !this.thumbnail.contentData );
    }

    hasURL() {
        return this.thumbnail && !!this.thumbnail.url;
    }

    hasContentData() {
        return this.thumbnail && !!this.thumbnail.contentData && !this.thumbnail.url;
    }
}
