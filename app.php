<?php
use devmx\ChannelWatcher\DependencyInjection\AppContainer;
//we want to catch the early errors, as they are not handled by the application
error_reporting(-1);
require_once 'vendor/autoload.php';

$c = new AppContainer;

$c['debug'] = false;

$c['root_dir'] = __DIR__;

$c['application']->run();
?>
