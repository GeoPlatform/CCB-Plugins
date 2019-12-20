import { Directive, ElementRef, Input, OnInit } from '@angular/core';
import { Item } from "@geoplatform/client";

@Directive({
  selector: '[gpIcon]'
})
export class GeoPlatformIconDirective {

    @Input() item : Item;
    @Input() themed : boolean = true;

    constructor(private el: ElementRef) {

    }

    ngOnInit() {
        if(!this.item) return;
        let className = this.item.type.toLowerCase().replace(/^[a-z]+\:/,'');
        className = 'icon-' + className;
        if(this.themed) {
            className += " is-themed";
        }
        this.el.nativeElement.className = className;
    }
}
