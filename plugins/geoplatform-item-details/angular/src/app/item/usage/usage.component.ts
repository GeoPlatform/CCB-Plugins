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

type ChartPeriods = 'week'
                  | 'month'
                  | 'year'

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
    public activeUsageChart: ChartPeriods = 'week';

    // State for each chart
    public weeklyChartState: ChartSettings;
    public monthlyChartState: ChartSettings;
    public yearlyChartState: ChartSettings;

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
        this.charts[tabName].fn();

        // Do we really need any of this below?
        this.tabs[this.activeTab].status = false;
        this.tabs[tabName].status = true;
        this.activeTab = tabName;
    }

    initCharts( rebuild:boolean = false ) {}

    fetchAndDrawlChart(period?: ChartPeriods){
        this.activeUsageChart = period;

        let func;
        let dateFormat;
        let chartState;
        let loaded;
        switch(period) {
            case 'month':
                loaded = !!this.weeklyChartState;
                func = 'getPastMonthUsage';
                dateFormat = '';
                break;
            case 'year':
                loaded = !!this.monthlyChartState;
                func = 'getPastYearUsage';
                dateFormat = '';
                break;
            default: // 'week' and default
                loaded = !!this.yearlyChartState;
                func = 'getPastWeekUsage';
                dateFormat = '';
                break;
        }

        if(!loaded){
            this.usageService[func](this.itemId)
            .subscribe(data => {
                const chartData = this.usageService.matomoRespToDataset(dateFormat ,data)
                const chartState = this.getChartSettings(chartData)

                switch(period) {
                    case 'month':
                        this.monthlyChartState = chartState;
                        break;
                    case 'year':
                        this.yearlyChartState = chartState;
                        break;
                    default: // 'week' and default
                        this.weeklyChartState = chartState;
                        break;
                }
            });
        }

    }
    /**
     * Sets the stae of the chart
     */
    getChartSettings(chartData: ChartData): ChartSettings {
        return {
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
    }

}
