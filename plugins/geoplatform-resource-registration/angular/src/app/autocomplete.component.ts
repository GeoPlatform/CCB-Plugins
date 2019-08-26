
import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, ViewChild, ElementRef, SimpleChanges,
    ContentChild, TemplateRef
} from '@angular/core';
import { FormGroup } from '@angular/forms';
import {
    MatAutocompleteSelectedEvent,
    MatAutocompleteTrigger,
    MatChipInputEvent,
    MatAutocomplete
} from '@angular/material';
import { Observable, Subject, of } from 'rxjs';
import {map, flatMap, startWith} from 'rxjs/operators';

/**
 * Custom component which wraps a Material Form Field containing
 * a Material Autocomplete with Material Chip List support. This class
 * uses a passed-in filtering function for specifying selectable options based
 * upon user input.
 *
 *  Example:
 *  <form [formGroup]="formGroup">
 *     <gprr-autocomplete-mat-chip
 *        [label]="'Field Label'"
 *        [placeholder]="'Field placeholder string'"
 *        [hint]="'Field hint text'"
 *        [fieldName]="'fieldName'"
 *        [formGroup]="formGroup"
 *        [filterFn]="myFilteringFn">
 *     </gprr-autocomplete-mat-chip>
 *  </form>
 */
@Component({
  selector: 'gprr-autocomplete-mat-chip',
  template: `
  <div *ngIf="hiddenFieldName">
      <mat-form-field floatLabel="always" [formGroup]="formGroup">
          <mat-label>
              <mat-icon *ngIf="icon" fontSet="{{iconFamily||'gp'}}" fontIcon="{{icon}}"></mat-icon>
              {{label}}
          </mat-label>

          <mat-chip-list #valChipList>
              <mat-chip
                  *ngFor="let val of values"
                  [selectable]="true"
                  [removable]="true"
                  (removed)="removeValue(val)">
                  {{val.label}}
                  <mat-icon matChipRemove>cancel</mat-icon>
              </mat-chip>
              <input matInput #acmcInput
                  formControlName="{{hiddenFieldName}}"
                  [matAutocomplete]="acmcAutoComplete"
                  [matChipInputFor]="valChipList"
                  (matChipInputTokenEnd)="addValue($event)"
                  placeholder="{{placeholder}}">
              <mat-icon matSuffix
                    *ngIf="formGroup.contains(hiddenFieldName)&&formGroup.get(hiddenFieldName).value?.length"
                    (click)="clearInput()">
                    close
              </mat-icon>
          </mat-chip-list>
          <mat-autocomplete #acmcAutoComplete="matAutocomplete"
              (optionSelected)="onAutocompleteSelection($event)">
              <mat-option *ngIf="!formGroup.contains(hiddenFieldName) || ( formGroup.get(hiddenFieldName).value?.length && !(filteredOptions | async)?.length ) ">No matches found</mat-option>
              <mat-option *ngFor="let option of filteredOptions | async" [value]="option">
                  <span *ngIf="!templateRef">{{ option.label }}</span>
                  <ng-container *ngIf="templateRef">
                    <ng-template  [ngTemplateOutlet]="templateRef"
                                  [ngTemplateOutletContext]="{$implicit: option}">
                    </ng-template>
                  </ng-container>
              </mat-option>
          </mat-autocomplete>
          <mat-hint>{{hint}}</mat-hint>
      </mat-form-field>
  </div>
  `,
  styleUrls: ['./app.component.less']
})
export class AutocompleteMatChipComponent implements OnInit, OnDestroy {

    @Input() label : string;
    @Input() fieldName : string;
    @Input() formGroup : FormGroup;
    @Input() filterFn : (value: string) => Promise<string[]>;
    @Input() placeholder : string;
    @Input() hint : string;
    @Input() iconFamily : string;
    @Input() icon : string;

    public hiddenFieldName : string;
    public filteredOptions: Observable<string[]>;

    @ViewChild('acmcAutoComplete', {static:false}) matAutoComplete: MatAutocomplete;
    @ViewChild('acmcInput', { read: MatAutocompleteTrigger,static:false }) acTrigger: MatAutocompleteTrigger;
    @ViewChild('acmcInput', {static:false}) field: ElementRef;

    //for allowing custom mat-option templates to be provided
    // as content to this component's element
    @ContentChild(TemplateRef, {static:false}) templateRef;

    constructor() { }

    ngOnInit() {
        let field = this.formGroup.get('$'+this.fieldName);
        if(!field) {
            console.log(`Warning: field named '${this.fieldName}' not found in form`);
            this.filteredOptions = of([]);
            return;
        }
        this.filteredOptions = field.valueChanges.pipe(
            flatMap( value => this.filterValues(value) )
        );
    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.fieldName && changes.fieldName.currentValue) {
            this.hiddenFieldName = '$' + changes.fieldName.currentValue;
        }
    }

    ngOnDestroy() { }

    /**
     *
     */
    get values() {
        let field = this.formGroup.get(this.fieldName);
        if(field) return field.value || [];
        return [];
    }

    /**
     * @param {string} value - user input to filter options with
     * @return {Promise} resolving array of string options
     */
    private filterValues(value: string): Promise<string[]> {
        if(this.filterFn) return this.filterFn(value);
        else return Promise.resolve([]);
    }

    /**
     * @param {MatChipInputEvent} event - event containing value to be applied
     */
    addValue(event: MatChipInputEvent): void {
        if(!event || !event.value) return;
        // Add only when MatAutocomplete is not open
        // To make sure this does not conflict with OptionSelected Event
        if (this.matAutoComplete.isOpen) return;

        let from = '$'+this.fieldName;
        let to = this.fieldName

        const input = event.input;
        const value = event.value;

        // Add our value
        if (value) {
            let val = value;
            if(typeof(val) === 'string') val = val.trim();
            let existing = this.getValue(to) || [];
            existing.push(val);
            this.setValue(to, existing);
        }

        // Reset the input value
        if (input) {
            input.value = '';
        }

        //clear the local form group so the autocomplete empties
        this.setValue(from, null);

    }

    /**
     * @param {any} value - currently selected value to remove
     */
    removeValue(value: any): void {
        let field = this.formGroup.get(this.fieldName);
        if(!field) return;
        let existing = field.value || [];
        let index = -1;
        existing.forEach( (p,i) => { if(p.id === value.id) { index = i; } });
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get(this.fieldName).setValue(existing);
        }

        //if user is removing an existing value, then abandon whatever they have
        // entered in the INPUT because the dropdown is populated with values
        // that may have been filtered to not include these existing selected values
        this.clearInput();
    }


    /**
     *
     */
    onAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        if(!event || !event.option || !event.option.value) return;
        let field = this.formGroup.get(this.fieldName);
        if(!field) return;
        let existing = field.value || [];
        existing.push(event.option.value);
        field.setValue(existing);

        //clear input and blur so autocomplete isn't left in weird state after selection
        this.clearInput();
    }


    getValue(fieldName) {
        let field = this.formGroup.get(fieldName);
        if(field) return field.value;
        return [];
    }
    setValue(fieldName, value) {
        let field = this.formGroup.get(fieldName);
        if(field) field.setValue(value);
    }


    public hasInput() {
        return this.field &&
               this.field.nativeElement.value &&
               this.field.nativeElement.value.length;
    }


    public clearInput() {
        if(this.field) this.field.nativeElement.value='';
        let field = this.formGroup.get(this.hiddenFieldName);
        if(field) field.setValue(null);
        if(this.matAutoComplete.isOpen) {
            this.acTrigger.closePanel();
        }
    }
}
