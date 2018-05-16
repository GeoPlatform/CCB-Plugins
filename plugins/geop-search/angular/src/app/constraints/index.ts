
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



export {
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
    SemanticComponent
}
