<?php

namespace ContainerJeZmckY;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_7Nwt0MTService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.7Nwt0MT' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.7Nwt0MT'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'donneesVirusRepository' => ['privates', 'App\\Repository\\DonneesVirusRepository', 'getDonneesVirusRepositoryService', true],
        ], [
            'donneesVirusRepository' => 'App\\Repository\\DonneesVirusRepository',
        ]);
    }
}
