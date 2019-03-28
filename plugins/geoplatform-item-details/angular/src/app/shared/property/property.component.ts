import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-property',
  templateUrl: './property.component.html',
  styleUrls: ['./property.component.less']
})
export class PropertyComponent implements OnInit {

    @Input() label : string;
    @Input() value : any;
    @Input() labelProperty : string;
    @Input() valueProperty : string;
    @Input() hasMultiple : boolean = false;
    @Input() isUrl : boolean = false;
            
    constructor() { }

    ngOnInit() {
    }

}
