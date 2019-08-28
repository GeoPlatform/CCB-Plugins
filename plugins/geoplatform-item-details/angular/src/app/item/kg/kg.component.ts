import { Component, OnInit, Input } from '@angular/core';
import { ItemTypes } from '@geoplatform/client';

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

    public isCollapsed : boolean = false;

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

    }

    toggleDisplay() {
        this.isCollapsed = !this.isCollapsed;
    }

    getKeys() {
        return CLASSIFIERS;
    }

    getIcon() : string {
        return ItemHelper.getIcon(ItemTypes.CONCEPT);
    }

}
