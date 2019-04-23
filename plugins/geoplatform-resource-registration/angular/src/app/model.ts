
export const ModelProperties = {
    TYPE:               "type",
    TITLE:              'title',
    LABEL:              'label',
    DESCRIPTION:        "description",
    CREATED_BY:         'createdBy',
    ACCESS_URL:         'href',
    SERVICE_TYPE:       'serviceType',
    RESOURCE_TYPES:     "resourceTypes",
    LANDING_PAGE:       "landingPage",
    DISTRIBUTIONS:      "distributions",
    KEYWORDS:           'keywords',
    THEMES:             'themes',
    PUBLISHERS:         'publishers',
    SUBTOPIC_OF:        'subTopicOf',
    COMMUNITIES:        'usedBy',
    THUMBNAIL:          'thumbnail',
    THUMBNAIL_URL:      'url',
    THUMBNAIL_CONTENT: 'contentData',
    THUMBNAIL_TYPE:     'mediaType',
    CLASSIFIERS:        'classifiers',
    LAYERS:             'layers',
    ASSETS:             'assets',
    TOPICS:             'topics',

    //not actual properties on the model, but are used by internal
    // form groups to represent THUMBNAIL_XXXX properties while being edited
    FORM_THUMBNAIL_URL:     'thumbnailUrl',
    FORM_THUMBNAIL_CONTENT: 'thumbnailContent',

    //not actual property on the model, but used by internal form groups to
    // represent a constraining concept scheme when filtering themes
    THEME_SCHEME :          'themeScheme',


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
    place            : 'Place',
    purpose          : 'Purpose',
    function         : 'Function',
    audience         : 'Audience',
    category         : 'Category',
    community        : 'Community',
    primaryTopic     : 'Topic',
    secondaryTopic   : 'Topic',
    primarySubject   : 'Subject',
    secondarySubject : 'Subject'
};



export class AppError extends Error {

    public label  : string;
    public status : number = 500;

    constructor(message : string, status?:number, label?:string) {
        super(message);
        if(status) this.status = status;
        if(label) this.label = label;
    }

}




export const AppEventTypes = {
    RESET   : "reset",
    AUTH    : "auth",
    CHANGE  : "change"
}

export const StepEventTypes = {
    RESET           : 'app.reset',
    SERVICE_INFO    : 'service.about'
}
