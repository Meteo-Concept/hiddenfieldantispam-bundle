<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The configuration class for the bundle, fairly straightforward
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('meteo_concept_hidden_field_antispam');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('meteo_concept_hidden_field_antispam');
        }

        $rootNode
            ->children()
                ->scalarNode("enabled")
                    ->info("Whether to enable the antispam hidden field mechanism.")
                    ->defaultValue(true)
                ->scalarNode("field_name")
                    ->info("The name of the hidden antispam field to add to forms.")
                    ->defaultValue("meteo_concept_sentinel")
            ->end()
        ;

        return $treeBuilder;
    }
}
