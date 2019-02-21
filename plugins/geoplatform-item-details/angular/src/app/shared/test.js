const R = require('ramda')

const apiResponse = {
    "1": {
        "2019-02-14": [],
        "2019-02-15": [],
        "2019-02-16": [],
        "2019-02-17": [],
        "2019-02-18": [],
        "2019-02-19": [],
        "2019-02-20": []
    },
    "2": {
        "2019-02-14": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 1,
                "nb_visits": 1,
                "nb_events": 1,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 4
            }
        ],
        "2019-02-15": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 2,
                "nb_visits": 4,
                "nb_events": 8,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 1
            }
        ],
        "2019-02-16": [],
        "2019-02-17": [],
        "2019-02-18": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 1,
                "nb_visits": 2,
                "nb_events": 2,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 2
            }
        ],
        "2019-02-19": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 1,
                "nb_visits": 1,
                "nb_events": 1,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 4
            }
        ],
        "2019-02-20": []
    },
    "3": {
        "2019-02-14": [],
        "2019-02-15": [],
        "2019-02-16": [],
        "2019-02-17": [],
        "2019-02-18": [],
        "2019-02-19": [],
        "2019-02-20": []
    },
    "4": {
        "2019-02-14": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 2,
                "nb_visits": 2,
                "nb_events": 4,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 8
            }
        ],
        "2019-02-15": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 1,
                "nb_visits": 1,
                "nb_events": 2,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 5
            }
        ],
        "2019-02-16": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 2,
                "nb_visits": 3,
                "nb_events": 8,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 1
            }
        ],
        "2019-02-17": [],
        "2019-02-18": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 2,
                "nb_visits": 2,
                "nb_events": 4,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 4
            }
        ],
        "2019-02-19": [
            {
                "label": "be6da1549cd91087542a5a9e3da5fb47",
                "nb_uniq_visitors": 2,
                "nb_visits": 2,
                "nb_events": 10,
                "nb_events_with_value": 0,
                "sum_event_value": 0,
                "min_event_value": 0,
                "max_event_value": 0,
                "avg_event_value": 0,
                "segment": "eventName==be6da1549cd91087542a5a9e3da5fb47",
                "idsubdatatable": 4
            }
        ],
        "2019-02-20": []
    },
    "6": {
        "2019-02-14": [],
        "2019-02-15": [],
        "2019-02-16": [],
        "2019-02-17": [],
        "2019-02-18": [],
        "2019-02-19": [],
        "2019-02-20": []
    },
    "7": {
        "2019-02-14": [],
        "2019-02-15": [],
        "2019-02-16": [],
        "2019-02-17": [],
        "2019-02-18": [],
        "2019-02-19": [],
        "2019-02-20": []
    }
}

function multiSiteResponseToSingleSiteResponse(apiResponse) {
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
    const mergeField = (key, a, b) => ~sumFields.indexOf(key) ? a + b : ( a > b ? a : b);

    const siteRecords = Object.values(apiResponse);
    return siteRecords.reduce((acc, v) => {
        return R.mergeWithKey(mergeField, acc, v)
    },{})
}


console.log(multiSiteResponseToSingleSiteResponse(apiResponse))
console.log("</>")


const expected ={
    '2019-02-14': [ { label: 'be6da1549cd91087542a5a9e3da5fb47',
       nb_uniq_visitors: 3,
       nb_visits: 3,
       nb_events: 5,
       nb_events_with_value: 0,
       sum_event_value: 0,
       min_event_value: 0,
       max_event_value: 0,
       avg_event_value: 0,
       segment: 'eventName==be6da1549cd91087542a5a9e3da5fb47',
       idsubdatatable: 8 } ],
  '2019-02-15': [ { label: 'be6da1549cd91087542a5a9e3da5fb47',
       nb_uniq_visitors: 3,
       nb_visits: 5,
       nb_events: 10,
       nb_events_with_value: 0,
       sum_event_value: 0,
       min_event_value: 0,
       max_event_value: 0,
       avg_event_value: 0,
       segment: 'eventName==be6da1549cd91087542a5a9e3da5fb47',
       idsubdatatable: 5 } ],
  '2019-02-16': [ { label: 'be6da1549cd91087542a5a9e3da5fb47',
       nb_uniq_visitors: 2,
       nb_visits: 3,
       nb_events: 8,
       nb_events_with_value: 0,
       sum_event_value: 0,
       min_event_value: 0,
       max_event_value: 0,
       avg_event_value: 0,
       segment: 'eventName==be6da1549cd91087542a5a9e3da5fb47',
       idsubdatatable: 1 } ],
  '2019-02-17': [],
  '2019-02-18': [ { label: 'be6da1549cd91087542a5a9e3da5fb47',
       nb_uniq_visitors: 3,
       nb_visits: 4,
       nb_events: 6,
       nb_events_with_value: 0,
       sum_event_value: 0,
       min_event_value: 0,
       max_event_value: 0,
       avg_event_value: 0,
       segment: 'eventName==be6da1549cd91087542a5a9e3da5fb47',
       idsubdatatable: 4 } ],
  '2019-02-19': [ { label: 'be6da1549cd91087542a5a9e3da5fb47',
       nb_uniq_visitors: 3,
       nb_visits: 3,
       nb_events: 11,
       nb_events_with_value: 0,
       sum_event_value: 0,
       min_event_value: 0,
       max_event_value: 0,
       avg_event_value: 0,
       segment: 'eventName==be6da1549cd91087542a5a9e3da5fb47',
       idsubdatatable: 4 } ],
  '2019-02-20': [] }