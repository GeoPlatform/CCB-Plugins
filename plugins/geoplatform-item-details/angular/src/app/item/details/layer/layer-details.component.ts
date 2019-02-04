import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-layer-details',
  templateUrl: './layer-details.component.html',
  styleUrls: ['./layer-details.component.less']
})
export class LayerDetailsComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

}
