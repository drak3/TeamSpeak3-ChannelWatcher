<?php

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 *
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 * The hostname of the TeamSpeak3-Server to watch
 * e.g 'example.com' or '127.0.0.1'
 */
$c['ts3']['host'] = '';

/**
 * The queryport (not the vserverport) of the TeamSpeak3-Server (10011 by default)
 */
$c['ts3']['query.port'] = 10011;

/**
 * The port of the virtual server to watch
 */
$c['ts3']['vserver.port'] = 9987;

/**
 * Login credentials
 * Uncomment and fill out if needed
 */
//$c['ts3']['login.name'] = '';
//$c['ts3']['login.pass'] = '';

/**
 * Nickname the channelwatcher is using when connecting to a server
 * defaults to "unknown from <ip>"
 * Note that the default nickname is given by the ts3-server and will be visible at least once, 
 * before the name is changed to the nick specified here.
 */
//$c['ts3']['query.nick'] = '';

/**
 * The time after which channels should be deleted
 */
$c['watcher']['time_to_live'] = array(
    'years'   =>    0,
    'months'  =>    0,
    'weeks'   =>    0,
    'days'    =>    0,
    'hours'   =>    0,
    'minutes' =>    0,
    'seconds' =>    0,
);

/**
 * List of channel-IDs that should not be deleted
 * Only applied when rule.acl_filter is enabled
 */
$c['watcher']['rule.acl_filter.blacklist'] = array();

/**
 * All rules that should be applied before deleting the channels
 * Uncomment (remove the //) the rules to use. (E.g, the 'rule.acl_filter' is enabled by default.
 */
$c['watcher']['rules'] = array(
        // this rule saves all channels that have visited parentes
        //$c['watcher']['rule.save_childs'],

        // This rule saves all channels that have visited childs
        //$c['watcher']['rule.save_parent'],

        // this rule saves all spacers
        //$c['watcher']['rule.save_spacer'],

        // this rule saves channels according to the specified black/whitelist
        $c['watcher']['rule.acl_filter'],

        // this rule save the default channel
        $c['watcher']['rule.save_default_channel'],
);

// Uncomment the following if you get an error mentioning date.timezone.
// More information is provided in the troubleshooting documentation: http://docs.devmx.de/teamspeak3-channel-watcher/troubleshooting.html#exception-datetime-construct-it-is-not-safe-to-rely-on-the-system-s-timezone-settings
// You may adjust this setting with your timezone. A list of all available timezones can be found here: http://www.php.net/manual/timezones.php
// date_default_timezone_set('UTC');

//*********** Database Configuration ***********\\
// By default a SQLite Database is used. Normally there is no need to change anything here.
// Note that the here configured database is just used by the ChannelWatcher and MUST NOT be identical to the database used by the TeamSpeak3-Server
// See http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html for configuration details

/**
 * Comment the following out to use another database than SQLITE as database
 */
// /*
$c['db']['connection.params'] = array(
  'driver' => 'pdo_sqlite',
  'path' => $c['storagedir'].$c['profile'].'_db.sqlite',
);
// */

/**
 * Uncomment the following to use MYSQL as database
 */
/*
  $c['db']['connection.params'] = array(
  'dbname' => '',
  'user' => '',
  'password' => '',
  'host' => '',
  'port' => 3306,
  //'unix_socket' => '',
  'driver' => 'pdo_mysql',
  'charset' => 'utf8'
  );
 */

/**
 * Uncomment the following lines to use POSTGRESQL as database
 */
/*
  $c['db']['connection.params'] = array(
  'dbname' => '',
  'host' => '',
  'port' => 0,
  'user' => '',
  'password' => '',
  'driver' => 'pdo_pgsql',
  );
 */

/**
 * Uncomment the following lines to use MSSQLSRV as database
 */
/*
  $c['db']['connection.params'] = array(
  'dbname' => '',
  'host' => '',
  'port' => 0,
  'user' => '',
  'password' => '',
  'driver' => 'pdo_sqlsrv'
  );
 */
