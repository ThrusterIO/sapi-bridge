<?php

declare(strict_types=1);

namespace Thruster\SapiBridge;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * Class SapiBridge.
 *
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class SapiBridge
{
    /** @var ServerRequestInterface */
    private $serverRequest;

    /** @var RequestHandlerInterface */
    private $handler;

    public function __construct(ServerRequestInterface $request)
    {
        $this->serverRequest = $request;
    }

    public static function createFromGlobals(): self
    {
        return new static(ServerRequestFactory::fromGlobals());
    }

    public function attach(RequestHandlerInterface $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    public function run(): void
    {
        $emitter = new SapiEmitter();

        $emitter->emit($this->handler->handle($this->serverRequest));
    }
}
