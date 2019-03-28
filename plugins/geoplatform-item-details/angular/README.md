# Angular

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 1.7.4.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

### Authenticating while using dev server

In order to run in a development environment (without WordPress) and still enable authentication,
you must run a proxy application which supports authentication and then leverage that app
as a proxy to this application.

**Note:** This is only necessary if running outside WordPress and you need authentication support.

- Clone and run an existing GeoPlatform client application, such as Web Map Viewer or Map Manager, locally.
- In the cloned app, authenticate as normal
- Edit proxy.conf.json to point to the correct URL of the authenticated app instead of "localhost:8081" (if necessary)
- Run this application using `npm run start` which will proxy authentication calls from this app to the authenticated app running locally
- In the dev tools console for the authenticated app (not this app), run the following which will open this app and enable authentication to occur using your session in the other app:

```
window.location = `http://localhost:4200/?access_token=${atob(localStorage.gpoauthJWT)}`
```


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
