<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Xylemical\Controller\MiddlewareFactoryInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the middleware factory.
 */
class MiddlewareFactory implements MiddlewareFactoryInterface {

  /**
   * The middleware factory providers.
   *
   * @var \Xylemical\Controller\MiddlewareFactoryInterface[]
   */
  protected array $factories = [];

  /**
   * {@inheritdoc}
   */
  public function getMiddleware(RouteInterface $route): array {
    $middleware = [];
    foreach ($this->factories as $factory) {
      $middleware = array_merge($middleware, $factory->getMiddleware($route));
    }
    return $middleware;
  }

  /**
   * Add a middleware factory.
   *
   * @param \Xylemical\Controller\MiddlewareFactoryInterface $factory
   *   The factory.
   *
   * @return $this
   */
  public function addFactory(MiddlewareFactoryInterface $factory): static {
    $this->factories[] = $factory;
    return $this;
  }

}
