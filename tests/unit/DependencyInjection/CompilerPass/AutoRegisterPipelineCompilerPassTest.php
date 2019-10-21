<?php

namespace Wynd\CQRSBundle\Tests\unit\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterPipelineCompilerPass;

class AutoRegisterPipelineCompilerPassTest extends TestCase
{
    private $compilerPass;

    protected function setUp(): void
    {
        $this->compilerPass = new AutoRegisterPipelineCompilerPass(
            'resolver_service_id',
            'resolver_container_tag'
        );
    }

    public function testProcess()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $resolverDefinition = new Definition();

        $handlerAlias = new Alias('pipeline_one_pipeline');

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
                'one_pipeline' => [
                    ['identifier' => 'one_pipeline'],
                ],
            ]);
        ;

        $container
            ->expects($this->once())
            ->method('setAlias')
            ->with('pipeline_one_pipeline')
            ->willReturn($handlerAlias);

        $this->compilerPass->process($container);

        $this->assertTrue($handlerAlias->isPublic());
        $this->assertEquals([
            ['addPipeline', ['one_pipeline', 'pipeline_one_pipeline']]
        ], $resolverDefinition->getMethodCalls());
    }
}