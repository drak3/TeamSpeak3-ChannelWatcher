<?php
$start = \microtime(true);
require_once 'autoload.php';
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use devmx\ChannelWatcher\DependencyInjection\TeamspeakExtension;
use devmx\ChannelWatcher\DependencyInjection\AppExtension;
use devmx\ChannelWatcher\DependencyInjection\ChannelWatcherExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use devmx\ChannelWatcher\DependencyInjection\DataBaseExtension;

$usage = $_SERVER['argv'][0]." <command> <config> <options> <arguments>\n";

if(  php_sapi_name() !== 'cli') {
    die('you must run this script from command line');
}
if($_SERVER['argc'] < 3) {
    die($usage);
}

$profile = $_SERVER['argv'][2];
unset($_SERVER['argv'][2]);

$container = new ContainerBuilder();

$container->setParameter('app.version', 0.1);
$container->setParameter('app.name', 'Teamspeak3 ChannelWatcher');
$container->setParameter('app.profile', $profile);
$container->setParameter('app.storagedir', __DIR__.'/storage/%app.profile%/');

$container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass());
$locator = new FileLocator(array(__DIR__.'/config/profiles', __DIR__.'/config/services/'));

$tsExtension = new TeamspeakExtension($locator);
$appExtension = new AppExtension($locator);
$watcherExtension = new ChannelWatcherExtension($locator);
$dbExtension = new DataBaseExtension($locator);

$container->registerExtension($tsExtension);
$container->registerExtension($appExtension);
$container->registerExtension($watcherExtension);
$container->registerExtension($dbExtension);

$loader = new YamlFileLoader($container, $locator);
$loader->load($profile.'.yml');



$container->compile();


$container->get('application')->run();
$end = \microtime(true);
$taken = $end-$start;
echo "time taken: $taken\n";
?>
