import {
    Component, OnInit, Input, Directive, HostBinding
} from '@angular/core';
import {
    DomSanitizer, SafeResourceUrl, SafeUrl
} from '@angular/platform-browser';

import { environment } from '../../../environments/environment';


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
  selector: 'thumbnail',
  templateUrl: './thumbnail.component.html',
  styleUrls: ['./thumbnail.component.css']
})
export class ThumbnailComponent implements OnInit {

    @Input() source : { url?: string, contentData?: string, mediaType?:string };
    @Input() fallback : string = `../${environment.assets}img-404.png`;

    constructor(private sanitizer: DomSanitizer) { }

    ngOnInit() {

    }

    getBackgroundImage() {
        let type = this.source.mediaType || 'image/png';
        let content = this.source.contentData;
        return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
    }

}
