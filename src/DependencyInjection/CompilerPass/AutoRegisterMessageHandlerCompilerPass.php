<?php

namespace Nihilus\CQRSBundle\DependencyInjection\CompilerPass;

use Nihilus\CQRSBundle\Exception\BadConfigurationMessageHandler;
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
     * @var string
     */
    private $handlerInterface;

    /**
     * AutoRegisterMessageHandlerCompilerPass constructor.
     * @param string $resolverId
     * @param string $tagName
     */
    public function __construct(string $resolverId, string $tagName, string $handlerInterface)
    {
        $this->resolverId = $resolverId;
        $this->tagName = $tagName;
        $this->handlerInterface = $handlerInterface;
    }

    public function process(ContainerBuilder $container): void
    {
        $resolverDefinition = $container->findDefinition($this->resolverId);
        $services = $container->findTaggedServiceIds($this->tagName);

        foreach (array_keys($services) as $serviceId) {
            $aliasName = sprintf('handler_%s', $serviceId);
            $container->setAlias($aliasName, $serviceId)->setPublic(true);

            $handlerClass = $container->getDefinition($serviceId)->getClass();

            if (!in_array($this->handlerInterface, class_implements($handlerClass))) {
                throw new BadConfigurationMessageHandler(sprintf(
                    'unable auto register message handler "%s", expect implement interface "%s", given (%s)',
                    $handlerClass,
                    $this->handlerInterface,
                    implode(', ', class_implements($handlerClass))
                ));
            }

            $handledClass = $handlerClass::getHandledClass();

            if (!class_exists($handledClass)) {
                throw new BadConfigurationMessageHandler(sprintf(
                    'unable auto register message handler, handled class (%s) not exists',
                    $handledClass
                ));
            }

            $resolverDefinition->addMethodCall(
                'addHandler',
                [$handledClass, $aliasName]
            );
        }
    }
}
