<?php

namespace Nihilus\CQRSBundle\tests\Command\Resolver;

use Nihilus\CommandHandlerInterface;
use Nihilus\CommandHandlerResolverInterface;
use Nihilus\CommandInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Nihilus\CQRSBundle\Command\Resolver\ContainerCommandHandlerResolver;

class ContainerCommandHandlerResolverTest extends TestCase
{
    /**
     * @var ContainerCommandHandlerResolver
     */
    private $resolver;

    /**
     * @var MockObject|ContainerInterface
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->resolver = new ContainerCommandHandlerResolver(
            $this->container
        );
    }

    public function testGetWhenNotHandlerRegisteredInMapping()
    {
        $command = $this->createMock(CommandInterface::class);

        $this->container
            ->expects($this->never())
            ->method('has');
        $this->container
            ->expects($this->never())
            ->method('get');

        $this->assertNull($this->resolver->get($command));
    }

    public function testGetWhenNotHandlerRegisteredInContainer()
    {
        $command = $this
            ->getMockBuilder(CommandInterface::class)
            ->setMockClassName('HelloCommand')
            ->getMock();

        $this->resolver->addHandler(
            'HelloCommand',
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

        $this->assertNull($this->resolver->get($command));
    }

    public function testGetWhenHandlerFound()
    {
        $command = $this
            ->getMockBuilder(CommandInterface::class)
            ->setMockClassName('HelloCommand')
            ->getMock();

        $handler = $this->createMock(CommandHandlerInterface::class);

        $this->resolver->addHandler(
            'HelloCommand',
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

        $this->assertSame($handler, $this->resolver->get($command));
    }

    public function testGetAll()
    {
        $this->assertEquals([], $this->resolver->getAll($this->createMock(CommandInterface::class)));
    }

    public function testGetServiceIdWhenFound()
    {
        $this->resolver->addHandler('queryName', 'serviceId');

        $this->assertEquals('serviceId', $this->resolver->getServiceId('queryName'));
    }

    public function testGetServiceIdWhenNotFound()
    {
        $this->assertNull($this->resolver->getServiceId('unknow'));
    }
}
