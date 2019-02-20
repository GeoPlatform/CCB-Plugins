import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { HttpClient, HttpRequest } from '@angular/common/http';
import { GoogleCharts } from 'google-charts';
import { Config } from 'geoplatform.client';

import { environment } from '../../../environments/environment';
import { NG2HttpClient } from '../../shared/http-client';
import { ItemDetailsError } from '../../shared/item-details-error';


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
    public svcStatsData : any;
    public isFetchingData : boolean = true;
    public error : ItemDetailsError;

    private googleIsLoaded : boolean = false;
    private googleWaitAttempts : number = 0;
    private httpClient : NG2HttpClient;

    constructor(http : HttpClient) {
        this.httpClient = new NG2HttpClient(http, {
            timeout: Config.timeout || 60000
        })
    }

    ngOnInit() {
        //Load the charts library
        GoogleCharts.load(() => { this.googleIsLoaded = true; });
    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.item && changes.item.currentValue) {

            let itemId = changes.item.currentValue.id;

            //TODO fetch Service stats...
            this.getServiceHistory()
            .then( data => {
                this.svcStatsData = data;
                this.initChart(true);
            })
            .catch(e => {
                this.isFetchingData = false;
                //display error message in place of charts
                console.log("ServiceStats.ngOnChanges() - Unable to fetch service history: " + e.message);
                let msg = "An error occurred attempting to fetch service performance history";
                this.error = new ItemDetailsError(msg, 500);
                this.error.label = "Unable to Fetch Service History";
            });
        }
    }

    toggleCollapsed () {
        this.isCollapsed = !this.isCollapsed;
    }


    initChart( rebuild:boolean = false ) {
        if(!this.googleIsLoaded) {
            if(this.googleWaitAttempts < 5) {
                //wait just a bit more for google charts api to finish loading
                setTimeout( () => { this.initChart(rebuild); }, 1000);
            } else {
                //display error message that google api could not load...
            }

            return;
        }

        //draw whichever chart is currently visible
        setTimeout( () => {
            // this.drawWeeklyChart();
        }, 100);
    }



    drawWeeklyChart() {

        var data = new GoogleCharts.api.visualization.DataTable();
        data.addColumn('string', 'Date'); // Implicit domain label col.
        data.addColumn('number', 'Score'); // Implicit series 1 data col.
        data.addColumn('number', 'Speed'); // Implicit series 1 data col.


        let rows = [], max = 0;

        (this.svcStatsData || []).forEach( (point,i) => {
            // compliant: true
            // d: "2019-01-22T00:00:00.000Z"
            // online: true
            // score: 99.05
            // speed: 1.22
            // timestamp: "Jan 22, 2019"

            let score = Math.max(point.score, 0);
            let speed = point.speed;
            if(speed < 0) speed = 100;

            rows.push( [ point.timestamp, score, speed] );
        });

        data.addRows(rows);

        let options = {
            height: 300,
            series: { 0: { targetAxisIndex: 0 }, 1: { targetAxisIndex: 1 } },
            hAxis: {
                title: 'Date',
                format: 'M/d/yy',
                minValue: rows[0][0],
                maxValue: rows[rows.length-1][0]
            },
            vAxis: {
                0: { title: 'Score', minValue: 0, maxValue: 100 },
                1: { title: 'Speed', minValue: 0, maxValue: 100 },
                ticks: [0, 20, 40, 60, 80, 100]
            }
        };

        // this.isFetchingData = false;

        let el = document.getElementById('stats_chart');
        var chart = new GoogleCharts.api.visualization.LineChart(el);
        chart.draw(data, options);
    }



    /**
     * @return {Promise} containing historical data or empty array or rejecting with error
     */
    getServiceHistory() : Promise<any> {


        let id = this.item.id;
        if(this.item.identifiers && this.item.identifiers.length) {
            let ngpIds = this.item.identifiers.filter( ident => ident.indexOf("ngp:")===0);
            if(ngpIds && ngpIds.length) {
                id = ngpIds[0].split(':')[1];
            } else {
                return Promise.reject(
                    new Error("Service has no statistics identifier to use to retrieve performance history")
                );
            }
        }

        let url = environment.svcHistoryUrl;
        if(!url) {
            return Promise.reject(
                new Error("URL to fetch service status history is not configured")
            );
        }

        url = url.replace(/\{id\}/, id);

        //https://sit-dashboard.geoplatform.us/api/sd/service/.../history?days=6&rollupByDay=true&stub=28
        let option = {
            method: "GET",
            responseType: 'json',
            url: url,
            params: {
                days: 6,
                rollupByDay: true,
                stub: 28
            }
        };
        let request : HttpRequest<any> = this.httpClient.createRequestOpts(option);
        return this.httpClient.execute(request);

    }


}
