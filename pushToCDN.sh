#!/bin/bash

if [[ -z ${2+x} ]] ; then
    echo "Packages and uploads version of a plugin to a the GeoPlatform CDN."
    echo ""

    echo "SYNOPSIS:"
    echo "       ./geop_zip_file.sh <path_to_asset> <version>"

    exit 1
fi

path=$1
version=$2
# Use Regex to determin if we are uploding a theme or plugin
regex="/?(plugins|themes)/([^/]+)/?"
[[ "$1" =~ $regex ]] # Do the regex match
type="${BASH_REMATCH[1]}"
name="${BASH_REMATCH[2]}"

if [ "$type" == "" ] || [ "$name" == "" ] ; then
    echo "Invalid path to plugin/theme provided: $1"
    exit 1
fi

echo "==== Uploading to CDN ====="
echo "type: " $type
echo "name: " $name
echo "version: " $version
echo "==========================="


# Zip file creation and success feedback.
pushd $type
zip -r $name $name
echo "==========================="

# Push to S3 (cp for single asset)
aws s3 cp --acl public-read $name.zip s3://geoplatform-cdn/CCB/$type/$name/$version/$name.zip
echo "==========================="

# Print out full URL to resource
echo "Artifact pushed to CDN: http://dyk46gk69472z.cloudfront.net/CCB/$type/$name/$version/$name.zip"
echo "It may take a few minutes for the resource to propagate through the CDN."

# Delete the zip
#rm $name.zip
popd


