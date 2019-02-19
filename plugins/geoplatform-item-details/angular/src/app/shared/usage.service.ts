import { Injectable } from '@angular/core';
import { curry, compose } from 'rambda'
import { HttpClient } from '@angular/common/http'
import { Observable, Subject } from 'rxjs';


                // Events
type APIMethods = 'Events.getName'
                | 'Events.getCategory'
                | 'Events.getAction'

type APIPeriod = 'day'
               | 'week'
               | 'month'
               | 'year'
               | 'range'

type MatomoAPIRequest = {
    label: string
    method: APIMethods
    period: APIPeriod
    date: string // TODO: limit better

    module: 'API'
    idSite: number
    token_auth: 'f822e078fcb182110b5de9deeba85e7e'
    format: 'json'
}

type MatomoAPIResponse = {
    [x: string]: [{
        label: string
        nb_visits: number,
        nb_events: number,
        nb_events_with_value: number,
        sum_event_value: number,
        min_event_value: number,
        max_event_value: number,
        sum_daily_nb_uniq_visitors: number,
        avg_event_value: number,
        segment: string,
        idsubdatatable: number
    }]
}




@Injectable()
export class UsageService {

    private http: HttpClient;
    private rpmUrl: string;
    private auth_token: string;

    constructor(http: HttpClient, rpmUrl: string, auth_token: string) {
        this.http = http;
        this.rpmUrl = rpmUrl;
        this.auth_token = auth_token;
    }

    //        Public API       //
    public async getPastWeekUsage(id: string){
        return this.getUsageForRange(this.usageRequest('week', 'last7', id))
    }

    public async getPastMonthUsage(id: string) {
        return this.getUsageForRange(this.usageRequest('week', 'last5', id))
    }

    public async getPastYearUsage(id: string) {
        return this.getUsageForRange(this.usageRequest('month', 'last12', id))
    }


    //          Functions           //

    private getAPIParams = curry(function(method: APIMethods, period: APIPeriod, date: string, label: string): MatomoAPIRequest{
        return {
            label,
            method,
            period,
            date,
            // Common fields
            module: 'API',
            token_auth: this.auth_token,
            format: 'json',
            idSite: 4 // viewer (this will have to be paramerterized)
        }
    })

    private apiRequestToQueryString(apiParams: MatomoAPIRequest): string {
        const pairs = Object.entries(apiParams)
                            .map(([key, value]) => `${key}=${value}`)
                            .join('&')
        return `?${pairs}`
    }

    private async getStats(paramString: string) /*: Promise<MatomoAPIResponse>*/ {
        return this.http.get<MatomoAPIResponse>(`${this.rpmUrl}${paramString}`)
    }

    private usageRequest = this.getAPIParams('Events.getName')

    private getUsageForRange = compose(this.getStats, this.apiRequestToQueryString);

}
