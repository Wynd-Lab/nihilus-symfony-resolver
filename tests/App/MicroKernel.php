<?php


namespace Wynd\CQRSBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Wynd\CQRSBundle\WyndCQRSBundle;

class MicroKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new WyndCQRSBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}