<?php


namespace Wynd\CQRSBundle\Tests\Builder;


use PHPUnit\Framework\TestCase;
use Wynd\CQRSBundle\Builder\MiddlewareChainBuilder;

class MiddlewareChainBuilderTest extends TestCase
{
    /**
     * @var MiddlewareChainBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = new MiddlewareChainBuilder();
    }

    public function testBuildAll()
    {
        $this->builder->setChains([]);
        $this->builder->addChain('first_chain', ['a']);
        $this->builder->addChain('second_chain', ['@first_chain', 'b']);
        $this->builder->addChain('three_chain', ['@second_chain', 'c']);
        $this->builder->addChain('fourth_chain', ['d', '@second_chain']);

        $this->assertEquals(
            [
                'first_chain' => ['a'],
                'second_chain' => ['a', 'b'],
                'three_chain' => ['a', 'b', 'c'],
                'fourth_chain' => ['d', 'a', 'b']
            ],
            $this->builder->buildAll(['first_chain', 'second_chain', 'three_chain', 'fourth_chain'])
        );
    }
}