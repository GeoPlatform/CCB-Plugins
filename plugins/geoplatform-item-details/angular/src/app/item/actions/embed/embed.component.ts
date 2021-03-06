import {
    Component, OnInit, Input, Inject, ViewChild, ElementRef
} from '@angular/core';
import { HttpRequest } from '@angular/common/http';
import { NgbModal, NgbActiveModal, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';
import { Config, Item, ItemTypes } from '@geoplatform/client';
import { ItemHelper } from '../../../shared/item-helper';

@Component({
  selector: 'gpid-embed-action',
  templateUrl: './embed.component.html',
  styleUrls: ['./embed.component.less']
})
export class EmbedActionComponent implements OnInit {

    @Input() item : any;

    public embedHref : string;
    public isCollapsed : boolean = true;


    constructor(private modalService: NgbModal) {}


    ngOnInit() {
        let url = Config.ualUrl.replace('ual','maps');
        let type = this.item.type;
        if( ItemTypes.GALLERY === type || ItemTypes.MAP === type ) {
            this.embedHref = url + '/' + type.toLowerCase() + ".html?id=" + this.item.id;
        }
    }

    isSupported() {
        return this.item && (
            ItemTypes.MAP === this.item.type ||
            ItemTypes.GALLERY === this.item.type
        );
    }

    toggleEmbed() {
        this.isCollapsed = !this.isCollapsed;
    }

    open() {
        let opts : NgbModalOptions = { size: 'lg', centered: true };
        const modalRef = this.modalService.open(EmbedModalContent, opts);
        modalRef.componentInstance.href = this.embedHref;
        modalRef.componentInstance.type = ItemHelper.getTypeLabel(this.item);
    }

}








@Component({
  selector: 'gpid-embed-modal-content',
  template: `
  <div class="modal-header">
      <div class="a-heading modal-title" id="modal-basic-title">Embed {{type||"Item"}}</div>
  </div>
  <div class="modal-body">

    <p>
        Copy and paste the following HTML into a webpage to embed a representation of this {{type||"item"}}.
        Note that you may need to adjust the width and/or height on the <code>iframe</code>
        in order to properly fit it into your page alongside other content.
    </p>

    <textarea rows="6" #embedcode readonly="true">
<iframe src="{{href}}"
    style="width:100%; height:100%; min-height: 550px; overflow:auto; overflow-x: hidden; overflow-y: auto;" frameborder="0" seamless>
   <p>Please verify browser iframe support.</p>
</iframe></textarea>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-light" (click)="copyToClipboard(embedcode)">
        <span class="fas fa-copy"></span> Copy
      </button>
      <button type="button" class="btn btn-secondary" (click)="activeModal.close()">Close</button>
  </div>
  `,
  styles: [
      `
      textarea {
          width:100%;
          padding: 1em 0.25em;
          background-color: #f6f6f6;
          color: #555;
          font-family: "Consolas";
          font-size: 0.875em;
      }
      `
  ]
})
export class EmbedModalContent {
    @Input() href : string;
    @Input() type : string;
    @ViewChild('embedcode', {static: false}) embedCodeEl: ElementRef;

    constructor(public activeModal: NgbActiveModal) {}

    copyToClipboard(element){
        element.select();
        document.execCommand('copy');
        element.setSelectionRange(0, 0);
    }
}
