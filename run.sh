#!/usr/bin/env sh

SCRIPT=$(readlink -f "$0")
# Absolute path this script is in
SCRIPT_DIR=$(dirname "$SCRIPT")

PROJECT_NAME=darealfive-bistmask
DOCKER_IMAGE_VERSION=v1.0.0
DOCKER_IMAGE_NAME_TESTER=$PROJECT_NAME-tester:$DOCKER_IMAGE_VERSION

CMD=$1
shift

case "$CMD" in
    tester-build)
        	DOCKER_BUILDKIT=1 docker build \
        	  -f "$SCRIPT_DIR"/test.Dockerfile \
        	  -t $DOCKER_IMAGE_NAME_TESTER \
        	  --target tester \
        	  .
        ;;
    tester-run)
        	docker run \
        	  --rm \
        	  $DOCKER_IMAGE_NAME_TESTER \
        	  tests
        ;;
    *)
        exit 1;
        ;;
esac
