<?php

namespace Wynd\CQRSBundle\tests\Query\Resolver;

use Nihilus\QueryHandlerInterface;
use Nihilus\QueryHandlerResolverInterface;
use Nihilus\QueryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Wynd\CQRSBundle\Query\Resolver\ContainerQueryHandlerResolver;

class ContainerQueryHandlerResolverTest extends TestCase
{
    /**
     * @var QueryHandlerResolverInterface
     */
    private $resolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->resolver = new ContainerQueryHandlerResolver(
            $this->container
        );
    }

    public function testGetWhenNotHandlerRegisteredInMapping()
    {
        $query = $this->createMock(QueryInterface::class);

        $this->container
            ->expects($this->never())
            ->method('has');
        $this->container
            ->expects($this->never())
            ->method('get');

        $this->assertNull($this->resolver->get($query));
    }

    public function testGetWhenNotHandlerRegisteredInContainer()
    {
        $query = $this
            ->getMockBuilder(QueryInterface::class)
            ->setMockClassName('HelloQuery')
            ->getMock();

        $this->resolver->addHandler(
            'HelloQuery',
            'alias_handler'
        );

        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('alias_handler')
            ->willReturn(false);

        $this->container
            ->expects($this->never())
            ->method('get');

        $this->assertNull($this->resolver->get($query));
    }

    public function testGetWhenHandlerFound()
    {
        $query = $this
            ->getMockBuilder(QueryInterface::class)
            ->setMockClassName('HelloQuery')
            ->getMock();

        $handler = $this->createMock(QueryHandlerInterface::class);

        $this->resolver->addHandler(
            'HelloQuery',
            'alias_handler'
        );

        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('alias_handler')
            ->willReturn(true);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('alias_handler')
            ->willReturn($handler);

        $this->assertSame($handler, $this->resolver->get($query));
    }

    public function testGetServiceId()
    {
        $this->resolver->addHandler('OneQuery', 'OneService');

        $this->assertEquals('OneService', $this->resolver->getServiceId('OneQuery'));
    }
}