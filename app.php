<?php
use devmx\ChannelWatcher\DependencyInjection\Container;
date_default_timezone_set('Europe/Berlin');
require_once 'vendor/autoload.php';

$usage = $_SERVER['argv'][0]." <command> <config> <options> <arguments>\n";

if(  php_sapi_name() !== 'cli') {
    die('you must run this script from command line');
}
if($_SERVER['argc'] < 3) {
    die($usage);
}

$profile = $_SERVER['argv'][2];
unset($_SERVER['argv'][2]);

$c = new Container();

$c['application.name'] = 'Teamspeak3 ChannelWatcher';
$c['application.version'] = '0.1';
$c['application.profile'] = $profile;
$c['app.storagedir'] = __DIR__.'/storage/'.$profile.'/';

$configPath = 'config/'.$profile.'.php';

if(  file_exists($configPath) && is_readable($configPath)) {
    include($configPath);
} else {
    die('Unknown configuration '.$profile.PHP_EOL);
}

$c['application']->run();
?>
