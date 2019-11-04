<?php


namespace Wynd\CQRSBundle\Tests\unit\Query\Resolver;

use Nihilus\QueryInterface;
use Nihilus\QueryMiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Wynd\CQRSBundle\Query\Resolver\ContainerQueryMiddlewareResolver;

class ContainerQueryMiddlewareResolverTest extends TestCase
{
    /**
     * @var ContainerQueryMiddlewareResolver
     */
    private $resolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->resolver = new ContainerQueryMiddlewareResolver(
            $this->container,
            [
                'global' => ['second_middleware', 'first_middleware'],
            ]
        );
    }

    public function testGet()
    {
        $firstmiddleware = $this->createMock(QueryMiddlewareInterface::class);
        $secondmiddleware = $this->createMock(QueryMiddlewareInterface::class);

        $query = $this->getMockBuilder(QueryInterface::class)
            ->setMockClassName('QueryTest')
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
        $this->resolver->addChain('QueryTest', [
            'first_middleware',
            'second_middleware'
        ]);
        $this->assertSame(
            [$firstmiddleware, $secondmiddleware],
            $this->resolver->get($query)
        );
    }

    public function testGetChain()
    {
        $this->resolver->addChain('one', ['a']);

        $this->assertEquals(['a'], $this->resolver->getChain('one'));
    }
}