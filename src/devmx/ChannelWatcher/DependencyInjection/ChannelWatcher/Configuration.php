<?php
namespace devmx\ChannelWatcher\DependencyInjection\ChannelWatcher;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
/**
 *
 * @author drak3
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('channelwatcher');
        $root
            ->children()
                    ->arrayNode('deleter')
                        ->children()
                            ->arrayNode('whitelist')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('blacklist')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('deletetime')
                                ->children()
                                    ->scalarNode('years')->defaultValue(0)->end()
                                    ->scalarNode('months')->defaultValue(0)->end()
                                    ->scalarNode('weeks')->defaultValue(0)->end()
                                    ->scalarNode('days')->defaultValue(0)->end()
                                    ->scalarNode('hours')->defaultValue(0)->end()
                                    ->scalarNode('minutes')->defaultValue(0)->end()
                                    ->scalarNode('seconds')->defaultValue(0)->end()
                                ->end()
                        ->end()
                ->end();
        return $treeBuilder;
    }
}

?>
