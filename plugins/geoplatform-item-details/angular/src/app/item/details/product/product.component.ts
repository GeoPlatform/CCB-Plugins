import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-product-details',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.less']
})
export class ProductComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

}
