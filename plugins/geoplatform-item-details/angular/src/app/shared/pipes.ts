
import { NgModule, Pipe, PipeTransform } from '@angular/core';

/*
 * Limits an array of entries to a maximum number
 * Usage:
 *   array | limitTo:num
 * Example:
 *   {{ ['one','two','three'] | limitTo:2 }}
 */
@Pipe({name: 'limitTo'})
export class LimitToPipe implements PipeTransform {
    transform(value: any[], num: number): any[] {
        if(value && value.length > num) {
            return value.slice(0, num);
        }
        return value;
    }
}






/*
 * Formats a GeoPlatform Item's type to a friendly label
 * Usage:
 *   type | friendlyType
 * Example:
 *   {{ "dcat:Dataset" | friendlyType }}
 *   formats to: "Dataset"
*/
@Pipe({name: 'friendlyType'})
export class FriendlyTypePipe implements PipeTransform {
    transform(value: string): string {
        if(!value || typeof(value) !== 'string' || value.length === 0) return value;
        let name = value;
        let idx = value.indexOf(":");
        if(~idx) name = value.substring(idx+1);
        if('VCard' === name) name = 'Contact';
        return name;
    }
}





/*
 * Replaces underscores between words with spaces
 * Usage:
 *   type | fixLabel
 * Example:
 *   {{ "One_Two_Three" | fixLabel }}
 *   formats to: "One Two Three"
*/
@Pipe({name: 'fixLabel'})
export class FixLabelPipe implements PipeTransform {
    transform(value: string): string {
        if(!value || typeof(value) !== 'string' || !value.length) return 'Untitled';
        let result = value.replace(/([a-z])([A-Z])/g, "$1 $2").replace(/_/g, " ").trim();
        return result.charAt(0).toUpperCase() + result.slice(1);
    }
}




import { DomSanitizer } from "@angular/platform-browser";

@Pipe({ name: 'safeUrl' })

export class SafeUrlPipe implements PipeTransform {

    constructor(private sanitizer: DomSanitizer) { }

    transform(url) {
        return this.sanitizer.bypassSecurityTrustResourceUrl(url);
    }
}
