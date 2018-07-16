# GeoPlatform Search Application

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 1.7.4.

## Initial Setup
If you have not already setup your development environment to work using Angular you will need to do the following:

1. Install Node 6.x
> Goto and follow instructions at : https://nodejs.org/en/download/
> Make sure to get version 6.x of NodeJS.

2. Install Angular-CLI 1.7.4
**Note:** Newer versions of Angular-CLI require newer versions of NodeJS which are not supported by GeoPlatform at this time.
> [user ~]$ sudo npm install -g @angular/cli@1.7.4

3. Run NPM install in the angular dirctory
> [user ~]$ cd { dir-of-clone/plugins/geoplatform-search/angular }
> [user angular]$ npm install

4. Build plugin from source for the desired environment. This build process specified the environment to be utilized as well as copying assets and built JS and CSS into the appropriate WP plugin locations suitable for usage inside WP. 
> [user dir]$ ./build.sh [sit|stg|prod]

5. More stuff needed to expose angular app from within WP
> TODO

After that you should enable to plugin in the local WordPress and begin testing/developing.
When you update source files the project should automatically rebuild. Simply refresh the page
to see the code changes.



## Development server

Run `ng serve` for a dev server. The app will automatically reload if you change any of the source files.

## Code scaffolding

Run `ng generate component component-name` to generate a new component. You can also use `ng generate directive|pipe|service|class|guard|interface|enum|module`.

## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory. Use the `-prod` flag for a production build.  

## Running unit tests

Run `ng test` to execute the unit tests via [Karma](https://karma-runner.github.io).

## Running end-to-end tests

Run `ng e2e` to execute the end-to-end tests via [Protractor](http://www.protractortest.org/).

## Further help

To get more help on the Angular CLI use `ng help` or go check out the [Angular CLI README](https://github.com/angular/angular-cli/blob/master/README.md).
