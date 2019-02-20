import {
    Component, OnInit, OnChanges, SimpleChanges, Input, ViewChild
} from '@angular/core';
import { UsageService } from '../../shared/usage.service';
import { BaseChartDirective } from 'ng2-charts//ng2-charts'

// All are RGBA or HEX
type ChartColor = {
    backgroundColor: string
    borderColor: string
    pointBackgroundColor?: string,
    pointBorderColor?: string
    pointHoverBackgroundColor?: string
    pointHoverBorderColor?: string
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
            this.fetchAndDrawlChart('week');
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

    fetchAndDrawlChart(period?: ChartPeriods){
        this.activeUsageChart = period;

        let func;
        let chartStateName;
        switch(period) {
            case 'month':
                chartStateName = 'monthlyChartState';
                func = 'getPastMonthUsage';
                break;
            case 'year':
                chartStateName = 'yearlyChartState';
                func = 'getPastYearUsage';
                break;
            default: // 'week' and default
                chartStateName = 'weeklyChartState';
                func = 'getPastWeekUsage';
                break;
        }

        let loaded = !!this[chartStateName];
        if(!loaded){
            this.usageService[func](this.itemId)
            .subscribe(data => {
                const chartData = this.usageService.matomoRespToDataset(period ,data)
                this[chartStateName] = this.getChartSettings(chartData);
            });
            if(this.usageChart)
                this.usageChart.chart.update();
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
                // 172	214	246	blue back
                // 47	152	232	blue outline
                { // GeoPlatform Green
                  backgroundColor: 'rgba(92, 184, 92, 0.2)',
                  borderColor: 'rgba(51, 153, 51, 1)',
                  pointBackgroundColor: 'rgba(7, 119, 7,1)',
                  pointBorderColor: 'rgb(7, 119, 7)',
                },
                { // GeoPlatform Blue
                  backgroundColor: 'rgba(91, 192, 222,0.2)',
                  borderColor: 'rgba(66, 139, 202,1)',
                  pointBackgroundColor: 'rgba(17,33,88,1)',
                  pointBorderColor: 'rgb(17,33,88)',
                }
            ],
        };
    }

}
