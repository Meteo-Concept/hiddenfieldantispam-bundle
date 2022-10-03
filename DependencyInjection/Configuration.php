<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The configuration class for the bundle, fairly straightforward
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('meteo_concept_hidden_field_antispam');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode("enabled")
                    ->info("Whether to enable the antispam hidden field mechanism.")
                    ->defaultTrue()
                    ->end()
                ->scalarNode("field_name")
                    ->info("The name of the hidden antispam field to add to forms.")
                    ->defaultValue("meteo_concept_sentinel")
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
