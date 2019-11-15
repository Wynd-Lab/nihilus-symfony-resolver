<?php


namespace Nihilus\CQRSBundle\Builder;

class MiddlewareChainBuilder
{
    private $middlewaresChain = [];

    public function setChains(array $chains)
    {
        $this->middlewaresChain = $chains;
    }

    public function addChain(string $name, array $chain)
    {
        $this->middlewaresChain[$name] = $chain;
    }

    private function build(string $name)
    {
        return $this->buildChainMiddlewares(
            $this->middlewaresChain[$name] ?? $this->middlewaresChain['global'] ?? []
        );
    }

    public function buildAll(array $names)
    {
        $chainsBuild = [];
        foreach ($names as $name) {
            $chainsBuild[$name] = $this->build($name);
        }

        return $chainsBuild;
    }

    private function buildChainMiddlewares(array $chain)
    {
        $chainMiddlewares = [];

        foreach ($chain as $middleware) {
            $prependMiddlewares = false !== strpos($middleware, '@')
                ? $this->buildChainMiddlewares($this->middlewaresChain[substr($middleware, 1)] ?? [])
                : [$middleware];
            if (!empty($prependMiddlewares)) {
                array_push($chainMiddlewares, ...$prependMiddlewares);
            }
        }

        return $chainMiddlewares;
    }
}
