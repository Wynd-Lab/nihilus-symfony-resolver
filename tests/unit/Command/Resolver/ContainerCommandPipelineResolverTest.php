<?php


namespace Wynd\CQRSBundle\Tests\unit\Command\Resolver;

use Nihilus\CommandPipelineInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Wynd\CQRSBundle\Command\Resolver\ContainerCommandPipelineResolver;

class ContainerCommandPipelineResolverTest extends TestCase
{
    /**
     * @var ContainerCommandPipelineResolver
     */
    private $resolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->resolver = new ContainerCommandPipelineResolver(
            $this->container,
            [
                'global' => ['second_pipeline', 'first_pipeline'],
            ]
        );
    }

    public function testGlobals()
    {
        $firstPipeline = $this->createMock(CommandPipelineInterface::class);
        $secondPipeline = $this->createMock(CommandPipelineInterface::class);

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