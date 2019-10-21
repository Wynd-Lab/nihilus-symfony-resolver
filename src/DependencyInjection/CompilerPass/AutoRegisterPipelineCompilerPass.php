<?php


namespace Wynd\CQRSBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutoRegisterPipelineCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $resolverId;

    /**
     * @var string
     */
    private $tagName;

    public function __construct(string $resolverId, string $tagName)
    {
        $this->resolverId = $resolverId;
        $this->tagName = $tagName;
    }

    public function process(ContainerBuilder $container)
    {
        $resolverDefinition = $container->findDefinition($this->resolverId);
        $services = $container->findTaggedServiceIds($this->tagName);

        foreach ($services as $serviceId => $tags) {
            $aliasServiceName = sprintf('pipeline_%s', $serviceId);
            $container->setAlias($aliasServiceName, $serviceId)->setPublic(true);

            foreach ($tags as $tag) {
                $resolverDefinition->addMethodCall(
                    'addPipeline',
                    [$tag['identifier'], $aliasServiceName]
                );
            }
        }
    }
}
