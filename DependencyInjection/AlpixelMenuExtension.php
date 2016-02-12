<?php

namespace Alpixel\Bundle\MenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AlpixelMenuExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        if (!$container->hasParameter('default_locale')) {
            throw new UndefinedOptionsException('The default locale parameter must be defined under parameters in your symfony configuration');
        }

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $menuBuilder = $container->getDefinition('alpixel_menu.builder');
        $menuBuilder->addMethodCall('setDefaultLocale', [$container->getParameter('default_locale')] );
    }
}
