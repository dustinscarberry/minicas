#!/bin/sh

docker login

# build and push app image

docker build -t dustinscarberry/minicas:latest -f Dockerfile ../
docker push dustinscarberry/minicas:latest
