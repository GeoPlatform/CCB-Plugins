

import { HttpClient } from '@angular/common/http';

import { ConstraintEditor } from '../models/constraint';

import { Codec } from '../models/codec';
import { TypeCodec } from './type/codec';
import { KeywordCodec } from './keywords/codec';
import { ThemeCodec } from './theme/codec';
import { PublisherCodec } from './publisher/codec';
import { CreatorCodec } from './creator/codec';
import { ExtentCodec } from './extent/codec';
import { TemporalCodec } from './temporal/codec';
import { SemanticCodec } from './semantic/codec';
import { FreeTextCodec } from './freetext.codec';

import { CurrentComponent } from './current/current.component';

import { CreatorComponent } from './creator/creator.component';
import { KeywordsComponent } from './keywords/keywords.component';
import { ContactComponent } from './contact/contact.component';
import { PublisherComponent } from './publisher/publisher.component';
import { ExtentComponent } from './extent/extent.component';
import { TemporalComponent } from './temporal/temporal.component';
import { ThemeComponent } from './theme/theme.component';
import { TypeComponent } from './type/type.component';
import { SemanticComponent } from './semantic/semantic.component';


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

SearchEditorRegistry.registerEditor( "creator", "Creator", CreatorComponent );
SearchEditorRegistry.registerEditor( "extent", "Geographic Extent", ExtentComponent );
SearchEditorRegistry.registerEditor( "keywords", "Keywords", KeywordsComponent );
SearchEditorRegistry.registerEditor( "contacts", "Points of Contact", ContactComponent );
SearchEditorRegistry.registerEditor( "publishers", "Publishers", PublisherComponent );
SearchEditorRegistry.registerEditor( "temporal", "Temporal Extent", TemporalComponent );
SearchEditorRegistry.registerEditor( "themes", "Themes", ThemeComponent );
SearchEditorRegistry.registerEditor( "types", "Types", TypeComponent );
SearchEditorRegistry.registerEditor( "semantic", "Semantic Concepts", SemanticComponent );









/**
 *
 */
class CodecFactory {

    private codecs : [Codec] = [] as [Codec];

    constructor(private http : HttpClient) {
        this.codecs.push(new FreeTextCodec());
        this.codecs.push(new TypeCodec());
        this.codecs.push(new KeywordCodec());
        this.codecs.push(new ThemeCodec(http));
        this.codecs.push(new PublisherCodec(http));
        this.codecs.push(new CreatorCodec());
        this.codecs.push(new ExtentCodec());
        this.codecs.push(new TemporalCodec());
        this.codecs.push(new SemanticCodec());
    }

    get () : Codec {
        return null;
    }

    list () : [Codec] {
        return this.codecs;
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
    ExtentComponent,
    TemporalComponent,
    ThemeComponent,
    TypeComponent,
    SemanticComponent,
    FreeTextCodec,
    TypeCodec,
    KeywordCodec,
    ThemeCodec,
    PublisherCodec,
    CreatorCodec,
    ExtentCodec,
    TemporalCodec,
    SemanticCodec
}
