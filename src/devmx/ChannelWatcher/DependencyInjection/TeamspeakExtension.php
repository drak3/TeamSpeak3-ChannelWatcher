<?php
namespace devmx\ChannelWatcher\DependencyInjection;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 * @author drak3
 */
class TeamspeakExtension implements \Symfony\Component\DependencyInjection\Extension\ExtensionInterface
{
    /**
     * @var Symfony\Component\Config\FileLocator
     */
    protected $locator;
    
    public function __construct(FileLocator $locator) {
        $this->locator = $locator;
    }
    
        
    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension

     */
    function load(array $config, ContainerBuilder $container) {
        $config = $config[0];
        if(!isset($config['host']) && !isset($config['port'])) {
            throw new \InvalidArgumentException('You must at least specify a host');
        }
        $loader = new YamlFileLoader($container, $this->locator);
        $loader->load('teamspeak.yml');
        $container->setAlias('teamspeak.transport', 'teamspeak.transport.base');
        
        $container->setParameter('teamspeak.host', $config['host']);
        $container->setParameter('teamspeak.port', $config['port']);
        $container->setParameter('teamspeak.vserver.port', $config['vServerPort']);
        
        if(isset($config['loginname']) && isset($config['loginpassword'])) {
            $container->getDefinition('teamspeak.query')
                      ->addMethodCall('login', array($config['loginname'], $config['loginpass']));
        }
        
        $hasTicked = false;
        
        if(isset($config['ticktime'])) {
            $container->register('teamspeak.transport.ticked')
                      ->setClass('\devmx\Teamspeak3\Query\Transport\Decorator\TickingDecorator')
                      ->setArguments(array(new Reference('teamspeak.transport.base')))
                      ->addMethodCall('setTickTime', array($config['ticktime']));
            $container->setAlias('teamspeak.transport', 'teamspeak.transport.ticked');
            $hasTicked = true;
        }
        
        if(isset($config['debug']) && $config['debug'] === true) {
            if($hasTicked) {
                $ref = new Reference('teamspeak.transport.ticked');
            }
            else {
                $ref = new Reference('teamspeak.transport.base');
            }
            $container->register('teamspeak.transport.debug')
                      ->setClass('\devmx\Teamspeak3\Query\Transport\Decorator\DebuggingDecorator')
                      ->addArgument($ref);
            $container->setAlias('teamspeak.transport', 'teamspeak.transport.debug');
        }
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     *
     */
    function getNamespace() {
        return 'http://devmx.de/schema/dic/'.$this->getAlias();
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    function getXsdValidationBasePath() {
        return false;
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     *
     */
    function getAlias() {
        return 'teamspeak';
    }
    
}

?>
