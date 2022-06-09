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
  public function applies(ServerRequestInterface $request): bool {
    foreach ($this->matchers as $matcher) {
      if ($matcher->applies($request)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoute(ServerRequestInterface $request, ContextInterface $context): RouteInterface {
    foreach ($this->matchers as $matcher) {
      if ($matcher->applies($request)) {
        return $matcher->getRoute($request, $context);
      }
    }
    throw new \InvalidArgumentException("No route found.");
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
