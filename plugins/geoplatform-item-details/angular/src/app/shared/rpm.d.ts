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
    idSite: number | 'all'
    token_auth: string
    format: 'json'
}

type MatomoSiteAPIData = {
    label: string
    nb_uniq_visitors: number
    nb_visits: number
    nb_events: number
    nb_events_with_value: number
    sum_event_value: number
    min_event_value: number
    max_event_value: number
    avg_event_value: number
    segment: string
    idsubdatatable: number
    // Only on responses with periond = 'range'
    sum_daily_nb_uniq_visitors?: number
}

type MatomoSingleSiteAPIResponse = {
    [isoDate: string]: MatomoSiteAPIData[]
}

type MatomoMultiSiteAPIReponse = {
    [siteId: string]: {
        [isoDate: string]: MatomoSiteAPIData[]
    }
}