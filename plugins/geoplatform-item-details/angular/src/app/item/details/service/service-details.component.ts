import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-service-details',
  templateUrl: './service-details.component.html',
  styleUrls: ['./service-details.component.less']
})
export class ServiceDetailsComponent implements OnInit {

    @Input() item : any;

    constructor() { }

    ngOnInit() {
    }

}
