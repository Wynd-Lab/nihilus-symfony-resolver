<?php


namespace Nihilus\CQRSBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Nihilus\CQRSBundle\NihilusCQRSBundle;

class MicroKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new NihilusCQRSBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}