#!/usr/bin/env bash
set -e

cp ~/.ssh/id_rsa.pub php7/authorized_keys
cp ~/.ssh/id_rsa.pub apache/authorized_keys
docker-compose build
rm php7/authorized_keys
rm apache/authorized_keys
docker-compose up --force-recreate
