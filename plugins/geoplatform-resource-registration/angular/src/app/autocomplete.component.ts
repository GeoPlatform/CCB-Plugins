
import {
    Component, OnInit, OnChanges, OnDestroy,
    Input, ViewChild, ElementRef, SimpleChanges
} from '@angular/core';
import { FormGroup } from '@angular/forms';
import {
    MatAutocompleteSelectedEvent,
    MatChipInputEvent,
    MatAutocomplete
} from '@angular/material';
import { Observable, Subject } from 'rxjs';
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
          <mat-label>{{label}}</mat-label>
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
                    *ngIf="formGroup.get(hiddenFieldName).value?.length"
                    (click)="field?.nativeElement.value=''">
                    close
              </mat-icon>
          </mat-chip-list>
          <mat-autocomplete #acmcAutoComplete="matAutocomplete"
              (optionSelected)="onAutocompleteSelection($event)">
              <mat-option *ngIf="formGroup.get(hiddenFieldName).value?.length && !(filteredOptions | async)?.length">No matches found</mat-option>
              <mat-option *ngFor="let option of filteredOptions | async" [value]="option">
                  {{ option.label }}
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

    public hiddenFieldName : string;
    public filteredOptions: Observable<string[]>;
    @ViewChild('acmcAutcomplete') matAutoComplete: MatAutocomplete;
    @ViewChild('acmcInput') field: ElementRef;

    constructor() {

    }

    ngOnInit() {
        this.filteredOptions = this.formGroup.get('$'+this.fieldName)
        .valueChanges.pipe(
            flatMap(value => {
                let result = this.filterValues(value);
                return result;
            })
        );
    }

    ngOnChanges( changes : SimpleChanges ) {
        if(changes.fieldName && changes.fieldName.currentValue) {
            this.hiddenFieldName = '$' + changes.fieldName.currentValue;
        }
    }

    ngOnDestroy() { }

    get values() {
        return this.formGroup.get(this.fieldName).value || [];
    }

    private filterValues(value: string): Promise<string[]> {
        if(this.filterFn) return this.filterFn(value);
        else return Promise.resolve([]);
    }

    addValue(event: MatChipInputEvent): void {
        if(!event || !event.value) return;
        // Add only when MatAutocomplete is not open
        // To make sure this does not conflict with OptionSelected Event
        if (!this.matAutoComplete.isOpen) {
            this.addChip('$'+this.fieldName, this.fieldName, event);
        }
    }

    /**
     * @param {string} from - temporary model property
     * @param {string} to - actual model property
     * @param {MatChipInputEvent} event - event containing value to be applied
     */
    private addChip( from: string, to: string, event: MatChipInputEvent ) {

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


    removeValue(pub: any): void {
        let existing = this.formGroup.get(this.fieldName).value;
        let index = -1;
        existing.forEach( (p,i) => { if(p.id === pub.id) { index = i; } });
        if (index >= 0) {
            existing.splice(index, 1);
            this.formGroup.get(this.fieldName).setValue(existing);
        }
    }



    onAutocompleteSelection(event: MatAutocompleteSelectedEvent): void {
        if(!event || !event.option || !event.option.value) return;

        let existing = this.formGroup.get(this.fieldName).value || [];
        existing.push(event.option.value);
        this.formGroup.get(this.fieldName).setValue(existing);

        //clear input and blur so autocomplete isn't left in weird state after selection
        this.field.nativeElement.value='';
        this.field.nativeElement.blur();
        this.formGroup.get('$'+this.fieldName).setValue(null);
    }

    getValue(field) { return this.formGroup.get(field).value; }
    setValue(field, value) { this.formGroup.get(field).setValue(value); }

    public hasInput() {
        return this.field &&
               this.field.nativeElement.value &&
               this.field.nativeElement.value.length;
    }
}
