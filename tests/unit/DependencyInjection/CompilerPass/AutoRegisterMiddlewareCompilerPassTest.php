<?php

namespace Wynd\CQRSBundle\Tests\unit\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMiddlewareCompilerPass;

class AutoRegisterMiddlewareCompilerPassTest extends TestCase
{
    private $compilerPass;

    protected function setUp(): void
    {
        $this->compilerPass = new AutoRegisterMiddlewareCompilerPass(
            'resolver_service_id',
            'resolver_container_tag'
        );
    }

    public function testProcess()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $resolverDefinition = new Definition();

        $handlerAlias = new Alias('middleware_one_middleware');

        $container
            ->expects($this->once())
            ->method('findDefinition')
            ->with('resolver_service_id')
            ->willReturn($resolverDefinition)
        ;

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('resolver_container_tag')
            ->willReturn([
                'one_middleware' => [
                    ['identifier' => 'one_middleware'],
                ],
            ]);
        ;

        $container
            ->expects($this->once())
            ->method('setAlias')
            ->with('middleware_one_middleware')
            ->willReturn($handlerAlias);

        $this->compilerPass->process($container);

        $this->assertTrue($handlerAlias->isPublic());
        $this->assertEquals([
            ['addMiddleware', ['one_middleware', 'middleware_one_middleware']]
        ], $resolverDefinition->getMethodCalls());
    }
}