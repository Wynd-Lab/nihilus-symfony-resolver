<?php


namespace Wynd\CQRSBundle\Tests\unit\Query\Resolver;

use Nihilus\QueryPipelineInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Wynd\CQRSBundle\Query\Resolver\ContainerQueryPipelineResolver;

class ContainerQueryPipelineResolverTest extends TestCase
{
    /**
     * @var ContainerQueryPipelineResolver
     */
    private $resolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->resolver = new ContainerQueryPipelineResolver(
            $this->container,
            [
                'global' => ['second_pipeline', 'first_pipeline'],
            ]
        );
    }

    public function testGlobals()
    {
        $firstPipeline = $this->createMock(QueryPipelineInterface::class);
        $secondPipeline = $this->createMock(QueryPipelineInterface::class);

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['pipeline_first_service_id', $firstPipeline],
                ['pipeline_second_service_id', $secondPipeline],
            ]);

        $this->resolver->addPipeline('first_pipeline', 'pipeline_first_service_id');
        $this->resolver->addPipeline('second_pipeline', 'pipeline_second_service_id');

        $this->assertSame(
            [$secondPipeline, $firstPipeline],
            $this->resolver->getGlobals()
        );
    }
}