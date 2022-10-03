<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * The bundle extension class, used to load the configuration
 */
class MeteoConceptHiddenFieldAntispamExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $validator = $container->getDefinition('meteo_concept_hidden_field_antispam.form_type_extension');
        $validator->replaceArgument(0, $config['enabled']);
        $validator = $container->getDefinition('meteo_concept_hidden_field_antispam.form_type_extension');
        $validator->replaceArgument(1, $config['field_name']);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace(): string
    {
        return 'http://www.meteo-concept.fr/schema/dic/hiddenfieldantispam';
    }
}

