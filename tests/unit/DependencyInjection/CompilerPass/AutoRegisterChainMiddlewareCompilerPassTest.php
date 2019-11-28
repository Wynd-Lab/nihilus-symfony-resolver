<?php

namespace Nihilus\CQRSBundle\Tests\DependencyInjection\CompilerPass;

use Nihilus\CQRSBundle\Tests\unit\Fake\HelloCommandHandlerFake;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Nihilus\CQRSBundle\Builder\MiddlewareChainBuilder;
use Nihilus\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterChainMiddlewareCompilerPass;

class AutoRegisterChainMiddlewareCompilerPassTest extends TestCase
{
    /**
     * @var AutoRegisterChainMiddlewareCompilerPass
     */
    private $compilerPass;

    /** @var MiddlewareChainBuilder|MockObject */
    private $chainBuilder;

    protected function setUp(): void
    {
        $this->chainBuilder = $this->createMock(MiddlewareChainBuilder::class);
        $this->compilerPass = new AutoRegisterChainMiddlewareCompilerPass(
            'resolver_id',
            'handler_tag_name',
            'type',
            $this->chainBuilder
        );
    }

    public function testProcess()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $resolverDefinition = new Definition();
        $handlerDefinition = new Definition(HelloCommandHandlerFake::class);

        $containerBuilder
            ->expects($this->at(1))
            ->method('getParameter')
            ->with('nihilus.cqrs.type_middlewares_config')
            ->willReturn(['one_middleware' => ['a', 'b', 'c']]);
        $containerBuilder
            ->expects($this->at(2))
            ->method('getParameter')
            ->with('nihilus.cqrs.type_middlewares_binding')
            ->willReturn(['OneQuery' => ['a', 'b']]);
        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn([
                'handle_one' => [
                    ['handle' => 'OneQuery'],
                ],
                'handle_two' => [
                    ['handle' => 'TwoQuery'],
                ]
            ]);

        $containerBuilder
            ->expects($this->once())
            ->method('findDefinition')
            ->with('resolver_id')
            ->willReturn($resolverDefinition);

        $containerBuilder
            ->method('getDefinition')
            ->willReturn($handlerDefinition);

        $containerBuilder
            ->expects($this->once())
            ->method('setParameter')
            ->with('nihilus.cqrs.models.type', [
                'OneQuery',
                'TwoQuery'
            ]);

        $this->chainBuilder
            ->expects($this->once())
            ->method('buildAll')
            ->with(['OneQuery', 'TwoQuery'])
            ->willReturn([
                'OneQuery' => ['a', 'b'],
                'TwoQuery' => ['d'],
            ]);
        $this->chainBuilder
            ->expects($this->once())
            ->method('setChains')
            ->with([
                'one_middleware' => ['a', 'b', 'c'],
            ]);
        $this->chainBuilder
            ->expects($this->once())
            ->method('addChain')
            ->with('OneQuery', ['a', 'b']);

        $this->compilerPass->process($containerBuilder);

        $this->assertEquals([
            ['addChain', ['OneQuery', ['a', 'b']]],
            ['addChain', ['TwoQuery', ['d']]],
        ], $resolverDefinition->getMethodCalls());
    }
}