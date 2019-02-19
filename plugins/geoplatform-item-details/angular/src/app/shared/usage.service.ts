import { Injectable } from '@angular/core';
import { curry, compose, _ } from 'rambda'
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
    lable: string
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


//          Functions           //

function getAPIParams(method: APIMethods, period: APIPeriod, date: string, label: string){
    return {
        label,
        method,
        period,
        date,
        // Common fields
        module: 'API',
        auth_token: 'f822e078fcb182110b5de9deeba85e7e',
        format: 'json',
        idSite: 4 // viewer (this will have to be paramerterized)
    }
}

function apiRequestToQueryString(apiParams: MatomoAPIRequest): string {
    const pairs = Object.entries(apiParams)
                        .map(([key, value]) => `${key}=${value}`)
                        .join('&')
    return `?${pairs}`
}

async function getStats(paramString: string) /*: Promise<MatomoAPIResponse>*/ {
    return call(`${this.rpmUrl}${paramString}`) // need to make call here.
}

const getUsageForRange = compose(getStats, apiRequestToQueryString, getAPIParams)

@Injectable()
export class UsageService {

    private rpmUrl: string;
    private auth_token: string;

    constructor(rpmUrl: string, auth_token: string) {


    }

    //        Public API       //
    public async getPastWeekUsage(){
        return this.getUsageForRange('week', 'last7')
    }

    public async getPastMonthUsage() {
        return this.getUsageForRange('week', 'last5')
    }

    public async getPastYearUsage() {
        return this.getUsageForRange('month', 'last12')
    }

}
