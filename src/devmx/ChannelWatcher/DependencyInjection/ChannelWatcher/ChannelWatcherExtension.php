<?php
namespace devmx\ChannelWatcher\DependencyInjection\ChannelWatcher;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
/**
 *
 * @author drak3
 */
class ChannelWatcherExtension implements ExtensionInterface
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
    public function load(array $configs, ContainerBuilder $container) {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        
        $loader = new YamlFileLoader($container, $this->locator);
        $loader->load('channelwatcher.yml');
        $this->addDeleter($config['deleter'], $container);   
    }
    
    protected function addDeleter($config, $container)  {
        if(isset($config['deletetime'])) {
            $container->setParameter('channelwatcher.deletetime', $this->parseTime($config['deletetime']));
        }
        if($config['whitelist'] !== array() || $config['blacklist'] !== array()) {
            $args = array();
            if($config['blacklist'] !== array()) {
                $args[] = $config['blacklist'];
            }
            if($config['whitelist'] !== array()) {
                $args[] = $config['whitelist'];
            }
            $container->register('channelwatcher.acl', '\devmx\ChannelWatcher\AccessControl\ListBasedControler')
                      ->setArguments($args);
            $container->getDefinition('deleter')
                      ->addMethodCall('setAccessControlList', array(new Reference('channelwatcher.acl')));
        }
    }
    
    protected function parseTime(array $time) {
        $timeString = 'P';
        $timeString .= $time['years'].'Y';
        $timeString .= $time['months'].'M';
        $timeString .= $time['weeks'].'W';
        $timeString .= $time['days'].'D';
        $timeString .= 'T';
        $timeString .= $time['hours'].'H';
        $timeString .= $time['minutes'].'M';
        $timeString .= $time['seconds'].'S';
        return new \DateInterval($timeString);
    }
        

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     *
     */
    public function getNamespace() {
        return 'http://devmx.de/schema/dic/'.$this->getAlias();
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath() {
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
    public function getAlias() {
        return 'channelwatcher';
    }
}

?>
