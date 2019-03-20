

import { HttpClient } from '@angular/common/http';

import { ConstraintEditor } from '../models/constraint';

import { Codec } from '../models/codec';
import { TypeCodec } from './type/codec';
import { KeywordCodec } from './keywords/codec';
import { ThemeCodec } from './theme/codec';
import { PublisherCodec } from './publisher/codec';
import { CommunityCodec } from './community/codec';
import { CreatorCodec } from './creator/codec';
import { ExtentCodec } from './extent/codec';
import { TemporalCodec } from './temporal/codec';
import { SemanticCodec } from './semantic/codec';
import { SimilarityCodec } from './similarity/codec';
import { FreeTextCodec } from './freetext.codec';

import { CurrentComponent } from './current/current.component';

import { CreatorComponent } from './creator/creator.component';
import { KeywordsComponent } from './keywords/keywords.component';
import { ContactComponent } from './contact/contact.component';
import { PublisherComponent } from './publisher/publisher.component';
import { CommunityComponent } from './community/community.component';
import { ExtentComponent } from './extent/extent.component';
import { TemporalComponent } from './temporal/temporal.component';
import { ThemeComponent } from './theme/theme.component';
import { TypeComponent } from './type/type.component';
import { SemanticComponent } from './semantic/semantic.component';
import { SimilarityComponent } from './similarity/similarity.component';


class EditorRegistry {

    private editors: {key:string, label:string, component:any}[] = [];

    registerEditor(key:string, label:string, component:any) {
        this.editors.push({key:key, label:label, component:component});
    }

    getEditors() : {key:string, label:string, component:any}[] {
        return this.editors.slice(0);
    }
}


let SearchEditorRegistry = new EditorRegistry();

// SearchEditorRegistry.registerEditor( "creator", "Creator", CreatorComponent );
SearchEditorRegistry.registerEditor( "extent", "Geographic Extent", ExtentComponent );
SearchEditorRegistry.registerEditor( "keywords", "Keywords", KeywordsComponent );
// SearchEditorRegistry.registerEditor( "contacts", "Points of Contact", ContactComponent );
SearchEditorRegistry.registerEditor( "publishers", "Publishers", PublisherComponent );
SearchEditorRegistry.registerEditor( "communities", "Communities", CommunityComponent );
SearchEditorRegistry.registerEditor( "temporal", "Date Range", TemporalComponent );
SearchEditorRegistry.registerEditor( "themes", "Themes", ThemeComponent );
SearchEditorRegistry.registerEditor( "types", "Types", TypeComponent );
SearchEditorRegistry.registerEditor( "semantic", "Semantic Concepts", SemanticComponent );









/**
 *
 */
class CodecFactory {

    private codecs : {key:string,value:Codec} = {} as { key: string; value: Codec; };

    constructor(private http : HttpClient) {
        this.registerCodec(new FreeTextCodec());
        this.registerCodec(new TypeCodec());
        this.registerCodec(new KeywordCodec());
        this.registerCodec(new ThemeCodec(http));
        this.registerCodec(new PublisherCodec(http));
        this.registerCodec(new CommunityCodec(http));
        this.registerCodec(new CreatorCodec());
        this.registerCodec(new ExtentCodec());
        this.registerCodec(new TemporalCodec());
        this.registerCodec(new SemanticCodec(http));
        this.registerCodec(new SimilarityCodec(http));
    }

    registerCodec(codec : Codec) {
        let key = codec.getKey();
        this.codecs[key] = codec;
    }

    get (key : string) : Codec {
        if(this.codecs[key])
            return this.codecs[key];
        return null;
    }

    list () : [Codec] {
        return Object.keys(this.codecs).map(k=>this.codecs[k]) as [Codec];
    }

};





export {
    CodecFactory,
    CurrentComponent,
    SearchEditorRegistry as EditorRegistry,
    CreatorComponent,
    KeywordsComponent,
    ContactComponent,
    PublisherComponent,
    CommunityComponent,
    ExtentComponent,
    TemporalComponent,
    ThemeComponent,
    TypeComponent,
    SemanticComponent,
    SimilarityComponent,
    FreeTextCodec,
    TypeCodec,
    KeywordCodec,
    ThemeCodec,
    PublisherCodec,
    CommunityCodec,
    CreatorCodec,
    ExtentCodec,
    TemporalCodec,
    SemanticCodec,
    SimilarityCodec
}
