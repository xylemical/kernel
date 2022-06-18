<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Route;

use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Controller\ContextInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides a service collector for route matchers.
 */
class RouteMatcher implements RouteMatcherInterface {

  /**
   * The route matchers.
   *
   * @var \Xylemical\Kernel\Route\RouteMatcherInterface[]
   */
  protected array $matchers = [];

  /**
   * {@inheritdoc}
   */
  public function getRoute(ServerRequestInterface $request, ContextInterface $context): ?RouteInterface {
    foreach ($this->matchers as $matcher) {
      if ($route = $matcher->getRoute($request, $context)) {
        return $route;
      }
    }
    return NULL;
  }

  /**
   * Add a route matcher.
   *
   * @param \Xylemical\Kernel\Route\RouteMatcherInterface $matcher
   *   The matcher.
   *
   * @return $this
   */
  public function addMatcher(RouteMatcherInterface $matcher): static {
    $this->matchers[] = $matcher;
    return $this;
  }

}
