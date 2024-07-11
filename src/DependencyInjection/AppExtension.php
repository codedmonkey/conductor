<?php

namespace CodedMonkey\Conductor\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class AppExtension extends ConfigurableExtension
{
    public function getAlias(): string
    {
        return 'conductor';
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('conductor.storage.path', $mergedConfig['storage']['path']);
        $container->setParameter('conductor.title', $mergedConfig['title']);
        $container->setParameter('conductor.security.public_access', $mergedConfig['security']['public']);
        $container->setParameter('conductor.security.registration_enabled', $mergedConfig['security']['registration']);
        $container->setParameter('conductor.dist_mirroring.enabled', $mergedConfig['dist_mirroring']['enabled']);
        $container->setParameter('conductor.dist_mirroring.preferred', $mergedConfig['dist_mirroring']['preferred']);
        $container->setParameter('conductor.dist_mirroring.dev_packages', $mergedConfig['dist_mirroring']['dev_packages']);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new AppConfiguration();
    }
}
