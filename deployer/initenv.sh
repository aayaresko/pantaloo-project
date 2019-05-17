#!/bin/bash

if [ -s .env ]
then
     echo ".env already init"
else
     cp .env.local .env
     echo ".env init"
fi