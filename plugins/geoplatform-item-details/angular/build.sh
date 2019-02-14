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
ng build --prod --aot=false --environment=${1:-prod}

declare -a names=(
  "main"
  "scripts"
  "polyfills"
  "inline"
)

for name in "${names[@]}"; do
  echo $JSDEST/$name.bundle.js " <- " $NGDIST/$name.*.bundle.js
  cp $NGDIST/$name.*.bundle.js $JSDEST/$name.bundle.js
done

# Don't forget the Styles! (they count too!)
echo $CSSDEST/styles.bundle.js " <- " $NGDIST/styles.*.bundle.js
cp $NGDIST/styles.*.bundle.css $CSSDEST/styles.bundle.css

# Copy all the other files types as well
cp $NGDIST/*.eot $CSSDEST
cp $NGDIST/*.svg $CSSDEST
cp $NGDIST/*.woff $CSSDEST
cp $NGDIST/*.woff2 $CSSDEST
cp $NGDIST/*.ttf $CSSDEST

# Move assets as well!
echo $ASSETDEST " <- " $NGDIST/assets
cp -R $NGDIST/assets/* $ASSETDEST
