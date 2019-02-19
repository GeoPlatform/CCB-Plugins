import {
    Component, OnInit, OnChanges, SimpleChanges, Input, ViewChild
} from '@angular/core';
import { UsageService } from '../../shared/usage.service';
import { BaseChartDirective } from 'ng2-charts//ng2-charts'

const MONTHS = [
    'Jan','Feb','Mar','Apr','May','Jun',
    'Jul','Aug','Sep','Oct','Nov','Dec'
];
const DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

// All are RGBA or HEX
type ChartColor = {
    backgroundColor: string
    borderColor: string
    pointBackgroundColor: string,
    pointBorderColor: string
    pointHoverBackgroundColor: string
    pointHoverBorderColor: string
}

type ChartData = {
    datasets: {data: number[], label: string}[]
    labels: string[]
}

export type ChartSettings = ChartData & {
    options?: any
    colors: ChartColor[]
    legend?: boolean
    chartType: 'line' | 'bar'
}

@Component({
  selector: 'gpid-usage',
  templateUrl: './usage.component.html',
  styleUrls: ['./usage.component.less']
})
export class UsageComponent implements OnInit {

    @Input() item : any;

    @ViewChild(BaseChartDirective)
    private usageChart: BaseChartDirective;

    public isCollapsed : boolean = false;
    public chartState: ChartSettings;
    public activeTab : string = 'usage_weekly';
    private tabs : any  = {
        usage_weekly : { status: true, label: "Past Week" },
        usage_monthly : { status: false, label: "Past Month" },
        usage_yearly : { status: false, label: "Past Year" }
    };
    private charts : any = {
        usage_weekly : { status: false, fn: () => {this.fetchAndDrawlChart('week')}  },
        usage_monthly : { status: false, fn: () => {this.fetchAndDrawlChart('month')} },
        usage_yearly : { status: false, fn: () => {this.fetchAndDrawlChart('year')} }
    };
    private itemId : string;


    constructor(private usageService: UsageService) {}

    ngOnInit() {}

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.item && changes.item.currentValue) {
            this.itemId = changes.item.currentValue.id;
            this.fetchAndDrawlChart();
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

    initCharts( rebuild:boolean = false ) {}

    fetchAndDrawlChart(period?: 'week' | 'month' | 'year'){
        let func;
        switch(period) {
            case 'week':
                func = 'getPastWeekUsage';
                break;
            case 'month':
                func = 'getPastMonthUsage';
                break;
            case 'year':
                func = 'getPastYearUsage';
                break;
            default:
                func = 'getPastWeekUsage';
        }

        if(func){
            this.usageService[func](this.itemId)
            .subscribe(data => {
                this.drawlChart(this.usageService.matomoRespToDataset('',data))
            });
        }
    }
    /**
     * Sets the stae of the chart
     */
    drawlChart(chartData: ChartData){
        const updated: ChartSettings = {
            // TO pass in
            datasets: chartData.datasets,
            labels: chartData.labels,
            // Static
            legend: true,
            chartType: 'line',
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true   // minimum value will be 0.
                        }
                    }]
                }
            },
            // Things that make it pretty
            colors: [
                { // grey
                  backgroundColor: 'rgba(148,159,177,0.2)',
                  borderColor: 'rgba(148,159,177,1)',
                  pointBackgroundColor: 'rgba(148,159,177,1)',
                  pointBorderColor: '#fff',
                  pointHoverBackgroundColor: '#fff',
                  pointHoverBorderColor: 'rgba(148,159,177,0.8)'
                },
                { // dark grey
                  backgroundColor: 'rgba(77,83,96,0.2)',
                  borderColor: 'rgba(77,83,96,1)',
                  pointBackgroundColor: 'rgba(77,83,96,1)',
                  pointBorderColor: '#fff',
                  pointHoverBackgroundColor: '#fff',
                  pointHoverBorderColor: 'rgba(77,83,96,1)'
                },
                { // grey
                  backgroundColor: 'rgba(148,159,177,0.2)',
                  borderColor: 'rgba(148,159,177,1)',
                  pointBackgroundColor: 'rgba(148,159,177,1)',
                  pointBorderColor: '#fff',
                  pointHoverBackgroundColor: '#fff',
                  pointHoverBorderColor: 'rgba(148,159,177,0.8)'
                }
            ],
        };

        this.chartState = updated;
        if(this.usageChart){
            this.usageChart.chart.config.data = updated;
            this.usageChart.chart.update();
        }
    }


}
