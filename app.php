<?php
use devmx\ChannelWatcher\DependencyInjection\Container;
date_default_timezone_set('Europe/Berlin');
require_once 'vendor/autoload.php';

$c = new Container();

$c['app.root_dir'] = __DIR__;

$c['app']->run();
?>
