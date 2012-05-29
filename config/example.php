<?php
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
 */
//$c['ts3']['login.name'] = '';
//$c['ts3']['login.pass'] = '';

/**
 * The time after which channels should be deleted 
 */
$c['watcher']['time_to_live'] = array(
    'years'     => 0,
    'months'    => 0,
    'weeks'     => 0,
    'days'      => 0,
    'hours'     => 0,
    'minutes'   => 0,
    'seconds'   => 0,
);

/**
 * List of channel-IDs that should not be deleted 
 * Only applied when rule.acl_filter is enabled
 */
$c['watcher']['rule.acl_filter.blacklist'] = array();

/**
 * All rules that should be applied before deleting the channels 
 */
$c['watcher']['rules'] = array(
  // This rule saves all channels that have visited childs  
    //$c['watcher']['rule.save_parent'],
 
  // this rules saves all spacers
    //$c['watcher']['rule.save_spacer'],
    
  // this rule saves channels according to the specified black/whitelist
    //$c['watcher']['rule.acl_filter'],
  
  // this rule saves all channels that have visited parentes
    //$c['watcher']['rule.save_childs']
);

//*********** Database Configuration ***********\\

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
     'charset' => 'UTF8'
 );
*/

/**
 * Uncomment the following to use SQLITE as database 
 */
/*
$c['db']['connection.params'] = array(
    'driver' => 'pdo_sqlite',
    'path' => $c['storagedir'].$c['profile'].'_db.sqlite',
);
 */

?>
