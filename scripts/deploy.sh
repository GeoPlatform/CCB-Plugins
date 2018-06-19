#!/bin/sh
# Pre-deploy script for gpoauth

####################
# Script variables #
####################

DIRECTORY=/var/lib/$APPLICATION_NAME
ACCOUNT=$(aws sts get-caller-identity --output text --query 'Account')
REGION=${REGION:-"us-east-1"}
IMAGE=$ACCOUNT.dkr.ecr.$REGION.amazonaws.com/$APPLICATION_NAME:latest

echo "======= Variables Set ========="
echo "Directory: "$DIRECTORY
echo "Deployment Group Name: "$DEPLOYMENT_GROUP_NAME
echo "Account: "$ACCOUNT
echo "Image: "$IMAGE
echo "Application Name: "$APPLICATION_NAME
echo "Region: "$REGION

#########################
# remove any old images #
#########################
OLD_IMAGE=$(docker images -q $ACCOUNT.dkr.ecr.$REGION.amazonaws.com/$APPLICATION_NAME)

if [ -z $OLD_IMAGE ]; then
  echo "No image marked for removal, build will not be the latest changeset."
else
  echo "Removing old container image: "$OLD_IMAGE
  docker rmi $OLD_IMAGE
fi

###############################################################
# Pre-deployment any dockers that are running clear directory #
###############################################################

if [ -d "$DIRECTORY" ]; then
  pushd $DIRECTORY
    if [ -f docker-compose.yml ]; then
      docker-compose down #stops and removes containers
    fi
    rm -rf *
  popd
else
  mkdir $DIRECTORY
fi

##############################################################
# Check for, create, and modifiy ownership of uploads folder #
##############################################################
UPLOAD_DIR=$DIRECTORY/uploads
if [ ! -d "$UPLOAD_DIR" ]; then
   pushd $DIRECTORY
      mkdir uploads
   popd
fi
chown -R root:docker $UPLOAD_DIR
chmod -R 757 $UPLOAD_DIR

#########################################
# Setup github integration user account #
#########################################
echo "Pulling docker-compose.yml file for $DEPLOYMENT_GROUP_NAME envioment"
if [ "$DEPLOYMENT_GROUP_NAME" == "SIT" ]
then
    aws s3 cp s3://gpcodedeploy/$APPLICATION_NAME/sit/ /$DIRECTORY --exclude '*' --include 'docker-compose.yml' --recursive
    echo "Compose file pulled down"
else
	echo "Error: Invalid environment specified: $DEPLOYMENT_GROUP_NAME " 1>&2
	exit 1
fi

chown root:docker $DIRECTORY/docker-compose.yml
chmod 655 $DIRECTORY/docker-compose.yml

##########################
# Perform ECR Repo login #
##########################

$(aws ecr get-login --no-include-email --region us-east-1)

###################################
# Pull and start new docker image #
###################################
pushd $DIRECTORY
  docker pull $IMAGE
  docker-compose up -d
popd

exit 0
