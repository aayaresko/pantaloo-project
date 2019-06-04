#!/bin/bash

if [ -s .env ]
then
     echo ".env already init"
else
     cp .env.stage .env
     echo ".env init"
fi