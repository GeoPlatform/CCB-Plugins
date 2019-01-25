import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';

import { GoogleCharts } from 'google-charts';

const MONTHS = [
    'Jan','Feb','Mar','Apr','May','Jun',
    'Jul','Aug','Sep','Oct','Nov','Dec'
];
const DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];



@Component({
  selector: 'gpid-service-stats',
  templateUrl: './service-stats.component.html',
  styleUrls: ['./service-stats.component.less']
})
export class ServiceStatsComponent implements OnInit {

    @Input() item : any;

    public isCollapsed : boolean = false;

    public activeTab : string = 'stats_weekly';
    private tabs : any  = {
        stats_weekly : { status: true, label: "Past Week" },
        stats_monthly : { status: false, label: "Past Month" },
        stats_yearly : { status: false, label: "Past Year" }
    };
    private charts : any = {
        stats_weekly : { status: false, fn: () => {this.drawWeeklyChart()} },
        stats_monthly : { status: false, fn: () => {this.drawMonthlyChart()} },
        stats_yearly : { status: false, fn: () => {this.drawYearlyChart()} }
    };
    private googleIsLoaded : boolean = false;
    private googleWaitAttempts : number = 0;
    private svcStatsData : any;


    constructor() { }

    ngOnInit() {
        //Load the charts library
        GoogleCharts.load(() => { this.googleIsLoaded = true; });
    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.item && changes.item.currentValue) {

            let itemId = changes.item.currentValue.id;

            //TODO fetch RPM stats...
            // this.serviceSvc.getHistory(itemId)
            // .then( response => {
            //     //TODO rebuild charts
                    this.initCharts(true);
            // })
            // .catch(e => {
            //     //display error message in place of charts
            // });
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }

    getTabKeys() {
        return Object.keys(this.tabs);
    }

    /**
     * @param {string} tabName - tab to set as active
     */
    changeTab(tabName) {
        this.tabs[this.activeTab].status = false;
        this.tabs[tabName].status = true;
        this.activeTab = tabName;

        if(!this.charts[tabName].status) {
            setTimeout( () => {
                this.charts[tabName].status = true;
                this.charts[tabName].fn();
            }, 100);
        }
    }

    initCharts( rebuild:boolean = false ) {
        if(!this.googleIsLoaded) {
            if(this.googleWaitAttempts < 5) {
                //wait just a bit more for google charts api to finish loading
                setTimeout( () => { this.initCharts(); }, 1000);
            } else {
                //display error message that google api could not load...
            }

            return;
        }

        if(rebuild === true) {
            //change in item data, so let's rebuild the charts
            this.charts.forEach( ch => { ch.status = false; });
        }

        //draw whichever chart is currently visible
        let chart = this.charts[this.activeTab];
        if(!chart.status) {
            setTimeout( () => {
                chart.status = true;
                chart.fn();
            }, 100);
        }
    }



    drawWeeklyChart() {

        var data = new GoogleCharts.api.visualization.DataTable();
        data.addColumn('string', 'Day'); // Implicit domain label col.
        data.addColumn('number', 'Displayed'); // Implicit series 1 data col.
        data.addColumn({type:'string', role:'tooltip'});


        let date = new Date();


        let time = date.getTime();
        time -= 1000 * 60 * 60 * 24 * 7;
        date.setTime(time);

        let rows = [], max = 0;
        for(let i=0; i<7; ++i) {
            time = date.getTime();
            time += (1000 * 60 * 60 * 24);
            date.setTime(time);
            let dow = date.getDay();    //day of week
            let v = Math.floor(Math.random()*10);
            max = Math.max(max, v);
            let label = DAYS[dow] + ' (' + (date.getMonth()+1) + '/' +
                (date.getDate()+1) + ')';
            rows.push([ label, v, v + '' ]);
        }
        data.addRows(rows);

        var options = {
            height: 300,
            title: "Last 7 Days",
            hAxis: {
                title: 'Date',
                format: 'M/d/yy',
                minValue: rows[0][0],
                maxValue: rows[rows.length-1][0]
            },
            vAxis: {
                title: 'Usage',
                minValue: 0,
                maxValue: max + 10
            },
            legend: 'none'
        };

        let el = document.getElementById('stats_weekly_chart');
        var chart = new GoogleCharts.api.visualization.LineChart(el);
        chart.draw(data, options);
    }




    drawMonthlyChart() {

        var data = new GoogleCharts.api.visualization.DataTable();
        data.addColumn('number', 'Day'); // Implicit domain label col.
        data.addColumn('number', 'Displayed'); // Implicit series 1 data col.
        data.addColumn({type:'string', role:'tooltip'});

        let date = new Date();
        let currentMonth = date.getMonth();
        let currentDay = date.getDate();

        let rows = [], max = 0;
        for(let i=0; i<currentDay; ++i) {
            let v = Math.floor(Math.random()*10);
            max = Math.max(max, v);
            rows.push([ (i+1), v, v + '' ]);
        }
        data.addRows(rows);

        var options = {
            height: 300,
            title: "This Month",
            hAxis: {
                title: 'Day of Month',
                minValue: 0,
                maxValue: currentDay
            },
            vAxis: {
                title: 'Usage',
                minValue: 0,
                maxValue: max + 10
            },
            legend: 'none'
        };

        let el = document.getElementById('stats_monthly_chart');
        var chart = new GoogleCharts.api.visualization.LineChart(el);
        chart.draw(data, options);
    }



    drawYearlyChart() {

        var data = new GoogleCharts.api.visualization.DataTable();
        data.addColumn('string', 'Month'); // Implicit domain label col.
        data.addColumn('number', 'Displayed'); // Implicit series 1 data col.
        data.addColumn({type:'string', role:'tooltip'});

        let date = new Date();
        let currentMonth = date.getMonth();

        let rows = [], max = 0;
        for(let i=0; i<currentMonth; ++i) {
            let v = Math.floor(Math.random()*10);
            max = Math.max(max, v);
            rows.push([ MONTHS[i], v, v + '' ]);
        }
        data.addRows(rows);

        var options = {
            height: 300,
            title: "This Year",
            hAxis: {
                title: 'Month'
            },
            vAxis: {
                title: 'Usage',
                minValue: 0,
                maxValue: max + 10
            },
            legend: 'none'
        };

        let el = document.getElementById('stats_yearly_chart');
        var chart = new GoogleCharts.api.visualization.LineChart(el);
        chart.draw(data, options);
    }

}
