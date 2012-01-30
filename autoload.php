<?php
require_once 'vendor/.composer/autoload.php';
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespace( 'devmx\ChannelWatcher', array(__DIR__.'/src/') );
$loader->register();
?>
