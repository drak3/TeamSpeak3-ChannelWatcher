<?php
namespace devmx\ChannelWatcher\DependencyInjection;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
    public function load(array $config, ContainerBuilder $container) {
        $config = $config[0];
        $loader = new YamlFileLoader($container, $this->locator);
        $loader->load('channelwatcher.yml');
        if(isset($config['crawler'])) {
            $this->loadCrawlerConfig($config['crawler'], $container);
        }
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
