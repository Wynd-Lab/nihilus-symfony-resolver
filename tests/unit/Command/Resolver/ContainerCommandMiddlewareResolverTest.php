<?php


namespace Nihilus\CQRSBundle\Tests\unit\Command\Resolver;

use Nihilus\CommandInterface;
use Nihilus\CommandMiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Nihilus\CQRSBundle\Command\Resolver\ContainerCommandMiddlewareResolver;

class ContainerCommandMiddlewareResolverTest extends TestCase
{
    /**
     * @var ContainerCommandMiddlewareResolver
     */
    private $resolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->resolver = new ContainerCommandMiddlewareResolver(
            $this->container,
            [
                'global' => ['second_middleware', 'first_middleware'],
            ]
        );
    }

    public function testGet()
    {
        $firstmiddleware = $this->createMock(CommandMiddlewareInterface::class);
        $secondmiddleware = $this->createMock(CommandMiddlewareInterface::class);

        $command = $this->getMockBuilder(CommandInterface::class)
            ->setMockClassName('CommandTest')
            ->getMock();

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['middleware_first_service_id', $firstmiddleware],
                ['middleware_second_service_id', $secondmiddleware],
            ]);

        $this->resolver->addmiddleware('first_middleware', 'middleware_first_service_id');
        $this->resolver->addmiddleware('second_middleware', 'middleware_second_service_id');

        $this->resolver->addChain('CommandTest', [
            'first_middleware',
            'second_middleware',
        ]);

        $this->assertSame(
            [$firstmiddleware, $secondmiddleware],
            $this->resolver->get($command)
        );
    }

    public function testGetChainWhenFound()
    {
        $this->resolver->addChain('found', ['a', 'c']);
        $this->assertEquals(['a', 'c'], $this->resolver->getChain('found'));
    }
}