import { Component, OnInit, Input } from '@angular/core';


const CLASSIFIERS = [
    'purpose',
    'function',
    'primarySubjects',
    'secondarySubjects',
    'primaryTopics',
    'secondaryTopics',
    'places',
    'categories',
    'audience',
    'community'
]



@Component({
  selector: 'gpid-kg',
  templateUrl: './kg.component.html',
  styleUrls: ['./kg.component.less']
})
export class KgComponent implements OnInit {

    @Input() classifiers : any;

    public isCollapsed : boolean = true;

    public activeTab : string = 'purpose';

    constructor() { }

    ngOnInit() {
    }

    toggleDisplay() {
        this.isCollapsed = !this.isCollapsed;
    }

    getKeys() {
        return CLASSIFIERS;
    }

    changeTab(tabName) {
        this.activeTab = tabName;
    }
}
