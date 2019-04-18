
export const ModelProperties = {
    TYPE:           "type",
    TITLE:          'title',
    LABEL:          'label',
    DESCRIPTION:    "description",
    CREATED_BY:     'createdBy',
    ACCESS_URL:     'href',
    SERVICE_TYPE:   'serviceType',
    RESOURCE_TYPES: "resourceTypes",
    LANDING_PAGE:   "landingPage",
    DISTRIBUTIONS:  "distributions",
    KEYWORDS:       'keywords',
    PUBLISHERS:     'publishers',
    COMMUNITIES:    'usedBy',

    //not actual properties on the model, but
    // they map to 'thumbnail.url' and 'thumbnail.contentData'
    // which are in the model
    THUMBNAIL_URL:  'thumbnailUrl',
    THUMBNAIL_CONTENT: 'thumbnailContent',

    CLASSIFIERS_PURPOSE             : 'purpose',
    CLASSIFIERS_FUNCTION            : 'function',
    CLASSIFIERS_TOPIC_PRIMARY       : 'primaryTopic',
    CLASSIFIERS_TOPIC_SECONDARY     : 'secondaryTopic',
    CLASSIFIERS_SUBJECT_PRIMARY     : 'primarySubject',
    CLASSIFIERS_SUBJECT_SECONDARY   : 'secondarySubject',
    CLASSIFIERS_COMMUNITY           : 'community',
    CLASSIFIERS_AUDIENCE            : 'audience',
    CLASSIFIERS_PLACE               : 'place',
    CLASSIFIERS_CATEGORY            : 'category'
};


export const ClassifierTypes = {
    purpose:    'Purpose',
    function:   'Function',
    primaryTopic: 'Topic',
    secondaryTopic: 'Topic',
    primarySubject: 'Subject',
    secondarySubject: 'Subject',
    community:  'Community',
    audience: 'Audience',
    place: 'Place',
    category: 'Category'
};



export class AppError extends Error {

    public label : string;
    public status : number = 500;

    constructor(message : string, status?:number, label?:string) {
        super(message);
        if(status) this.status = status;
        if(label) this.label = label;
    }

}




export const AppEventTypes = {
    RESET   : "reset",
    AUTH    : "auth"
}


export const StepEventTypes = {
    RESET           : 'app.reset',
    SERVICE_INFO    : 'service.about'
}
