<?php

namespace MeteoConcept\HCaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * The bundle extension class, used to load the configuration
 */
class MeteoConceptHCaptchaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Take the hCaptcha site key and secret from the configuration to
        // inject them automatically in the form type and the CAPTCHA validator
        $validator = $container->getDefinition('meteo_concept_hidden_field_antispam.form_extension');
        $validator->replaceArgument(0, $config['enabled']);
        $validator = $container->getDefinition('meteo_concept_hidden_field_antispam.form_extension');
        $validator->replaceArgument(1, $config['field_name']);
    }

    public function getNamespace(): string
    {
        return 'http://www.meteo-concept.fr/schema/dic/hiddenfieldantispam';
    }
}

