#!/bin/bash

# Because Angular is so cool as to require a convo like this:
#   https://stackoverflow.com/questions/37558656/angular-cli-ng-build-doesnt-produce-a-working-project
#
# We will then have to go ahead and make this build script in primitive "bash"
# to suplement the new shiny "advanced" Angular tool.
#
# Thanks Google!
ng build --prod

declare -a names=(
  "main"
  "scripts"
  "polyfills"
  "inline"
)

for name in "${names[@]}"; do
  echo dist/$name.bundle.js " <- " dist/$name.*.bundle.js
  mv dist/$name.*.bundle.js dist/$name.bundle.js
done

# Don't forget the Styles! (they count too!)
mv dist/styles.*.bundle.css dist/styles.bundle.css
