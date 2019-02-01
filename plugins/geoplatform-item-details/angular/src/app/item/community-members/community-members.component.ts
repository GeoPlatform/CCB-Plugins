import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'gpid-community-members',
  templateUrl: './community-members.component.html',
  styleUrls: ['./community-members.component.less']
})
export class CommunityMembersComponent implements OnInit {

    @Input() members : any[];
    public isCollapsed : boolean = true;

    constructor() { }

    ngOnInit() {
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

}
