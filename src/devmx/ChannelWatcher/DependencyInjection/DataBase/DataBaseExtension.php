<?php
namespace devmx\ChannelWatcher\DependencyInjection\DataBase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
/**
 *
 * @author drak3
 */
class DataBaseExtension implements ExtensionInterface
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
        
        $loader = new YamlFileLoader($container, $this->locator);
        $loader->load('dbal.yml');
        
        $config = $config[0];
        if(!isset($config['type'])) {
            throw new \InvalidArgumentException('You must specify a database type');
        }
        $driver = $this->parseDriver($config['type']);
        
        $dbalconfig = array();
        $dbalconfig['driver'] = $driver;
        
        if($driver === 'pdo_sqlite') {
            $container->setParameter('dbal.sqlite.file', '%app.storagedir%%dbal.sqlite.filename%');
            $dbalconfig['path'] = $container->getParameter('dbal.sqlite.file');
        }
        else {
            if(!isset($config['host']) || !isset($config['port'])) {
                throw new \InvalidArgumentException('You must specify host and port');
            }
            $dbalconfig['host'] = $config['host'];
            $dbalconfig['port'] = $config['port'];
            if(!isset($config['dbname'])) {
                throw new \InvalidArgumentException('You must specify a dbname');
            }
            $dbalconfig['dbname'] = $config['dbname'];
        }
        
        if(isset($config['user'])) {
            $dbalconfig['user'] = $config['user'];
        }
        
        if(isset($config['password'])) {
            $dbalconfig['password'] = $config['password'];
        }
        
        if(in_array($driver, array('pdo_oci', 'oci8' , 'pdo_mysql',))) {
            $dbalconfig['charset'] = '%dbal.charset%';
        }
        
        if(isset($config['prefix'])) {
            $container->setParameter('dbal.prefix', $config['prefix']);
        }
        else {
            $container->setParameter('dbal.prefix', '');
        }
        
        if($container->hasParameter( 'dbal.connection.additional_params')) {
            $dbalconfig = array_merge($dbalconfig, $container->getParameter('dbal.connection.additional_params'));
        }
        
        
        $container->setParameter('dbal.connection.parameter', $dbalconfig);
        
        
    }
    
    protected function parseDriver($type) {
        $map = array(
            //simple names
            'mysql' => 'pdo_mysql',
            'sqlite' => 'pdo_sqlite',
            'pgsql' => 'pdo_pgsql',
            'oracle' => 'oci8',
            'mssql' => 'pdo_sqlsrv',
            //identities:
            'pdo_mysql' => 'pdo_mysql',
            'pdo_sqlite' => 'pdo_sqlite',
            'pdo_pgsql' => 'pdo_pgsql',
            'pdo_oci' => 'pdo_oci',
            'pdo_sqlsrv' => 'pdo_sqlsrv',
            'oci8' => 'oci8',
        );
        if(!isset($map[$type])) {
            throw new \InvalidArgumentException('Specified Database type not available');
        }
        return $map[$type];
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
        return 'database';
    }
}

?>
