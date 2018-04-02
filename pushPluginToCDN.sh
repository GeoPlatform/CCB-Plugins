#!/bin/bash

if [[ $# -eq 0 ]] ; then
    echo "Zips the file provided and appends the version number to its name."
    echo ""

    echo "SYNOPSIS:"
    echo "       ./geop_zip_file.sh <folder> <version>"
    echo ""

#    echo "List of current ECS repositories:"
#    aws ecr describe-repositories | grep 'Name'

    exit 0
fi

# Zip file creation and success feedback.
zip -r $1_$2.zip $1
echo ""
echo "  Zip complete, $1_$2.zip created."
