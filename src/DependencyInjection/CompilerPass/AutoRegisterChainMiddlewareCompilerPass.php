<?php


namespace Nihilus\CQRSBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nihilus\CQRSBundle\Builder\MiddlewareChainBuilder;

class AutoRegisterChainMiddlewareCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $resolverId;

    /**
     * @var string
     */
    private $handlerTagName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var MiddlewareChainBuilder
     */
    private $chainBuilder;

    public function __construct(
        string $resolverId,
        string $handlerTagName,
        string $type,
        MiddlewareChainBuilder $chainBuilder
    ) {
        $this->resolverId = $resolverId;
        $this->handlerTagName = $handlerTagName;
        $this->type = $type;
        $this->chainBuilder = $chainBuilder;
    }

    public function process(ContainerBuilder $container): void
    {
        $resolverDefinition = $container->findDefinition($this->resolverId);

        $chains = $container->getParameter(sprintf('nihilus.cqrs.%s_middlewares_config', $this->type));

        $this->chainBuilder->setChains($chains);

        $modelsBinding = $container->getParameter(sprintf('nihilus.cqrs.%s_middlewares_binding', $this->type));
        foreach ($modelsBinding as $model => $config) {
            $this->chainBuilder->addChain($model, $config);
        }

        $models = $this->discoverModels($container);

        $chainsBuild = $this->chainBuilder->buildAll($models);

        foreach ($chainsBuild as $chainName => $chainMiddlewares) {
            $resolverDefinition->addMethodCall(
                'addChain',
                [$chainName, $chainMiddlewares]
            );
        }

        $container->setParameter(sprintf('nihilus.cqrs.models.%s', $this->type), $models);
    }

    private function discoverModels(ContainerBuilder $container): array
    {
        $messagesHandler = $container->findTaggedServiceIds($this->handlerTagName);

        $models = [];
        foreach ($messagesHandler as $tags) {
            foreach ($tags as $tag) {
                $models[] = $tag['handle'];
            }
        }

        return $models;
    }
}
