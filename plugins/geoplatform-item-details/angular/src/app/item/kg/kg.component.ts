import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from 'geoplatform.client';

import { ItemHelper } from '../../shared/item-helper';


const CLASSIFIERS = [
    'purposes',
    'functions',
    'primarySubjects',
    'secondarySubjects',
    'primaryTopics',
    'secondaryTopics',
    'places',
    'categories',
    'audiences',
    'communities'
]




@Component({
  selector: 'gpid-kg',
  templateUrl: './kg.component.html',
  styleUrls: ['./kg.component.less']
})
export class KgComponent implements OnInit {

    @Input() classifiers : any;

    public isCollapsed : boolean = true;
    public isClassifierCollapsed : any = {};

    public activeTab : string = 'purposes';

    public classifierLabels = {
        'purposes' : 'Purpose',
        'functions': "Function",
        'primarySubjects': "Primary Subject",
        'secondarySubjects': "Secondary Subject",
        'primaryTopics': "Primary Topic",
        'secondaryTopics': "Secondary Topic",
        'places': "Place",
        'categories': "Category",
        'audiences': "Audience",
        'communities' : "Community"
    }

    constructor() { }

    ngOnInit() {
        CLASSIFIERS.forEach(key => { this.isClassifierCollapsed[key] = false });
    }

    toggleDisplay(key) {
        if(key) {
            this.isClassifierCollapsed[key] = !this.isClassifierCollapsed[key];
        } else {
            this.isCollapsed = !this.isCollapsed;
        }
    }

    getKeys() {
        return CLASSIFIERS;
    }

    changeTab(tabName) {
        this.activeTab = tabName;
    }

    getIcon() : string {
        return ItemHelper.getIcon(ItemTypes.CONCEPT);
    }
}
