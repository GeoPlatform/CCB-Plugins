import { Injectable } from '@angular/core';
import { curry, mergeWithKey } from 'ramda'
import { HttpClient } from '@angular/common/http';

const MONTHS = [
    'Jan','Feb','Mar','Apr','May','Jun',
    'Jul','Aug','Sep','Oct','Nov','Dec'
];
const DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

type DateFormats = 'week' | 'month' | 'year'

type ChartData = {
    labels: string[]
    datasets: {data: number[], label: string}[]
}

/**
 * Desired outputs:
 *  week: Thu (2/15)
 *  month: 4 (day of month)
 *  year: Jan (month only)
 *
 * @param iso
 */
function matomoISOtoPrettyDate(iso: string, format: DateFormats): string {
    const [year, month, day] = iso.split('-')
                                  .map(s => parseInt(s))
    // Proof that a world built on JS is doomed to destruction
    // Note: The argument monthIndex is 0-based. This means that January = 0 and December = 11.
    const date = new Date(year, month-1, day || 1);
    switch (format) {
        case 'week':
            return `${DAYS[date.getDay()]} (${date.getMonth() + 1}/${day})`
        case 'month':
            return ` ${MONTHS[date.getMonth()]} ${day}`
        case 'year':
            return MONTHS[date.getMonth()]
        default:
            return iso;
    }
}


@Injectable()
export class RPMStatsService {

    private rpmUrl: string;
    private usageRequest: any;

    constructor(rpmUrl: string, token_auth: string, private http: HttpClient) {
        this.rpmUrl = rpmUrl;
        this.usageRequest = this.getAPIParams(token_auth, 'Events.getName')
    }

    //        Public API       //
    public getPastWeekUsage(id: string){
        return this.getUsageForRange(this.usageRequest('day', 'previous7', id))
    }

    public getPastMonthUsage(id: string) {
        return this.getUsageForRange(this.usageRequest('day', 'previous30', id))
    }

    public getPastYearUsage(id: string) {
        return this.getUsageForRange(this.usageRequest('month', 'last12', id))
    }

    public multiSiteResponseToSingleSiteResponse(apiResponse: MatomoMultiSiteAPIReponse): MatomoSingleSiteAPIResponse {
        // Fields that we are ok summing together...
        const sumFields = [
            'nb_uniq_visitors', // Really questionable...
            'nb_visits',
            'nb_events',
            'nb_events_with_value',
            // 'sum_event_value',
            // 'min_event_value',
            // 'max_event_value',
            'sum_daily_nb_uniq_visitors',
            // 'avg_event_value'
        ]
        const mergeField = (key: string, a: number, b: number) => ~sumFields.indexOf(key) ? a + b : ( a > b ? a : b);

        const siteRecords = Object.values(apiResponse);
        return siteRecords.reduce((acc, v) => {
            return mergeWithKey(mergeField, acc, v)
        },{})
    }

    /**
     * Desired output:
     * [
     *  ["Wed", 5, "5"]
     * ]
     * @param matomoResp
     */
    public matomoRespToDataset(dateFormat: DateFormats, matomoResp: MatomoSingleSiteAPIResponse): ChartData {
        const values = Object.values(matomoResp)
                        .map(d => {
                            // Standardize all records with the data we want
                            const data = d[0]
                            return {
                                        nb_uniq_visitors: data ? data.nb_uniq_visitors : 0,
                                        nb_events: data ? data.nb_events : 0
                                    }
                        })
                        .reduce((acc, entry) => {
                            return {
                                unique: acc.unique.concat([entry.nb_uniq_visitors]),
                                events: acc.events.concat([entry.nb_events])
                            }
                        }, { unique: [], events: [] });

        return {
            labels: Object.keys(matomoResp)
                          .map(d => matomoISOtoPrettyDate(d, dateFormat)),
            datasets: [
                { label: 'Unique Users', data: values.unique },
                { label: 'Total Usage', data: values.events}
            ]
        }
    }


    //          Functions           //

    private getAPIParams = curry(function(token_auth: string, method: APIMethods, period: APIPeriod, date: string, label: string): MatomoAPIRequest{
        return {
            label,
            method,
            period,
            date,
            token_auth,
            // Common fields
            module: 'API',
            format: 'json',
            idSite: 'all' // viewer (this will have to be paramerterized)
        }
    })

    private apiRequestToQueryString(apiParams: MatomoAPIRequest): string {
        const pairs = Object.entries(apiParams)
                            .map(([key, value]) => `${key}=${value}`)
                            .join('&')
        return `?${pairs}`
    }

    private getStats(paramString: string) {
        return this.http.get<MatomoSingleSiteAPIResponse>(`${this.rpmUrl}${paramString}`)
    }

    // private getUsageForRange = compose(this.getStats, this.apiRequestToQueryString);
    private getUsageForRange(params: MatomoAPIRequest){
        return this.getStats(this.apiRequestToQueryString(params))
    }

}
