#!/bin/bash

if [[ $# -lt 5 ]] ; then
    echo "Place your dumped sql file into this directory and run this script to import them into your desired MySQL database."
    echo ""

    echo "SYNOPSIS:"
    echo "       ./hydrateMysqlContainer.sh <dump_file_name> <container name> <username> <password> <dbname>"

    exit 1
fi

file=$1
container=$2
username=$3
password=$4
database=$5

echo "==== Overwriting Database ====="
echo "file: " $file
echo "container: " $container
echo "username: " $username
echo "password: " $password
echo "database: " $database
echo "==========================="

echo "Copying SQL scripts in"
docker cp $file $container:/

echo "Loading SQL scripts"
docker exec -it $container /bin/bash \
  -c "mysql -u $username --password=$password -D $database < /$file"

echo "Import complete"
