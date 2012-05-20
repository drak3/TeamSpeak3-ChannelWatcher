<?php

$c['ts3']['host'] = 'localhost';
$c['ts3']['query.port'] = 10011;
$c['ts3']['vserver.port'] = 9987;
$c['ts3']['login.name'] = '';
$c['ts3']['login.pass'] = '';
$c['delete_time'] = new \DateInterval('PT100S');

$c['dbal.connection.params'] = array(
    'driver' => 'pdo_sqlite',
    'path' => $c['app.storagedir'].$c['application.profile'].'_db.sqlite',
);

?>
