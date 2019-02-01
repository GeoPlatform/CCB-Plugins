#!/bin/bash
# Path where script is running
P="$( cd "$(dirname "$0")" ; pwd -P )"
NG="."
NGDIST="./dist"
JSDEST="../public/js"
CSSDEST="../public/css"
ASSETDEST="../assets"

rm ../public/js/*bundle.js
rm -r dist/*

# https://stackoverflow.com/questions/37558656/angular-cli-ng-build-doesnt-produce-a-working-project
ng build --environment=${1:-prod} --preserve-symlinks --aot #--build-optimizer=false
# ng build --prod --environment=${1:-prod}

declare -a names=(
  "main"
  "scripts"
  "polyfills"
  "inline"
)

# Note * was removed, it was: $NGDIST/$name.*.bundle.js (left hand side)
for name in "${names[@]}"; do
  echo $JSDEST/$name.bundle.js " <- " $NGDIST/$name.bundle.js
  cp $NGDIST/$name.bundle.js $JSDEST/$name.bundle.js
done

# Don't forget the Styles! (they count too!)
echo $CSSDEST/styles.bundle.js " <- " $NGDIST/styles.bundle.js
cp $NGDIST/styles.bundle.css $CSSDEST/styles.bundle.css

# Copy all the other files types as well
cp $NGDIST/*.eot $CSSDEST
cp $NGDIST/*.svg $CSSDEST
cp $NGDIST/*.woff $CSSDEST
cp $NGDIST/*.woff2 $CSSDEST
cp $NGDIST/*.ttf $CSSDEST

# Move assets as well!
#echo $ASSETDEST " <- " $NGDIST/assets
#cp $NGDIST/assets/* $ASSETDEST
