#!/bin/sh

if [ $DB = 'pgsql' ] 
then 
    psql -c 'DROP DATABASE IF EXISTS devmx_channelwatcher_tests;' -U postgres
    psql -c 'DROP DATABASE IF EXISTS devmx_channelwatcher_tests_tmp;' -U postgres
    psql -c 'create database devmx_channelwatcher_tests;' -U postgres
    psql -c 'create database devmx_channelwatcher_tests_tmp;' -U postgres
fi

if [ $DB = 'mysqli' ]
then 
    mysql -e 'create database IF NOT EXISTS devmx_channelwatcher_tests_tmp;create database IF NOT EXISTS devmx_channelwatcher_tests;'
fi