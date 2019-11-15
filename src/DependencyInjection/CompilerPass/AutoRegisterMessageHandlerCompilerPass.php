<?php

namespace Nihilus\CQRSBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutoRegisterMessageHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $resolverId;
    /**
     * @var string
     */
    private $tagName;

    /**
     * AutoRegisterMessageHandlerCompilerPass constructor.
     * @param string $resolverId
     * @param string $tagName
     */
    public function __construct(string $resolverId, string $tagName)
    {
        $this->resolverId = $resolverId;
        $this->tagName = $tagName;
    }

    public function process(ContainerBuilder $container): void
    {
        $resolverDefinition = $container->findDefinition($this->resolverId);
        $services = $container->findTaggedServiceIds($this->tagName);

        foreach ($services as $serviceId => $tags) {
            $aliasName = sprintf('handler_%s', $serviceId);
            $container->setAlias($aliasName, $serviceId)->setPublic(true);

            foreach ($tags as $tag) {
                $resolverDefinition->addMethodCall(
                    'addHandler',
                    [$tag['handle'], $aliasName]
                );
            }
        }
    }
}
