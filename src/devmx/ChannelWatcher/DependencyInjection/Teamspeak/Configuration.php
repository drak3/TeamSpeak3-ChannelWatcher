<?php
namespace devmx\ChannelWatcher\DependencyInjection\Teamspeak;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 *
 * @author drak3
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('teamspeak');
        $root
            ->children()
                ->scalarNode('host')->isRequired()->end()
                ->scalarNode('port')->defaultValue(10011)->end()
                ->scalarNode('vServerPort')->end()
                ->scalarNode('user')->end()
                ->scalarNode('password')->end()
                ->scalarNode('ticktime')->end()
                ->scalarNode('debug')->end()
                ->scalarNode('nickname')->end()
             ->end();
        return $treeBuilder;
    }
}

?>
