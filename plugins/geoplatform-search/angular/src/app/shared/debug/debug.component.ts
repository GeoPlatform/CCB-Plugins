import { Component, OnInit, Input } from '@angular/core';

import { environment } from '../../../environments/environment';

@Component({
    selector: 'debug-json',
    templateUrl: './debug.component.html',
    styleUrls: ['./debug.component.css']
})
export class DebugComponent implements OnInit {

    @Input() json: any;
    public isDev : boolean = false;

    constructor() { }

    ngOnInit() {
        this.isDev = !environment.production;
    }

    toHTML(arg?: any) {
        let obj = arg || this.json;
        if(!obj) return '';
        obj = JSON.parse(JSON.stringify(obj));

        if(Array.isArray(obj)) {
            return '<div class="c-debug__array">' +
                obj.map( o => this.toHTML(o) ).join(' ') +
                '</div>';
        }
        if(typeof(obj) === 'object') {
            return '<div class="c-debug__object">' +
                Object.keys(obj).map( prop => {
                    return '<div class="c-debug__property">' +
                        '<b>' + prop + '</b>' + this.toHTML(obj[prop]) +
                        '</div>';
                }).join(' ') +
                '</div>';
        } else {
            return `<span class="c-debug__value">${obj}</span>`;
        }
    }

}
