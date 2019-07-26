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
ng build --prod --configuration=${1:-production} --sourceMap=true --output-hashing=none



declare -a names=(
  "main"
  "polyfills"
  "runtime"
)

for name in "${names[@]}"; do
  echo $NGDIST/$name.js " -> " $JSDEST/$name-es2015.js
  cp $NGDIST/$name-es2015.js $JSDEST/$name.js
  cp $NGDIST/$name-es2015.js.map $JSDEST/$name.js.map
done

# 'scripts' doesn't have variants
echo $NGDIST/scripts.js " -> " $JSDEST/scripts.js
cp $NGDIST/scripts.js $JSDEST/scripts.js
cp $NGDIST/scripts.js.map $JSDEST/scripts.js.map


# Don't forget the Styles! (they count too!)
echo $NGDIST/styles.css " -> " $CSSDEST/styles.css
cp $NGDIST/styles.css $CSSDEST/styles.css
cp $NGDIST/styles.css.map $CSSDEST/styles.css.map

# Copy all the other files types as well
cp $NGDIST/*.eot $CSSDEST
cp $NGDIST/*.svg $CSSDEST
cp $NGDIST/*.woff $CSSDEST
cp $NGDIST/*.woff2 $CSSDEST
cp $NGDIST/*.ttf $CSSDEST

# Move assets as well!
echo $NGDIST/assets " -> " $ASSETDEST
cp -R $NGDIST/assets/* $ASSETDEST
