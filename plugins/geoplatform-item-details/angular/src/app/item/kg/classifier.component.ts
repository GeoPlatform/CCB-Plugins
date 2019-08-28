import { Component, Input } from '@angular/core';

@Component({
  selector: 'gpid-kg-classifier',
  templateUrl: './classifier.component.html',
  styleUrls: ['./classifier.component.less']
})
export class ClassifierComponent {

    @Input() label : string;
    @Input() values : any[];
    public isCollapsed : boolean = false;

    constructor() {

    }

}
