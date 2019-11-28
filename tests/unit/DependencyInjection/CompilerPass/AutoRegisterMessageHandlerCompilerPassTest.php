<?php

namespace Nihilus\CQRSBundle\Tests\unit\DependencyInjection\CompilerPass;

use Nihilus\CQRSBundle\Command\CommandHandlerInterface;
use Nihilus\CQRSBundle\Exception\BadConfigurationMessageHandler;
use Nihilus\CQRSBundle\Tests\unit\Fake\HelloCommandHandlerFake;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Nihilus\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMessageHandlerCompilerPass;

class AutoRegisterMessageHandlerCompilerPassTest extends TestCase
{
    /**
     * @var AutoRegisterMessageHandlerCompilerPass
     */
    private $compilerPass;

    public function setUp(): void
    {
        $this->compilerPass = new AutoRegisterMessageHandlerCompilerPass(
            'resolver_service_id',
            'resolver_container_tag',
            CommandHandlerInterface::class
        );
    }

    public function testProcessBadConfiguration()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $byeCommandHandler = $this->getMockBuilder(\Nihilus\CommandHandlerInterface::class)
            ->setMockClassName('ByeCommandHandler')
            ->getMock();

        $resolverDefinition = new Definition();
        $handlerDefinition = new Definition(get_class($byeCommandHandler));

        $handlerAlias = new Alias('handler_handler_one');

        $container
            ->expects($this->once())
            ->method('findDefinition')
            ->with('resolver_service_id')
            ->willReturn($resolverDefinition)
        ;

        $container
            ->method('getDefinition')
            ->willReturn($handlerDefinition);

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('resolver_container_tag')
            ->willReturn([
                'handler_one' => [
                    ['handle' => 'CommandName'],
                ],
            ])
        ;

        $container
            ->expects($this->once())
            ->method('setAlias')
            ->with('handler_handler_one')
            ->willReturn($handlerAlias);

        $this->expectException(BadConfigurationMessageHandler::class);
        $this->compilerPass->process($container);
    }

    public function testProcessWhenGoodConfiguration()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $resolverDefinition = new Definition();
        $handlerDefinition = new Definition(HelloCommandHandlerFake::class);

        $handlerAlias = new Alias('handler_handler_one');

        $container
            ->expects($this->once())
            ->method('findDefinition')
            ->with('resolver_service_id')
            ->willReturn($resolverDefinition)
        ;

        $container
            ->method('getDefinition')
            ->willReturn($handlerDefinition);

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('resolver_container_tag')
            ->willReturn([
                'handler_one' => [],
            ])
        ;

        $container
            ->expects($this->once())
            ->method('setAlias')
            ->with('handler_handler_one')
            ->willReturn($handlerAlias);

        $this->compilerPass->process($container);

        $this->assertTrue($handlerAlias->isPublic());
        $this->assertEquals([
            ['addHandler', ['HelloCommand', 'handler_handler_one']]
        ], $resolverDefinition->getMethodCalls());
    }
}