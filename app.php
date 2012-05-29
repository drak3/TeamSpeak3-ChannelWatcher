<?php
use devmx\ChannelWatcher\DependencyInjection\AppContainer;
require_once 'vendor/autoload.php';

$c = new AppContainer;

$c['root_dir'] = __DIR__;

$c['application']->run();
?>
