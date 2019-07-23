#!/bin/bash
# Path where script is running
P="$( cd "$(dirname "$0")" ; pwd -P )"
NG="."
NGDIST="./dist"
JSDEST="../public/js"
CSSDEST="../public/css"
ASSETDEST="../assets"

# Because Angular is so cool as to require a convo like this:
#   https://stackoverflow.com/questions/37558656/angular-cli-ng-build-doesnt-produce-a-working-project
#
# We will then have to go ahead and make this build script in primitive "bash"
# to suplement the new shiny "advanced" Angular tool.
#
# Thanks Google!
ng build --prod --configuration=${1:-production} --output-hashing=none

declare -a names=(
  "main"
  "scripts"
  "polyfills"
  "runtime"
)

for name in "${names[@]}"; do
  echo $NGDIST/$name-es2015.js " -> " $JSDEST/$name.bundle.js
  cp $NGDIST/$name-es2015.js $JSDEST/$name.bundle.js
  cp $NGDIST/$name-es2015.js.map $JSDEST/$name.bundle.js.map
done

# Don't forget the Styles! (they count too!)
echo $NGDIST/styles.css " -> " $CSSDEST/styles.css
cp $NGDIST/styles.css $CSSDEST/styles.css
cp $NGDIST/styles.css.map $CSSDEST/styles.css.map

# Copy all the other files types as well
# cp $NGDIST/*.eot $CSSDEST
# cp $NGDIST/*.svg $CSSDEST
# cp $NGDIST/*.woff $CSSDEST
# cp $NGDIST/*.woff2 $CSSDEST
# cp $NGDIST/*.ttf $CSSDEST

# Move assets as well!
echo $NGDIST/assets " -> " $ASSETDEST
cp -R $NGDIST/assets/* $ASSETDEST
