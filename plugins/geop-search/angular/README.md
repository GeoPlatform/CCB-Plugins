# GeoPlatform Search Application

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 1.7.4.

## Initial Setup
If you have not already setup your development environment to work using Angular you will need to do the following:

1. Install Node 6.x
> Goto and follow instructions at : https://nodejs.org/en/download/
> Make sure to get version 6.x of NodeJS.

2. Install Angular-CLI 1.7.4
> [user ~]$ sudo npm install -g @anular/cli@1.7.4

3. Run NPM install in the angular dirctory
> [user ~]$ cd { dir-of-clone/plugins/geop-search/angular }
> [user angular]$ npm install

4. Build plugin from source
> [user dir]$ ng build --prod

5. Set environment settings
> Create (or replace) assets/env.json and define environment variables needed for operation
> "ualUrl" - URL of GeoPlatform API endpoint ('https://ual.geoplatform.gov' for production)

6. More stuff needed to expose angular app from within WP
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
