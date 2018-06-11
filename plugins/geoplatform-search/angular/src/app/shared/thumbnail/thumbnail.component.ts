import { Component, OnInit, Input } from '@angular/core';
import { DomSanitizer, SafeResourceUrl, SafeUrl } from '@angular/platform-browser';

@Component({
  selector: 'thumbnail',
  templateUrl: './thumbnail.component.html',
  styleUrls: ['./thumbnail.component.css']
})
export class ThumbnailComponent implements OnInit {

    @Input() source : { url?: string, contentData?: string, mediaType?:string };

    constructor(private sanitizer: DomSanitizer) { }

    ngOnInit() {

    }

    getBackgroundImage() {
        let type = this.source.mediaType || 'image/png';
        let content = this.source.contentData;
        return this.sanitizer.bypassSecurityTrustStyle(`url(data:${type};base64,${content})`);
    }

}
